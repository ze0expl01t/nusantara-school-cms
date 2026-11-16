<?php

namespace App\Services;

use App\Models\CheckpointScanResult;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Throwable;

class CheckpointScanService
{
    public function run(string $triggeredBy = 'manual'): CheckpointScanResult
    {
        $startedAt = microtime(true);
        $exitCode = 0;
        $raw = '';

        // ═══ Cek command terdeteksi di runtime ini ═══
        try {
            $availableCommands = array_keys(Artisan::all());
            $commandExists = in_array('checkpoint:scan', $availableCommands, true);
        } catch (Throwable $e) {
            $commandExists = false;
        }

        if ($commandExists) {
            // ── Cara ideal: Artisan::call() ──
            try {
                $buffer = new BufferedOutput;
                $exitCode = Artisan::call('checkpoint:scan', ['--json' => true], $buffer);
                $raw = $buffer->fetch();

                if (empty($raw)) {
                    $raw = Artisan::output();
                }
            } catch (Throwable $e) {
                $raw = $this->runViaProcess();
                $exitCode = str_starts_with($raw, 'ERROR') ? 1 : 0;
            }
        } else {
            // ── Fallback: subprocess via Symfony Process ──
            // Package hanya register command saat runningInConsole() === true,
            // jadi HTTP context perlu spawn artisan CLI baru.
            Log::info('Checkpoint scan: using Symfony Process fallback');
            $raw = $this->runViaProcess();
            $exitCode = str_starts_with($raw, 'ERROR') ? 1 : 0;
        }

        $duration = round(microtime(true) - $startedAt, 2);
        $parsed = $this->parseOutput($raw);

        return CheckpointScanResult::create([
            'user_id' => Auth::guard('twill_users')->id(),
            'triggered_by' => $triggeredBy,
            'total_checks' => $parsed['total'],
            'pass_count' => $parsed['pass'],
            'warn_count' => $parsed['warn'],
            'fail_count' => $parsed['fail'],
            'duration_seconds' => $duration,
            'exit_code' => $exitCode,
            'raw_output' => $raw,
            'parsed_output' => $parsed,
        ]);
    }

    /**
     * Fallback via Symfony Process — lebih aman dari shell_exec karena:
     *  - Tidak pakai shell (bypass shell parsing entirely)
     *  - Command & args dipisah sebagai array
     *  - Ada timeout built-in
     *  - Ada exception handling proper
     *
     * Security: semua argumen di-pass sebagai array, TIDAK ADA input user.
     */
    protected function runViaProcess(): string
    {
        $php = $this->resolvePhpCliBinary();
        $artisan = base_path('artisan');

        try {
            $process = new Process(
                [$php, $artisan, 'checkpoint:scan', '--json'],
                base_path(),     // working directory
                null,            // env (inherit)
                null,            // input
                180              // timeout 3 menit
            );

            $process->run();

            $stdout = $process->getOutput();
            $stderr = $process->getErrorOutput();

            // Kalau ada stderr tapi stdout kosong, artinya error
            if (empty($stdout) && ! empty($stderr)) {
                return 'ERROR: '.trim($stderr);
            }

            // Gabungkan stdout + stderr kalau perlu
            if (empty($stdout)) {
                return 'ERROR: Process returned empty output. Exit code: '.$process->getExitCode();
            }

            return $stdout;

        } catch (ProcessFailedException $e) {
            return 'ERROR (ProcessFailed): '.$e->getMessage();
        } catch (Throwable $e) {
            return 'ERROR: '.$e->getMessage();
        }
    }

    /**
     * Resolve PHP CLI binary — penting karena PHP_BINARY di FPM
     * bisa mengarah ke php-fpm (bukan CLI).
     */
    protected function resolvePhpCliBinary(): string
    {
        $php = PHP_BINARY ?: 'php';

        // Kalau PHP_BINARY mengarah ke FPM/LSAPI, cari PHP CLI
        if (str_contains($php, 'lsphp') || str_contains($php, 'php-fpm')) {
            $candidates = [
                '/usr/local/lsws/lsphp84/bin/php',
                '/usr/local/lsws/lsphp83/bin/php',
                '/usr/local/lsws/lsphp82/bin/php',
                '/usr/local/lsws/lsphp81/bin/php',
                '/usr/local/bin/php',
                '/usr/bin/php',
            ];

            foreach ($candidates as $candidate) {
                if (is_executable($candidate)) {
                    return $candidate;
                }
            }

            // Fallback terakhir
            return 'php';
        }

        return $php;
    }

    protected function parseOutput(string $raw): array
    {
        $decoded = json_decode($raw, true);

        if (is_array($decoded)) {
            return $this->normalizeJson($decoded);
        }

        return $this->parseText($raw);
    }

    protected function normalizeJson(array $data): array
    {
        $checks = $data;

        if (isset($data['checks']) && is_array($data['checks'])) {
            $checks = $data['checks'];
        } elseif (isset($data['results']) && is_array($data['results'])) {
            $checks = $data['results'];
        }

        $counts = ['total' => 0, 'pass' => 0, 'warn' => 0, 'fail' => 0];
        $items = [];

        foreach ($checks as $check) {
            if (! is_array($check)) {
                continue;
            }

            $status = strtolower($check['status'] ?? 'unknown');
            $counts['total']++;

            if (isset($counts[$status])) {
                $counts[$status]++;
            }

            $items[] = [
                'name' => $check['check'] ?? $check['name'] ?? $check['title'] ?? 'Unknown',
                'status' => $status,
                'message' => $check['message'] ?? $check['description'] ?? null,
                'hashes' => $check['hashes'] ?? [],
            ];
        }

        return array_merge($counts, ['items' => $items]);
    }

    protected function parseText(string $raw): array
    {
        preg_match_all('/\b(PASS|WARN|FAIL|OK|SUCCESS|ERROR)\b/i', $raw, $matches);

        $counts = ['total' => 0, 'pass' => 0, 'warn' => 0, 'fail' => 0];

        foreach ($matches[1] as $status) {
            $status = strtolower($status);

            if (in_array($status, ['pass', 'ok', 'success'], true)) {
                $counts['pass']++;
                $counts['total']++;
            } elseif ($status === 'warn') {
                $counts['warn']++;
                $counts['total']++;
            } elseif (in_array($status, ['fail', 'error'], true)) {
                $counts['fail']++;
                $counts['total']++;
            }
        }

        return $counts;
    }
}

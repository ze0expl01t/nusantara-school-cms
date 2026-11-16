<?php

use A17\Twill\Facades\TwillAppSettings;
use App\Models\CheckpointScanResult;
use App\Services\CheckpointScanService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Inspire Command
|--------------------------------------------------------------------------
*/
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Sitemap Schedule
|--------------------------------------------------------------------------
| Regenerate sitemap.xml setiap hari jam 00:00.
|--------------------------------------------------------------------------
*/
Schedule::command('sitemap:generate')
    ->daily();

/*
|--------------------------------------------------------------------------
| Backup Schedule — dibaca dari Twill AppSettings (backup-settings)
|--------------------------------------------------------------------------
| Semua nilai (jam, timezone, enabled) diambil dari Settings > Backup.
| Kalau belum di-set, pakai default di bawah.
|
| Urutan harian:
|   01:30  backup:clean
|   02:00  backup:run
|   09:00  backup:monitor
|--------------------------------------------------------------------------
*/

$backupTime = '02:00';
$cleanupTime = '01:30';
$monitorTime = '09:00';
$timezone = 'Asia/Jakarta';
$enabled = true;

try {
    $schedule = TwillAppSettings::getGroupDataForSectionAndName('backup-settings', 'schedule');
    $general = TwillAppSettings::getGroupDataForSectionAndName('backup-settings', 'general');

    if (is_array($schedule)) {
        $backupTime = $schedule['backup_time'] ?? $backupTime;
        $cleanupTime = $schedule['cleanup_time'] ?? $cleanupTime;
        $monitorTime = $schedule['monitor_time'] ?? $monitorTime;
        $timezone = $schedule['timezone'] ?? $timezone;
    }

    if (is_array($general)) {
        $enabled = (bool) ($general['enable_backup'] ?? true);
    }
} catch (Throwable $e) {
    // Settings belum di-seed → pakai default
}

if ($enabled) {
    Schedule::command('backup:run')
        ->name('backup-run')
        ->dailyAt($backupTime)
        ->timezone($timezone)
        ->withoutOverlapping(120)
        ->onOneServer()
        ->runInBackground();

    Schedule::command('backup:clean')
        ->name('backup-clean')
        ->dailyAt($cleanupTime)
        ->timezone($timezone)
        ->withoutOverlapping(30);

    Schedule::command('backup:monitor')
        ->name('backup-monitor')
        ->dailyAt($monitorTime)
        ->timezone($timezone)
        ->withoutOverlapping(10);
}

/*
|--------------------------------------------------------------------------
| Checkpoint Scan Schedule
|--------------------------------------------------------------------------
| Auto-run security scanner tiap hari jam 03:00 (setelah backup selesai).
|
| Berguna untuk:
|   - Deteksi dini CVE baru pada composer/npm dependencies
|   - Monitoring hardcoded secrets yang tidak sengaja ter-commit
|   - Audit berkala SQL injection, XSS, dsb.
|
| Hasil scan disimpan di tabel checkpoint_scan_results dan bisa
| dilihat via admin panel: /panel-maintenance/checkpoint-scan
|
| ⚠️ PENTING: ->name() HARUS dipanggil SEBELUM ->withoutOverlapping()
|             dan SEBELUM ->onOneServer()
|--------------------------------------------------------------------------
*/

Schedule::call(function () {
    try {
        app(CheckpointScanService::class)->run('scheduled');

        Log::info('Scheduled checkpoint scan completed');
    } catch (Throwable $e) {
        Log::error('Scheduled checkpoint scan failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
})
    ->name('checkpoint-scan-daily')
    ->dailyAt('03:00')
    ->timezone($timezone)
    ->withoutOverlapping(60)
    ->onOneServer();

/*
|--------------------------------------------------------------------------
| Checkpoint Scan Pruning
|--------------------------------------------------------------------------
| Hapus riwayat scan > 6 bulan supaya tabel tidak membengkak.
| Dijalankan tanggal 1 setiap bulan, jam 01:00.
|--------------------------------------------------------------------------
*/

Schedule::call(function () {
    try {
        $deleted = CheckpointScanResult::where('created_at', '<', now()->subMonths(6))->delete();

        Log::info('Checkpoint scan pruning completed', [
            'deleted_count' => $deleted,
        ]);
    } catch (Throwable $e) {
        Log::error('Checkpoint scan pruning failed', [
            'error' => $e->getMessage(),
        ]);
    }
})
    ->name('checkpoint-scan-prune')
    ->monthlyOn(1, '01:00')
    ->timezone($timezone)
    ->withoutOverlapping(30);

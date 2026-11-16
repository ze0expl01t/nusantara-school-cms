<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        // Best practice Twill: middleware 'can:' untuk authorization
        $this->middleware('can:backup-access', ['only' => ['show', 'create']]);
        $this->middleware('can:backup-download', ['only' => ['download']]);
        $this->middleware('can:backup-delete', ['only' => ['delete']]);
    }

    /**
     * Tampilkan halaman Backup Management di Twill admin.
     */
    public function show(): View
    {
        return view('twill.backup');
    }

    /**
     * Jalankan backup manual.
     */
    public function create(Request $request): RedirectResponse
    {
        set_time_limit(300);
        ignore_user_abort(true);
        try {
            Artisan::call('backup:run');
            $output = Artisan::output();

            Log::info('Manual backup triggered from Twill', [
                'user' => $request->user('twill_users')?->email ?? 'unknown',
                'output' => $output,
            ]);

            return back()->with('backup_success', 'Backup berhasil dibuat.');
        } catch (\Throwable $e) {
            Log::error('Manual backup failed', [
                'user' => $request->user('twill_users')?->email ?? 'unknown',
                'error' => $e->getMessage(),
            ]);

            return back()->with('backup_error', 'Backup gagal: '.$e->getMessage());
        }
    }

    /**
     * Download file backup.
     *
     * Support subfolder path — contoh: "smkmuh1blora/smkmuh1blora-2026-09-23.zip"
     */
    public function download(Request $request, string $file): StreamedResponse
    {
        $diskName = config('backup.destination.disks.0', 'backups');
        $disk = Storage::disk($diskName);

        // Security: cegah path traversal TAPI tetap izinkan subfolder
        $file = str_replace(['..', "\0", '\\'], '', $file);

        // Kalau file tidak ada di path langsung, coba di subfolder backup.name
        if (! $disk->exists($file)) {
            $backupName = config('backup.name', 'smkmuh1blora');
            $altPath = $backupName.'/'.basename($file);

            if ($disk->exists($altPath)) {
                $file = $altPath;
            } else {
                abort(404, 'File backup tidak ditemukan.');
            }
        }

        Log::info('Backup downloaded', [
            'user' => $request->user('twill_users')?->email ?? 'unknown',
            'file' => $file,
        ]);

        return $disk->download($file);
    }

    /**
     * Hapus file backup.
     *
     * Support subfolder path — contoh: "smkmuh1blora/smkmuh1blora-2026-09-23.zip"
     */
    public function delete(Request $request, string $file): RedirectResponse
    {
        $diskName = config('backup.destination.disks.0', 'backups');
        $disk = Storage::disk($diskName);

        // Security: cegah path traversal TAPI tetap izinkan subfolder
        $file = str_replace(['..', "\0", '\\'], '', $file);

        // Kalau file tidak ada di path langsung, coba di subfolder backup.name
        if (! $disk->exists($file)) {
            $backupName = config('backup.name', 'smkmuh1blora');
            $altPath = $backupName.'/'.basename($file);

            if ($disk->exists($altPath)) {
                $file = $altPath;
            } else {
                return back()->with('backup_error', 'File tidak ditemukan.');
            }
        }

        try {
            $disk->delete($file);

            Log::warning('Backup deleted manually', [
                'user' => $request->user('twill_users')?->email ?? 'unknown',
                'file' => $file,
            ]);

            return back()->with('backup_success', 'Backup '.basename($file).' berhasil dihapus.');
        } catch (\Throwable $e) {
            return back()->with('backup_error', 'Gagal menghapus: '.$e->getMessage());
        }
    }
}

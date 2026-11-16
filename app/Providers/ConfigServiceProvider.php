<?php

namespace App\Providers;

use A17\Twill\Facades\TwillAppSettings;
use Illuminate\Support\ServiceProvider;
use Spatie\Backup\Notifications\Notifications\BackupHasFailed;
use Spatie\Backup\Notifications\Notifications\BackupWasSuccessful;
use Spatie\Backup\Notifications\Notifications\UnhealthyBackupWasFound;
use Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumStorageInMegabytes;

class ConfigServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Hanya override jika settings group sudah ada
        try {
            $settings = $this->getBackupSettings();
        } catch (\Throwable $e) {
            // Settings belum di-seed, gunakan default dari config
            return;
        }

        if (empty($settings)) {
            return;
        }

        // Override config Spatie Backup
        $this->overrideBackupConfig($settings);
    }

    /**
     * Ambil semua settings dari group 'backup-settings'.
     */
    protected function getBackupSettings(): array
    {
        $groups = ['general', 'schedule', 'notifications'];
        $result = [];

        foreach ($groups as $group) {
            try {
                $data = TwillAppSettings::getGroupDataForSectionAndName(
                    'backup-settings',
                    $group
                );

                if ($data) {
                    $result = array_merge($result, $data);
                }
            } catch (\Throwable $e) {
                // Skip jika section belum ada
            }
        }

        return $result;
    }

    /**
     * Override config('backup.*') dengan nilai dari settings.
     */
    protected function overrideBackupConfig(array $s): void
    {
        // 1. Enable/disable backup
        if (isset($s['enable_backup'])) {
            config(['backup.enabled' => (bool) $s['enable_backup']]);
        }

        // 2. Retention
        if (isset($s['retention_days'])) {
            config([
                'backup.cleanup.default_strategy.keep_all_backups_for_days' => (int) $s['retention_days'],
                'backup.cleanup.default_strategy.keep_daily_backups_for_days' => (int) $s['retention_days'],
            ]);
        }

        // 3. Max size
        if (isset($s['max_size_mb'])) {
            config([
                'backup.cleanup.default_strategy.delete_oldest_backups_when_using_more_megabytes_than' => (int) $s['max_size_mb'],
                'backup.monitor_backups.0.health_checks.'.MaximumStorageInMegabytes::class => (int) $s['max_size_mb'],
            ]);
        }

        // 4. Sumber backup
        if (isset($s['backup_database'])) {
            config(['backup.source.databases' => $s['backup_database'] ? ['mysql'] : []]);
        }

        if (isset($s['backup_files'])) {
            config([
                'backup.source.files.include' => $s['backup_files']
                    ? [storage_path('app/public/uploads')]
                    : [],
            ]);
        }

        // 5. Email notifikasi
        if (! empty($s['email_to'])) {
            config(['backup.notifications.mail.to' => $s['email_to']]);
        }

        // 6. Notifikasi on/off
        if (isset($s['notify_on_failure'])) {
            config([
                'backup.notifications.notifications.'.BackupHasFailed::class => $s['notify_on_failure'] ? ['mail'] : [],
            ]);
        }

        if (isset($s['notify_on_success'])) {
            config([
                'backup.notifications.notifications.'.BackupWasSuccessful::class => $s['notify_on_success'] ? ['mail'] : [],
            ]);
        }

        if (isset($s['notify_on_unhealthy'])) {
            config([
                'backup.notifications.notifications.'.UnhealthyBackupWasFound::class => $s['notify_on_unhealthy'] ? ['mail'] : [],
            ]);
        }
    }
}

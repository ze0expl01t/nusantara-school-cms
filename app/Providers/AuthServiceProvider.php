<?php

namespace App\Providers;

use A17\Twill\AuthServiceProvider as TwillAuthServiceProvider;
use A17\Twill\Models\Enums\UserRole;

class AuthServiceProvider extends TwillAuthServiceProvider
{
    /**
     * Role yang boleh akses Backup Settings & Backup UI.
     */
    protected const BACKUP_ROLES = [
        UserRole::ADMIN,
    ];

    public function boot(): void
    {
        parent::boot();

        // Akses ke Settings > Backup
        $this->define('backup-access', function ($user) {
            return $this->authorize($user, function ($user) {
                return $this->userHasRole($user, self::BACKUP_ROLES);
            });
        });

        // Download file backup
        $this->define('backup-download', function ($user) {
            return $this->authorize($user, function ($user) {
                return $this->userHasRole($user, self::BACKUP_ROLES);
            });
        });

        // Hapus file backup
        $this->define('backup-delete', function ($user) {
            return $this->authorize($user, function ($user) {
                return $this->userHasRole($user, self::BACKUP_ROLES);
            });
        });
    }
}

<?php

namespace App\Providers;

use A17\Twill\Facades\TwillAppSettings;
use A17\Twill\Facades\TwillNavigation;
use A17\Twill\Services\Settings\SettingsGroup;
use A17\Twill\View\Components\Navigation\NavigationLink;
use App\Http\Controllers\Twill\LoginController;
use App\Models\Achievement;
use App\Models\Agenda;
use App\Models\Brosur;
use App\Models\Extracurricular;
use App\Models\Facility;
use App\Models\JobVacancy;
use App\Models\Jurusan;
use App\Models\MenuLink;
use App\Models\NewsletterSubscriber;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\TeamMember;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // ═══════════════════════════════════════════════════════════════
        // OVERRIDE TWILL LOGIN CONTROLLER
        // ═══════════════════════════════════════════════════════════════
        // Fix bug: Spatie ActivityLog TypeError di saat logout ketika
        // $this->guard()->user() return null (session expired / sudah logout).
        //
        // Twill default: LoginController.php line 118
        //   activity()->performedOn($this->guard()->user())->...
        //
        // Kita override dengan custom controller yang handle null user.
        //
        // File custom: app/Http/Controllers/Twill/LoginController.php
        // ═══════════════════════════════════════════════════════════════
        $this->app->bind(
            \A17\Twill\Http\Controllers\Admin\LoginController::class,
            LoginController::class
        );
    }

    public function boot(): void
    {
        $this->registerMorphMap();
        $this->registerNavigation();
        $this->registerSettingsGroups();
        $this->registerRateLimiters();
        $this->forceHttps();
    }

    /**
     * =====================================================================
     * MORPH MAP — untuk dashboard Twill & polymorphic relations
     * =====================================================================
     *
     * Mapping ini WAJIB ada agar:
     *  - Dashboard Twill bisa resolve module dari key string ('pages', 'posts', dst)
     *  - Activity log tercatat dengan alias konsisten (bukan FQN panjang)
     *  - Polymorphic relation lebih efisien (tidak pakai FQN di database)
     *
     * Referensi: https://laravel.com/docs/eloquent-relationships#polymorphic-relationships
     */
    protected function registerMorphMap(): void
    {
        // Daftar mapping: alias → FQN model
        $map = [
            // ─── Konten ─────────────────────────────────────
            'pages' => Page::class,
            'posts' => Post::class,
            'brosurs' => Brosur::class,

            // ─── Sekolah ────────────────────────────────────
            'projects' => Project::class,
            'agendas' => Agenda::class,
            'extracurriculars' => Extracurricular::class,
            'facilities' => Facility::class,
            'teamMembers' => TeamMember::class,
            'jurusans' => Jurusan::class,
            'achievements' => Achievement::class,
            'jobVacancies' => JobVacancy::class,

            // ─── Menu & Newsletter ──────────────────────────
            'menuLinks' => MenuLink::class,
            'newsletterSubscribers' => NewsletterSubscriber::class,
        ];

        // Defensif: filter hanya model yang benar-benar ada (class_exists)
        // sehingga tidak error kalau ada module yang belum dibuat
        $map = array_filter($map, fn ($class) => class_exists($class));

        Relation::morphMap($map);
    }

    /**
     * =====================================================================
     * NAVIGASI SIDEBAR ADMIN (Compact Version)
     * =====================================================================
     */
    protected function registerNavigation(): void
    {
        // =====================================================================
        // GROUP 1: KONTEN — Halaman, Berita, Brosur
        // =====================================================================
        TwillNavigation::addLink(
            NavigationLink::make()
                ->title('Konten')
                ->forModule('pages') // Menggunakan module 'pages' sebagai anchor
                ->doNotAddSelfAsFirstChild()
                ->setChildren([
                    NavigationLink::make()->forModule('pages')->title('Halaman'),
                    NavigationLink::make()->forModule('posts')->title('Berita'),
                    NavigationLink::make()->forModule('brosurs')->title('Brosur'),
                ])
        );

        // =====================================================================
        // GROUP 2: SEKOLAH — Project, Agenda, Ekskul, Fasilitas, Tim & Pengajar, Jurusan, Prestasi, Bursa Kerja
        // =====================================================================
        TwillNavigation::addLink(
            NavigationLink::make()
                ->title('Sekolah')
                ->forModule('projects') // Menggunakan module 'projects' sebagai anchor
                ->doNotAddSelfAsFirstChild()
                ->setChildren([
                    NavigationLink::make()->forModule('projects')->title('Project'),
                    NavigationLink::make()->forModule('agendas')->title('Agenda'),
                    NavigationLink::make()->forModule('extracurriculars')->title('Ekskul'),
                    NavigationLink::make()->forModule('facilities')->title('Fasilitas'),
                    NavigationLink::make()->forModule('teamMembers')->title('Tim & Pengajar'),
                    NavigationLink::make()->forModule('jurusans')->title('Jurusan'),
                    NavigationLink::make()->forModule('achievements')->title('Prestasi'),
                    NavigationLink::make()->forModule('jobVacancies')->title('Bursa Kerja'),
                ])
        );

        // =====================================================================
        // MENU (standalone)
        // =====================================================================
        TwillNavigation::addLink(
            NavigationLink::make()
                ->forModule('menuLinks')
                ->title('Menu')
        );

        // =====================================================================
        // NEWSLETTER (standalone) — conditional
        // =====================================================================
        if (Route::has('twill.newsletterSubscribers.index')) {
            TwillNavigation::addLink(
                NavigationLink::make()
                    ->forModule('newsletterSubscribers')
                    ->title('Newsletter')
            );
        }

        // =====================================================================
        // GROUP 3: SISTEM — Analytics, Backup, Checkpoint Scan
        // =====================================================================
        TwillNavigation::addLink(
            NavigationLink::make()
                ->title('Sistem')
                ->forRoute('twill.analytics.index') // Tetap menggunakan route sebagai anchor
                ->doNotAddSelfAsFirstChild()
                ->setChildren([
                    NavigationLink::make()
                        ->title('Analytics')
                        ->forRoute('twill.analytics.index'),

                    NavigationLink::make()
                        ->title('Backup')
                        ->forRoute('twill.backup.show'),

                    // ═══════════════════════════════════════════════════
                    // 🆕 CHECKPOINT SCAN
                    // ═══════════════════════════════════════════════════
                    // Security scanner dari andreapollastri/checkpoint.
                    // Trigger `php artisan checkpoint:scan` via UI admin.
                    //
                    // Route didefinisikan di routes/twill.php sehingga:
                    //   - URL:  /panel-maintenance/checkpoint-scan
                    //   - Name: twill.checkpoint-scan.index
                    //
                    // Controller: App\Http\Controllers\Twill\CheckpointScanController
                    // Service:    App\Services\CheckpointScanService
                    // Model:      App\Models\CheckpointScanResult
                    // View:       resources/views/twill/checkpoint-scan/index.blade.php
                    // ═══════════════════════════════════════════════════
                    NavigationLink::make()
                        ->title('Checkpoint Scan')
                        ->forRoute('twill.checkpoint-scan.index'),
                ])
        );
    }

    /**
     * =====================================================================
     * SETTINGS GROUPS (Twill AppSettings)
     * =====================================================================
     */
    protected function registerSettingsGroups(): void
    {
        // Homepage
        TwillAppSettings::registerSettingsGroup(
            SettingsGroup::make()
                ->name('homepage')
                ->label('Homepage')
        );

        // General
        TwillAppSettings::registerSettingsGroup(
            SettingsGroup::make()
                ->name('general')
                ->label('Pengaturan Situs')
        );

        // Sitemap
        TwillAppSettings::registerSettingsGroup(
            SettingsGroup::make()
                ->name('sitemap')
                ->label('Sitemap')
        );

        // Maintenance
        TwillAppSettings::registerSettingsGroup(
            SettingsGroup::make()
                ->name('maintenance')
                ->label('Maintenance Mode')
        );

        // Backup Settings — hanya untuk user dengan gate 'backup-access'
        TwillAppSettings::registerSettingsGroup(
            SettingsGroup::make()
                ->name('backup-settings')
                ->label('Backup')
                ->availableWhen(fn () => Auth::guard('twill_users')->user()?->can('backup-access') ?? false)
        );
    }

    /**
     * =====================================================================
     * RATE LIMITERS (Hardening)
     * =====================================================================
     */
    protected function registerRateLimiters(): void
    {
        // Login rate limiter
        RateLimiter::for('login', function (Request $request) {
            $key = strtolower($request->input('email', '')).'|'.$request->ip();

            return [
                Limit::perMinute(5)->by($key),
                Limit::perHour(20)->by($request->ip()),
            ];
        });

        // Agenda ICS rate limiter
        RateLimiter::for('agenda-ics', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
        });

        // API rate limiter
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * =====================================================================
     * FORCE HTTPS (Hardening)
     * =====================================================================
     */
    protected function forceHttps(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}

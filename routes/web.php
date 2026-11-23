<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AgendaFrontendController;
use App\Http\Controllers\BrosurFrontendController;
use App\Http\Controllers\ExtracurricularFrontendController;
use App\Http\Controllers\FacilityFrontendController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageDisplayController;
use App\Http\Controllers\PostFrontendController;
use App\Http\Controllers\ProjectFrontendController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Twill\NewsletterSubscriberController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =====================================================================
// HOMEPAGE
// =====================================================================
Route::get('/', [PageDisplayController::class, 'home'])
    ->name('frontend.home');

// =====================================================================
// FRONTEND MODULE ROUTES (detail & calendar export)
// =====================================================================
Route::get('/ekstrakurikuler/{slug}', [ExtracurricularFrontendController::class, 'show'])
    ->name('extracurricular.show');

Route::get('/fasilitas/{slug}', [FacilityFrontendController::class, 'show'])
    ->name('facility.show');

Route::get('/agenda/{slug}/ics', [AgendaFrontendController::class, 'downloadIcs'])
    ->middleware('throttle:agenda-ics')
    ->name('agenda.ics');

Route::get('/agenda/{slug}', [AgendaFrontendController::class, 'show'])
    ->name('agenda.show');

Route::get('/post/{slug}', [PostFrontendController::class, 'show'])
    ->name('post.show');

Route::get('/project/{slug}', [ProjectFrontendController::class, 'show'])
    ->name('project.show');

Route::get('/brosur/{slug}', [BrosurFrontendController::class, 'show'])
    ->name('brosur.show');

Route::get('/jurusan/{slug}', [JurusanController::class, 'show'])
    ->name('jurusan.show');

Route::get('/prestasi/{slug}', [AchievementController::class, 'show'])
    ->name('achievement.show');

Route::get('/bursa-kerja/{slug}', [JobVacancyController::class, 'show'])
    ->name('jobVacancy.show');

// =====================================================================
// SITEMAP XML
// =====================================================================
Route::get('/sitemap.xml', [SitemapController::class, 'index'])
    ->name('sitemap');

// =====================================================================
// SEARCH
// =====================================================================
Route::get('/search', [SearchController::class, 'index'])
    ->middleware('throttle:60,1')
    ->name('search');

// =====================================================================
// NEWSLETTER — Public Routes
// =====================================================================
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->middleware('throttle:5,1')
    ->name('newsletter.subscribe');

Route::get('/newsletter/verify/{token}', [NewsletterController::class, 'verify'])
    ->middleware('throttle:20,1')
    ->name('newsletter.verify');

Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribePage'])
    ->middleware('throttle:20,1')
    ->name('newsletter.unsubscribe.page');

Route::post('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])
    ->middleware('throttle:20,1')
    ->name('newsletter.unsubscribe');

// =====================================================================
// ADMIN — Custom Routes (di luar Twill auto-generated)
// =====================================================================
Route::middleware(['web', 'auth:twill_users'])
    ->prefix('admin')
    ->group(function () {

        // ─────────────────────────────────────────────────────────
        // Sitemap regenerate
        // ─────────────────────────────────────────────────────────
        Route::post('/sitemap/regenerate', function () {
            try {
                Artisan::call('sitemap:generate');

                return response()->json([
                    'success' => true,
                    'message' => 'Sitemap berhasil di-generate ulang!',
                ]);

            } catch (\Throwable $e) {
                Log::error('Sitemap regenerate failed', [
                    'error' => $e->getMessage(),
                    'user_id' => auth('twill_users')->id(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal generate sitemap. Cek log server.',
                ], 500);
            }
        })
            ->middleware('throttle:5,1')
            ->name('admin.sitemap.regenerate');

        // ─────────────────────────────────────────────────────────
        // Newsletter export CSV
        // ─────────────────────────────────────────────────────────
        Route::get('/newsletter-subscribers/export-csv', [NewsletterSubscriberController::class, 'exportCsv'])
            ->name('admin.newsletter-subscribers.export');

    });

// =====================================================================
// CATCH-ALL — HARUS PALING BAWAH
// =====================================================================
Route::get('{slug}', [PageDisplayController::class, 'show'])
    ->name('frontend.page');

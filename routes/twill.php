<?php

use A17\Twill\Facades\TwillRoutes;
use App\Http\Controllers\Twill\AnalyticsController;
use App\Http\Controllers\Twill\BackupController;
use App\Http\Controllers\Twill\CheckpointScanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Twill Admin Routes
|--------------------------------------------------------------------------
|
| Semua route di file ini otomatis mendapat:
|   - URL prefix:  panel-maintenance/
|   - Name prefix: twill.
|
*/

// ═══════════════════════════════════════════════════════════════
// Twill Modules
// ═══════════════════════════════════════════════════════════════
TwillRoutes::module('pages');
TwillRoutes::module('menuLinks');
TwillRoutes::module('agendas');
TwillRoutes::module('posts');
TwillRoutes::module('projects');
TwillRoutes::module('brosurs');
TwillRoutes::module('extracurriculars');
TwillRoutes::module('facilities');
TwillRoutes::module('newsletterSubscribers');
TwillRoutes::module('teamMembers');
TwillRoutes::module('jurusans');
TwillRoutes::module('achievements');
TwillRoutes::module('jobVacancies');

// ═══════════════════════════════════════════════════════════════
// CUSTOM: Backup
// ═══════════════════════════════════════════════════════════════
Route::prefix('backup')->name('backup.')->group(function () {
    Route::get('/', [BackupController::class, 'show'])->name('show');
    Route::post('/create', [BackupController::class, 'create'])->name('create');
    Route::get('/download/{file}', [BackupController::class, 'download'])
        ->where('file', '.*')
        ->name('download');
    Route::delete('/delete/{file}', [BackupController::class, 'delete'])
        ->where('file', '.*')
        ->name('delete');
});

// ═══════════════════════════════════════════════════════════════
// CUSTOM: Analytics
// ═══════════════════════════════════════════════════════════════
Route::prefix('analytics')->name('analytics.')->group(function () {
    Route::get('/', [AnalyticsController::class, 'index'])->name('index');
});

// ═══════════════════════════════════════════════════════════════
// 🆕 CUSTOM: Checkpoint Scan
// ═══════════════════════════════════════════════════════════════
// URL  : /panel-maintenance/checkpoint-scan
// Name : twill.checkpoint-scan.index
// ═══════════════════════════════════════════════════════════════
Route::prefix('checkpoint-scan')->name('checkpoint-scan.')->group(function () {
    Route::get('/', [CheckpointScanController::class, 'index'])->name('index');
    Route::post('/run', [CheckpointScanController::class, 'run'])
        ->middleware('throttle:10,1')
        ->name('run');
});

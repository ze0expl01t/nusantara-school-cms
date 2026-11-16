<?php

use App\Http\Middleware\AddCspHeader;
use App\Http\Middleware\CheckMaintenanceMode;
use App\Http\Middleware\MinifyHtml;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\ProtectDeveloperCredit;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ===== Register middleware untuk web group =====
        $middleware->web(append: [
            \App\Http\Middleware\ProtectDeveloperCredit::class,
            MinifyHtml::class,
            AddCspHeader::class,
            CheckMaintenanceMode::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class SitemapController extends Controller
{
    public function index()
    {
        $path = public_path('sitemap.xml');

        // Kalau file static belum ada, generate dulu (fallback)
        if (! file_exists($path)) {
            Artisan::call('sitemap:generate');
        }

        return response()->file($path, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}

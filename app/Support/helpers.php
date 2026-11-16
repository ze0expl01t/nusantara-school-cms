<?php

use Mews\Purifier\Facades\Purifier;

if (! function_exists('safe_html')) {
    /**
     * Sanitasi HTML dari input user (untuk field description, bio, dsb.)
     */
    function safe_html(?string $value): string
    {
        if (blank($value)) {
            return '';
        }

        return Purifier::clean($value, [
            'HTML.Allowed' => 'p,br,strong,em,u,s,a[href|title|rel|target],'
                                     .'ul,ol,li,blockquote,code,pre,'
                                     .'h1,h2,h3,h4,h5,h6,'
                                     .'table,thead,tbody,tr,th,td,'
                                     .'img[src|alt|width|height]',
            'Attr.AllowedRel' => 'noopener,nofollow',
            'HTML.SafeIframe' => false,
            'AutoFormat.RemoveEmpty' => true,
        ]);
    }
}

if (! function_exists('safe_svg')) {
    /**
     * Untuk icon SVG dari Twill field yang sudah dikurasi.
     * Hanya strip event handler & script.
     */
    function safe_svg(?string $value): string
    {
        if (blank($value)) {
            return '';
        }

        $value = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $value);
        $value = preg_replace('/\son\w+\s*=\s*"[^"]*"/i', '', $value);
        $value = preg_replace("/\son\w+\s*=\s*'[^']*'/i", '', $value);
        $value = preg_replace('/javascript\s*:/i', '', $value);

        return $value;
    }
}

/**
 * ═══════════════════════════════════════════════════════════════
 * FRAGMENT CACHE HELPER
 * ═══════════════════════════════════════════════════════════════
 * Cache fragment HTML dengan TTL + auto-invalidation.
 * Bypass otomatis di mode debug.
 *
 * @param  string  $key  Cache key unik
 * @param  int  $ttl  TTL dalam detik
 * @param  callable  $callback  Closure yang return string HTML
 * @return string
 *                ═══════════════════════════════════════════════════════════════
 */
if (! function_exists('cache_block')) {
    function cache_block(string $key, int $ttl, callable $callback): string
    {
        // Bypass di dev mode
        if (config('app.debug')) {
            return (string) $callback();
        }

        try {
            return (string) Cache::remember($key, $ttl, $callback);
        } catch (Throwable $e) {
            Log::warning('cache_block failed', [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);

            return (string) $callback();
        }
    }
}

/**
 * Hapus cache block untuk page tertentu (semua locale).
 */
if (! function_exists('forget_page_cache')) {
    function forget_page_cache(int $pageId): void
    {
        $locales = array_keys(config('twill.locales', ['en' => []]));

        if (empty($locales)) {
            $locales = ['en'];
        }

        foreach ($locales as $locale) {
            Cache::forget("page:{$pageId}:blocks:{$locale}");
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MinifyHtml
{
    public function handle(Request $request, Closure $next)
    {
        // ═══════════════════════════════════════════════════════════════
        // SKIP minify untuk admin panel & Horizon
        // Alasan: Horizon Vue SPA + inline JS kompleks — minify merusak
        // render Vue. Admin panel Twill juga punya inline JS sensitif.
        // ═══════════════════════════════════════════════════════════════
        if ($request->is('horizon*')
            || $request->is('panel-maintenance*')
            || $request->is('admin*')
            || $request->is('twill*')
        ) {
            return $next($request);
        }

        $response = $next($request);

        $contentType = $response->headers->get('Content-Type', '');
        if (! str_contains($contentType, 'text/html')) {
            return $response;
        }

        $content = $response->getContent();
        if (empty($content) || strlen($content) < 500) {
            return $response;
        }

        // ═══ SKIP minify untuk HTML besar (>500 KB) — rawan bug & lambat ═══
        if (strlen($content) > 500 * 1024) {
            return $response;
        }

        // Preserve sensitive tags (script, style, pre, code, textarea)
        $preserved = [];
        $content = preg_replace_callback(
            '#(<(pre|code|textarea|script|style)\b[^>]*>)(.*?)(</\2>)#is',
            function ($m) use (&$preserved) {
                $key = '___PRESERVE_'.count($preserved).'___';
                $preserved[$key] = $m[0];

                return $key;
            },
            $content
        );

        // Minify
        $content = preg_replace('/<!--(?!\s*\[if)[^>]*?-->/s', '', $content);
        $content = preg_replace('/[ \t]+/', ' ', $content);
        $content = preg_replace('/\r\n|\r|\n/', '', $content);
        $content = preg_replace('/>\s+</', '><', $content);
        $content = trim($content);

        // Restore preserved
        $content = strtr($content, $preserved);

        $response->setContent($content);

        return $response;
    }
}

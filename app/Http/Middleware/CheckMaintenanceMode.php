<?php

namespace App\Http\Middleware;

use A17\Twill\Facades\TwillAppSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        // ============ 1. Skip untuk route admin (dynamic) ============
        $adminPath = trim(config('twill.admin_app_path', 'admin'), '/');

        if (
            $request->is($adminPath) ||
            $request->is($adminPath.'/*') ||
            $request->is('admin') ||
            $request->is('admin/*') ||
            $request->is('twill') ||
            $request->is('twill/*') ||
            $request->is('panel-maintenance') ||
            $request->is('panel-maintenance/*') ||
            $request->is('horizon') ||
            $request->is('horizon/*')
        ) {
            return $next($request);
        }

        // ============ 2. Skip untuk static assets ============
        if (
            $request->is('storage/*') ||
            $request->is('build/*') ||
            $request->is('assets/*') ||
            $request->is('js/*') ||
            $request->is('css/*')
        ) {
            return $next($request);
        }

        // ============ 3. Skip untuk sitemap & robots ============
        if ($request->is('sitemap.xml') || $request->is('robots.txt')) {
            return $next($request);
        }

        // ============ 4. Cek toggle maintenance ============
        $isEnabled = false;
        try {
            $isEnabled = (bool) TwillAppSettings::get('maintenance.maintenance.maintenance_enabled', false);
        } catch (\Throwable $e) {
            $isEnabled = false;
        }

        if (! $isEnabled) {
            return $next($request);
        }

        // ============ 5. Admin bypass (login Twill) ============
        try {
            if (auth('twill_users')->check()) {
                return $next($request);
            }
        } catch (\Throwable $e) {
            // ignore
        }

        // ============ 6. Whitelist IP bypass ============
        $whitelistRaw = '';
        try {
            $raw = TwillAppSettings::get('maintenance.maintenance.maintenance_whitelist_ips', '');
            $whitelistRaw = is_array($raw)
                ? ($raw[app()->getLocale()] ?? reset($raw) ?? '')
                : (string) $raw;
        } catch (\Throwable $e) {
            $whitelistRaw = '';
        }

        if (! empty($whitelistRaw)) {
            $whitelistIps = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $whitelistRaw)));
            $clientIp = $request->ip();

            if (in_array($clientIp, $whitelistIps, true)) {
                return $next($request);
            }
        }

        // ============ 7. Ambil setting untuk tampilan ============
        $defaultTitle = 'Sedang Dalam Perbaikan';
        $defaultMessage = 'Kami sedang melakukan pemeliharaan sistem untuk meningkatkan layanan. Mohon maaf atas ketidaknyamanannya.';

        $getTranslated = function ($key, $default = null) {
            try {
                $val = TwillAppSettings::get("maintenance.maintenance.{$key}");

                if (is_array($val)) {
                    $locale = app()->getLocale();
                    $fallback = config('app.fallback_locale', 'en');

                    $picked = $val[$locale]
                        ?? $val[$fallback]
                        ?? (count($val) ? reset($val) : null);

                    return filled($picked) ? $picked : $default;
                }

                return filled($val) ? $val : $default;
            } catch (\Throwable $e) {
                return $default;
            }
        };

        $title = $getTranslated('maintenance_title', $defaultTitle) ?: $defaultTitle;
        $message = $getTranslated('maintenance_message', $defaultMessage) ?: $defaultMessage;
        $eta = $getTranslated('maintenance_eta');
        $contactEmail = $getTranslated('maintenance_contact_email');

        // Pastikan semua string
        $title = is_string($title) ? $title : (string) $title;
        $message = is_string($message) ? $message : (string) $message;
        $eta = is_string($eta) ? $eta : null;
        $contactEmail = is_string($contactEmail) ? $contactEmail : null;

        // ============ 8. Return maintenance view ============
        return response()
            ->view('errors.maintenance', [
                'title' => $title,
                'message' => $message,
                'eta' => $eta,
                'contactEmail' => $contactEmail,
            ], 503)
            ->header('Retry-After', '3600');
    }
}

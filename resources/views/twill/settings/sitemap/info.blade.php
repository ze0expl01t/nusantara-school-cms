@twillBlockTitle('Info Sitemap')
@twillBlockIcon('text')
@twillBlockGroup('app')

@php
    $path = public_path('sitemap.xml');
    $exists = file_exists($path);
    $urlCount = $exists ? substr_count(file_get_contents($path), '<url>') : 0;
    $fileSize = $exists ? round(filesize($path) / 1024, 2) : 0;
    $lastModified = $exists ? date('d M Y H:i:s', filemtime($path)) : '-';
    $sitemapUrl = url('/sitemap.xml');
    $regenerateUrl = route('admin.sitemap.regenerate');
@endphp

<div style="padding: 0.5rem 0;">
    <p style="color: #6b7280; margin-bottom: 1.5rem;">
        Sitemap XML di-generate otomatis setiap hari via cron. 
        Untuk regenerate manual, klik tombol di bawah atau jalankan 
        <code style="background: #f3f4f6; padding: 0.125rem 0.375rem; border-radius: 0.25rem; font-size: 0.875rem;">php artisan sitemap:generate</code>.
    </p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem;">
            <p style="font-size: 0.75rem; color: #6b7280; text-transform: uppercase; font-weight: 600; margin-bottom: 0.25rem;">Status</p>
            <p style="font-size: 1.125rem; font-weight: 700; color: {{ $exists ? '#10b981' : '#ef4444' }};">
                {{ $exists ? '✓ Aktif' : '✗ Belum Ada' }}
            </p>
        </div>

        <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem;">
            <p style="font-size: 0.75rem; color: #6b7280; text-transform: uppercase; font-weight: 600; margin-bottom: 0.25rem;">Total URL</p>
            <p style="font-size: 1.125rem; font-weight: 700;">{{ $urlCount }}</p>
        </div>

        <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem;">
            <p style="font-size: 0.75rem; color: #6b7280; text-transform: uppercase; font-weight: 600; margin-bottom: 0.25rem;">Ukuran</p>
            <p style="font-size: 1.125rem; font-weight: 700;">{{ $fileSize }} KB</p>
        </div>

        <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem;">
            <p style="font-size: 0.75rem; color: #6b7280; text-transform: uppercase; font-weight: 600; margin-bottom: 0.25rem;">Terakhir Update</p>
            <p style="font-size: 0.875rem; font-weight: 700;">{{ $lastModified }}</p>
        </div>
    </div>

    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
        <a href="{{ $sitemapUrl }}" target="_blank"
           style="display: inline-flex; align-items: center; gap: 0.5rem; background: #3b82f6; color: white; padding: 0.625rem 1.25rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; font-size: 0.875rem;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Buka Sitemap XML
        </a>

        <button type="button"
                onclick="if (typeof window.regenSitemap === 'function') { window.regenSitemap(this, '{{ $regenerateUrl }}'); } else { alert('Script belum ter-load. Refresh halaman.'); } return false;"
                style="display: inline-flex; align-items: center; gap: 0.5rem; background: #10b981; color: white; padding: 0.625rem 1.25rem; border-radius: 0.5rem; border: none; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <span>Regenerate Sekarang</span>
        </button>
    </div>
</div>

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Source Disk
    |--------------------------------------------------------------------------
    |
    | Disk tempat gambar original Twill dibaca. Karena Twill menyimpan
    | upload di storage/app/public/, dan kita sudah punya symlink
    | public/storage → storage/app/public, maka gunakan disk 'public'.
    |
    | Path gambar: uploads/{uuid}/{filename}.jpg
    | URL publik : /storage/uploads/{uuid}/{filename}.jpg
    |
    */

    'source_disk' => env('IMAGE_PROXY_SOURCE_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Cache Disk
    |--------------------------------------------------------------------------
    |
    | Disk untuk menyimpan hasil konversi WebP/AVIF. Gunakan 'local'
    | (storage/app/) agar tidak mengganggu direktori upload publik
    | dan tidak ter-sync ke CDN.
    |
    | Cache di-manage via: php artisan image-proxy:clean
    |
    */

    'cache_disk' => env('IMAGE_PROXY_CACHE_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Image Processing Driver
    |--------------------------------------------------------------------------
    |
    | 'imagick' dipilih karena:
    |   - Server sudah punya ekstensi Imagick (verified)
    |   - Mendukung AVIF (lebih kecil 20-30% dari WebP)
    |   - Mendukung WebP dengan kualitas lebih baik dari GD
    |
    | Fallback ke 'gd' jika Imagick bermasalah (tidak support AVIF).
    |
    */

    'driver' => env('IMAGE_PROXY_DRIVER', 'imagick'),

    /*
    |--------------------------------------------------------------------------
    | Output Format Priority
    |--------------------------------------------------------------------------
    |
    | Format diurutkan dari yang paling efisien. Browser modern
    | akan pilih format pertama yang didukung via Accept header.
    |
    | AVIF: ~50% lebih kecil dari JPEG, support sejak Chrome 85
    | WebP: ~30% lebih kecil dari JPEG, support sejak Chrome 32
    |
    | Fallback otomatis ke format original (JPEG/PNG) jika
    | browser tidak support WebP/AVIF.
    |
    */

    'formats' => ['avif', 'webp'],

    /*
    |--------------------------------------------------------------------------
    | URL Signing (HMAC-SHA256)
    |--------------------------------------------------------------------------
    |
    | Aktifkan untuk mencegah hotlink / abuse. Setiap URL harus
    | ditandatangani, jika tidak akan return 403.
    |
    | Generate key: php artisan key:generate --show
    | Atau: openssl rand -hex 32
    |
    | Untuk sekarang: DISABLED (aman untuk website publik sekolah)
    | Aktifkan jika terjadi penyalahgunaan bandwidth.
    |
    */

    'signing' => [
        'enabled' => env('IMAGE_PROXY_SIGNING_ENABLED', false),
        'key' => env('IMAGE_PROXY_SIGNING_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Remote Disk Caching
    |--------------------------------------------------------------------------
    |
    | Karena kita pakai disk 'public' (local), tidak ada remote disk.
    | Biarkan default — tidak berdampak pada setup kita.
    |
    */

    'cache_remote_originals' => true,

    'remote_disks' => ['s3', 'r2', 'gcs'],

    /*
    |--------------------------------------------------------------------------
    | External Domains (URL Proxying)
    |--------------------------------------------------------------------------
    |
    | Kosong = disabled. Kita HANYA proxy gambar dari disk lokal,
    | tidak dari URL eksternal. Ini mencegah SSRF attack.
    |
    */

    'allowed_domains' => [],

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | Route: /img/{path}
    | Contoh: /img/uploads/{uuid}/{filename}.jpg?w=1200&q=80
    |
    | Middleware 'web' agar bisa pakai session & CSRF (kalau perlu).
    | Untuk performa maksimal, bisa ditambahkan middleware 'cache.headers'.
    |
    */

    'route' => [
        'enabled' => true,
        // 'path'       => 'img',
        'path' => env('IMAGE_PROXY_PATH', 'media'),
        'middleware' => ['web'],
        'name' => 'image-proxy',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed MIME Types
    |--------------------------------------------------------------------------
    |
    | Hanya file gambar yang boleh di-proxy. Ini menyaring PDF
    | (file brosur) agar tidak di-proses image engine.
    |
    */

    'allowed_mime_types' => [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp',
        'image/avif',
        'image/gif',
    ],

    /*
    |--------------------------------------------------------------------------
    | Dimension & Pixel Limits
    |--------------------------------------------------------------------------
    |
    | Batasan ukuran gambar output. Twill upload biasanya <= 2000px.
    | Set max_width/height 3000 sudah cukup untuk hero image 4K.
    |
    | max_pixel_count: guard memory — mencegah image bomb attack.
    | 25 juta pixel = 5000x5000 (cukup besar untuk wallpaper).
    |
    */

    'max_width' => 3000,
    'max_height' => 3000,
    'max_pixel_count' => 25_000_000,

    /*
    |--------------------------------------------------------------------------
    | Quality Settings
    |--------------------------------------------------------------------------
    |
    | default_quality: 80 = sweet spot antara ukuran & visual quality
    | min_quality: 40 = batas bawah (dipakai untuk LQIP placeholder)
    | max_quality: 95 = batas atas (hindari 100, marginal return)
    |
    */

    'min_quality' => 40,
    'max_quality' => 95,
    'default_quality' => 80,

    /*
    |--------------------------------------------------------------------------
    | Effects Limits
    |--------------------------------------------------------------------------
    */

    'max_blur' => 30,

    /*
    |--------------------------------------------------------------------------
    | Watermark Disk
    |--------------------------------------------------------------------------
    |
    | null = fallback ke source_disk. Kita tidak pakai watermark.
    |
    */

    'watermark_disk' => null,

    /*
    |--------------------------------------------------------------------------
    | Border Radius & Padding Limits
    |--------------------------------------------------------------------------
    */

    'max_border_radius' => 1000,
    'max_padding' => 500,

    /*
    |--------------------------------------------------------------------------
    | Max Source File Size
    |--------------------------------------------------------------------------
    |
    | 10 MB = default. Cukup besar untuk gambar Twill.
    | Gambar > 10 MB akan ditolak (return error).
    |
    */

    'max_file_size' => 10 * 1024 * 1024,

    /*
    |--------------------------------------------------------------------------
    | Cache-Control Header
    |--------------------------------------------------------------------------
    |
    | 1 tahun (31536000 detik). Karena file hash UUID bersifat immutable,
    | aman untuk cache jangka panjang di browser + Cloudflare.
    |
    | Untuk invalidation: ganti URL dengan query berbeda atau
    | hapus file di cache disk.
    |
    */

    'cache_max_age' => 31536000,

    /*
    |--------------------------------------------------------------------------
    | Cache Lock
    |--------------------------------------------------------------------------
    |
    | Cegah duplicate processing saat banyak request bersamaan
    | untuk gambar yang sama.
    |
    */

    'cache_lock' => [
        'enabled' => true,
        'timeout' => 30,      // detik
        'wait' => 10,      // detik, tunggu lock release
    ],

    /*
    |--------------------------------------------------------------------------
    | Memory Guard
    |--------------------------------------------------------------------------
    |
    | Batas memory yang boleh dipakai satu proses konversi.
    | Mencegah image bomb (gambar kecil tapi jadi besar saat decoded).
    |
    */

    'memory_limit' => 256 * 1024 * 1024, // 256 MB

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Aktifkan untuk debug performa. Dimatikan di production
    | untuk hindari log bloat.
    |
    | Set true kalau perlu monitor konversi & cache hit.
    |
    */

    'log' => [
        'enabled' => env('IMAGE_PROXY_LOG', false),
        'channel' => env('IMAGE_PROXY_LOG_CHANNEL', 'stack'),
    ],

];

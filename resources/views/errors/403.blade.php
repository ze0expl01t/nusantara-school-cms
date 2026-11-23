<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0f172a">
    @php
        $statusCode = (isset($exception) && method_exists($exception, 'getStatusCode'))
            ? $exception->getStatusCode()
            : 403;
        $messages = [
            403 => ['title' => 'Akses Ditolak', 'desc' => 'Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.'],
            404 => ['title' => 'Halaman Tidak Ditemukan', 'desc' => 'Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman telah dipindahkan atau dihapus.'],
            419 => ['title' => 'Sesi Telah Berakhir', 'desc' => 'Sesi Anda telah berakhir karena tidak ada aktivitas. Silakan muat ulang halaman dan coba lagi.'],
            429 => ['title' => 'Terlalu Banyak Permintaan', 'desc' => 'Anda mengirim terlalu banyak permintaan. Silakan tunggu beberapa saat lagi.'],
            500 => ['title' => 'Kesalahan Server Internal', 'desc' => 'Maaf, terjadi kesalahan pada server kami. Tim kami sedang berusaha memperbaikinya.'],
            503 => ['title' => 'Layanan Tidak Tersedia', 'desc' => 'Kami sedang melakukan pemeliharaan rutin. Silakan kembali beberapa saat lagi.'],
        ];
        $title = $messages[$statusCode]['title'] ?? 'Terjadi Kesalahan';
        $desc = $messages[$statusCode]['desc'] ?? 'Maaf, sepertinya ada yang tidak beres. Silakan coba beberapa saat lagi.';
    @endphp
    <title>{{ $statusCode }} - {{ $title }} | {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #059669;
            --primary-hover: #10b981;
            --accent-teal: #2dd4bf;
            --accent-amber: #fbbf24;
            --bg-gradient-1: #f8fafc;
            --bg-gradient-2: #ecfdf5;
            --text-dark: #0f172a;
            --text-muted: #475569;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, var(--bg-gradient-1) 0%, var(--bg-gradient-2) 100%);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            overflow: hidden;
            position: relative;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(85px);
            z-index: 0;
            opacity: 0.35;
            pointer-events: none;
        }
        .blob-1 {
            width: 320px;
            height: 320px;
            background-color: var(--primary-color);
            top: -70px;
            left: -70px;
            animation: float 7s ease-in-out infinite;
        }
        .blob-2 {
            width: 380px;
            height: 380px;
            background-color: var(--accent-teal);
            bottom: -110px;
            right: -110px;
            animation: float 9s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-18px); }
        }

        .error-card {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            padding: 3rem 2rem;
            max-width: 490px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.1);
            animation: fadeInUp 0.45s ease-out forwards;
            opacity: 0;
            transform: translateY(16px);
            overflow: hidden;
        }

        .top-accent-bar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            display: flex;
        }
        .top-accent-bar span:nth-child(1) { width: 34%; background: #059669; }
        .top-accent-bar span:nth-child(2) { width: 33%; background: #2dd4bf; }
        .top-accent-bar span:nth-child(3) { width: 33%; background: #fbbf24; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-code {
            font-size: 6.5rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #059669, #0d9488);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
            letter-spacing: -2px;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--text-dark);
        }

        .error-desc {
            font-size: 0.975rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
            line-height: 1.65;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background-color: var(--primary-color);
            color: #ffffff;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            box-shadow: 0 10px 20px -5px rgba(5, 150, 105, 0.35);
            border: none;
            cursor: pointer;
        }

        .btn-home:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn-home:active {
            transform: translateY(0);
        }

        .btn-home svg {
            width: 18px;
            height: 18px;
        }

        @media (prefers-reduced-motion: reduce) {
            .blob, .error-card, .btn-home {
                animation: none !important;
                transition: none !important;
                opacity: 1 !important;
                transform: none !important;
            }
        }

        @media (max-width: 480px) {
            .error-code { font-size: 4.75rem; }
            .error-card { padding: 2.25rem 1.5rem; }
        }
    </style>
</head>
<body>

    <div class="blob blob-1" aria-hidden="true"></div>
    <div class="blob blob-2" aria-hidden="true"></div>

    <div class="error-card">
        <div class="top-accent-bar" aria-hidden="true">
            <span></span><span></span><span></span>
        </div>
        <div class="error-code">{{ $statusCode }}</div>
        <h1 class="error-title">{{ $title }}</h1>
        <p class="error-desc">{{ $desc }}</p>

        <a href="{{ url('/') }}" class="btn-home">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            Kembali ke Beranda
        </a>
    </div>

</body>
</html>

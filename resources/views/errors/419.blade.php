<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $exception->getStatusCode() }} - Terjadi Kesalahan</title>
    
    <!-- Font Modern (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        /* =========================================
           GANTI WARNA UTAMA DI SINI
           ========================================= */
        :root {
            /* Ganti kode hex di bawah ini dengan warna utama project kamu */
            --primary-color: #4F46E5; /* Contoh: Indigo (Laravel default) */
            --primary-hover: #4338CA;
            --bg-gradient-1: #f8fafc;
            --bg-gradient-2: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--bg-gradient-1) 0%, var(--bg-gradient-2) 100%);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            overflow: hidden;
        }

        /* Ornamen Background Modern */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.5;
        }
        .blob-1 {
            width: 300px;
            height: 300px;
            background-color: var(--primary-color);
            top: -50px;
            left: -50px;
            animation: float 6s ease-in-out infinite;
        }
        .blob-2 {
            width: 400px;
            height: 400px;
            background-color: #a78bfa; /* Warna aksen sekunder */
            bottom: -100px;
            right: -100px;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        /* Kartu Glassmorphism */
        .error-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 24px;
            padding: 3rem 2rem;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-code {
            font-size: 7rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, var(--primary-color), #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
            letter-spacing: -2px;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--text-dark);
        }

        .error-desc {
            font-size: 1rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background-color: var(--primary-color);
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px 0 rgba(79, 70, 229, 0.39);
            border: none;
            cursor: pointer;
        }

        .btn-home:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px 0 rgba(79, 70, 229, 0.5);
        }

        .btn-home:active {
            transform: translateY(0);
        }

        .btn-home svg {
            width: 18px;
            height: 18px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .error-code { font-size: 5rem; }
            .error-card { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>

    <!-- Ornamen Background -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    @php
        $code = $exception->getStatusCode();
        $messages = [
            404 => ['title' => 'Halaman Tidak Ditemukan', 'desc' => 'Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman telah dipindahkan atau dihapus.'],
            500 => ['title' => 'Kesalahan Server Internal', 'desc' => 'Maaf, terjadi kesalahan pada server kami. Tim kami sedang berusaha memperbaikinya.'],
            403 => ['title' => 'Akses Ditolak', 'desc' => 'Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.'],
            419 => ['title' => 'Sesi Telah Berakhir', 'desc' => 'Sesi Anda telah berakhir karena tidak ada aktivitas. Silakan muat ulang halaman dan coba lagi.'],
            429 => ['title' => 'Terlalu Banyak Permintaan', 'desc' => 'Anda mengirim terlalu banyak permintaan. Silakan tunggu beberapa saat lagi.'],
            503 => ['title' => 'Layanan Tidak Tersedia', 'desc' => 'Kami sedang melakukan pemeliharaan rutin. Silakan kembali beberapa saat lagi.'],
        ];
        $title = $messages[$code]['title'] ?? 'Terjadi Kesalahan';
        $desc = $messages[$code]['desc'] ?? 'Maaf, sepertinya ada yang tidak beres. Silakan coba beberapa saat lagi.';
    @endphp

    <div class="error-card">
        <div class="error-code">{{ $code }}</div>
        <h1 class="error-title">{{ $title }}</h1>
        <p class="error-desc">{{ $desc }}</p>
        
        <a href="{{ url('/') }}" class="btn-home">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            Kembali ke Beranda
        </a>
    </div>

</body>
</html>

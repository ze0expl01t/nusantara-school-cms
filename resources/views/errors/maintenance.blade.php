<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Maintenance' }} | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #020617; color: #e2e8f0; min-height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; }
        .container { max-width: 600px; text-align: center; padding: 20px; }
        h1 { font-size: 32px; font-weight: 800; color: #fff; margin-bottom: 16px; }
        p { color: #94a3b8; line-height: 1.6; }
        .badge { display: inline-block; padding: 8px 16px; border-radius: 50px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7; font-weight: 700; margin-bottom: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="badge">MAINTENANCE MODE</div>
        <h1>{{ $title }}</h1>
        <p>{{ $message }}</p>
        @if($eta) <p style="color: #fbbf24; margin-top: 12px;">{{ $eta }}</p> @endif
    </div>
</body>
</html>

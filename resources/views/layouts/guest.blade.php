{{-- resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Peminjaman Alat')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- PWA Meta Tags --}}
<meta name="theme-color" content="#3b82f6">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="PinjamAlat">
<meta name="msapplication-TileColor" content="#3b82f6">
<meta name="msapplication-TileImage" content="/icons/icon-144x144.png">

{{-- Web Manifest --}}
<link rel="manifest" href="/manifest.json">

{{-- Apple Touch Icons --}}
<link rel="apple-touch-icon" href="/icons/icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/icons/icon-192x192.png">

{{-- Favicon --}}
<link rel="icon" type="image/png" sizes="32x32" href="/icons/icon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/icons/icon-72x72.png">


    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }
        .guest-card {
            width: 100%;
            max-width: 500px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,.15);
            overflow: hidden;
        }
        .guest-card-header {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            padding: 28px 32px 24px;
            text-align: center;
            color: #fff;
        }
        .guest-card-body { padding: 32px; }
        .form-label { font-weight: 600; font-size: .875rem; color: #374151; }
        .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.15); }
        .btn-primary { background: #3b82f6; border-color: #3b82f6; }
        .btn-primary:hover { background: #2563eb; border-color: #2563eb; }
    </style>
    @yield('styles')
</head>
<body>
    <div class="guest-card">
        <div class="guest-card-header">
            @yield('card_header')
        </div>
        <div class="guest-card-body">
            @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>{{ session('error') }}</div>
            </div>
            @endif
            @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('success') }}</div>
            </div>
            @endif
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>{{-- Sebelum </body> di semua layout --}}
<script>
    // Register Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker
                .register('/sw.js')
                .then(reg => {
                    console.log('SW registered:', reg.scope);

                    // Cek update SW
                    reg.addEventListener('updatefound', () => {
                        const newWorker = reg.installing;
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                // Ada versi baru — tampilkan notif update
                                if (confirm('Versi baru tersedia! Muat ulang sekarang?')) {
                                    window.location.reload();
                                }
                            }
                        });
                    });
                })
                .catch(err => console.log('SW failed:', err));
        });
    }
</script>


    @yield('scripts')
</body>
</html>
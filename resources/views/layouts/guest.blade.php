{{-- resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Peminjaman Alat')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <meta name="theme-color" content="#1c1917">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="PinjamAlat">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <link rel="icon" type="image/png" href="/icons/icon-192.png">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['Cormorant Garamond', 'Georgia', 'serif'],
                        sans:  ['Montserrat', 'sans-serif'],
                    },
                    colors: {
                        espresso: '#1c1917', ink: '#1a1714', dim: '#4a4540',
                        label: '#6e665e', rule: '#c8bfb0', ghost: '#a89f94',
                        paper: '#fffdf9', cream: '#f5f0e8', sand: '#e8e0d0',
                    },
                    animation: { 'fade-up': 'fadeUp 0.65s ease both' },
                    keyframes: {
                        fadeUp: {
                            '0%':   { opacity: '0', transform: 'translateY(16px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                    },
                }
            }
        }
    </script>
    @yield('styles')
</head>
<body class="font-sans bg-paper min-h-screen">

    {{-- Tombol login admin pojok kanan atas --}}
    <a href="{{ route('login') }}"
       class="fixed top-5 right-5 z-50 flex items-center gap-2
              bg-paper border border-rule px-4 py-2
              font-sans text-[0.55rem] font-semibold tracking-[0.22em] uppercase text-label
              hover:bg-espresso hover:text-paper hover:border-espresso
              transition-all duration-200">
        <i class="fas fa-shield-halved text-[0.6rem]"></i>
        <span>Login Admin</span>
    </a>

    @yield('content')

    <script>
        if ('serviceWorker' in navigator) navigator.serviceWorker.register('/sw.js').catch(() => {});
    </script>
    @yield('scripts')
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f5f0e8; color: #1a1a1a; }
        .font-serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white border border-gray-300 shadow-sm p-8 text-center relative">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gray-900"></div>

        <div class="mb-6 flex justify-center">
            <div class="w-16 h-16 bg-gray-100 flex items-center justify-center border border-gray-200">
                <i class="fas @yield('icon', 'fa-triangle-exclamation') text-gray-800 text-2xl"></i>
            </div>
        </div>

        <p class="text-[0.65rem] font-bold tracking-[0.3em] text-gray-500 uppercase mb-2">Error @yield('code')</p>
        <h1 class="font-serif text-3xl mb-4 text-gray-900 leading-tight">@yield('message')</h1>
        <div class="w-12 h-px bg-gray-400 mx-auto mb-6"></div>

        <p class="text-[0.8rem] text-gray-600 mb-8 leading-relaxed">
            @hasSection('deskripsi')
                @yield('deskripsi')
            @else
                Maaf, sepertinya terjadi masalah pada sistem. Jika kendala ini terus berlanjut, mohon lapor ke tim Developer.
            @endif
        </p>

        <div class="space-y-3">
            <a href="{{ url('/') }}" class="block w-full bg-gray-900 text-white py-3 text-[0.65rem] font-bold tracking-[0.2em] uppercase hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
            </a>

            <div class="flex gap-3 pt-2">
                {{-- GANTI NOMOR WA DI BAWAH INI --}}
                <a href="https://wa.me/6281394944133?text=Halo%20Dev,%20saya%20menemukan%20*Error%20@yield('code')*%20di%20aplikasi.%20URL:%20{{ urlencode(request()->fullUrl()) }}"
                   target="_blank"
                   class="flex-1 flex justify-center items-center gap-2 border border-green-600 text-green-700 py-2.5 text-[0.6rem] font-bold tracking-wider uppercase hover:bg-green-50 transition-colors">
                    <i class="fab fa-whatsapp text-sm"></i> WhatsApp
                </a>
                {{-- GANTI EMAIL DI BAWAH INI --}}
                <a href="mailto:dev@domainkamu.com?subject=Laporan%20Error%20@yield('code')&body=Halo%20Dev,%0A%0ASaya%20mengalami%20Error%20@yield('code')%20saat%20mengakses%20URL:%20{{ request()->fullUrl() }}"
                   class="flex-1 flex justify-center items-center gap-2 border border-gray-400 text-gray-700 py-2.5 text-[0.6rem] font-bold tracking-wider uppercase hover:bg-gray-50 transition-colors">
                    <i class="fas fa-envelope text-sm"></i> Email
                </a>
            </div>
        </div>
    </div>
</body>
</html>
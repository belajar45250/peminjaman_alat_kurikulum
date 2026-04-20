{{-- resources/views/publik/home.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Peminjaman Alat</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <meta name="theme-color" content="#1c1917">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
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
</head>
<body class="font-sans bg-cream min-h-screen">

    {{-- ══ TOMBOL LOGIN ADMIN ══ --}}
    <a href="{{ route('login') }}"
       class="fixed top-5 right-5 z-50 flex items-center gap-2
              bg-espresso border border-espresso px-4 py-2
              font-sans text-[0.55rem] font-semibold tracking-[0.22em] uppercase text-paper
              hover:bg-ink transition-all duration-200">
        <i class="fas fa-shield-halved text-[0.6rem]"></i>
        <span>Petugas / Admin</span>
    </a>

    {{-- ══ INSTALL PWA BANNER ══ --}}
    <div id="installBanner"
         class="hidden fixed bottom-5 left-1/2 -translate-x-1/2 z-50
                bg-espresso border border-white/10 px-5 py-3
                flex items-center gap-4 shadow-xl">
        <i class="fas fa-download text-paper/60 text-[0.65rem]"></i>
        <span class="font-sans text-[0.6rem] tracking-[0.15em] uppercase text-paper/80">Install Aplikasi</span>
        <button id="btnInstall"
                class="bg-paper text-espresso px-4 py-1.5
                       font-sans text-[0.55rem] font-semibold tracking-[0.15em] uppercase
                       hover:bg-cream transition-colors">
            Install
        </button>
        <button id="btnTutupInstall"
                class="text-paper/30 hover:text-paper transition-colors text-sm ml-1">✕</button>
    </div>

    {{-- ══ HERO ══ --}}
    <div class="min-h-screen flex flex-col items-center justify-center px-5 py-24 text-center">

        {{-- Brand mark --}}
        <div class="mb-10 relative">
            <div class="w-16 h-16 bg-espresso flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-wrench text-paper text-xl"></i>
            </div>
            <div class="absolute -top-2 -right-2 w-5 h-5 border border-rule"></div>
        </div>

        {{-- Heading --}}
        <p class="font-sans text-[0.58rem] font-semibold tracking-[0.38em] uppercase text-label mb-4">
            Sistem Digital
        </p>
        <h1 class="font-serif text-ink text-4xl md:text-5xl font-normal leading-tight mb-4 max-w-sm">
            Peminjaman<br>Alat Sekolah
        </h1>
        <div class="h-px w-12 bg-rule mx-auto mb-6"></div>
        <p class="font-sans text-[0.75rem] text-label leading-relaxed max-w-xs mb-12">
            Pinjam alat dengan mudah dan cepat.<br>
            Cukup scan QR Code yang ada pada alat.
        </p>

        {{-- CTA Utama --}}
        <button id="btnScanUtama"
            class="relative overflow-hidden flex items-center gap-3 bg-espresso text-paper
                   px-8 py-4 mb-3
                   font-sans text-[0.65rem] font-semibold tracking-[0.3em] uppercase
                   hover:bg-ink transition-colors duration-200 active:scale-[0.99]
                   after:content-[''] after:absolute after:inset-0 after:bg-white/[0.06]
                   after:-translate-x-full after:transition-transform after:duration-[350ms]
                   hover:after:translate-x-0">
            <i class="fas fa-qrcode text-[0.85rem]"></i>
            Scan QR untuk Meminjam
        </button>
        <p class="font-sans text-[0.58rem] tracking-[0.12em] text-ghost mb-16">
            <i class="fas fa-info-circle mr-1 text-[0.5rem]"></i>
            Atau arahkan kamera HP langsung ke QR pada alat
        </p>

        {{-- Steps --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-2xl w-full mb-14">
            @php
                $steps = [
                    ['no'=>'1','icon'=>'fa-qrcode',         'title'=>'Scan QR',     'desc'=>'Tap tombol scan atau kamera HP'],
                    ['no'=>'2','icon'=>'fa-pen-to-square',  'title'=>'Isi Form',    'desc'=>'Isi nama, kelas, mata pelajaran'],
                    ['no'=>'3','icon'=>'fa-circle-check',   'title'=>'Selesai',     'desc'=>'Peminjaman tercatat otomatis'],
                    ['no'=>'4','icon'=>'fa-rotate-left',    'title'=>'Kembalikan',  'desc'=>'Serahkan ke petugas untuk scan balik'],
                ];
            @endphp
            @foreach($steps as $s)
            <div class="bg-paper border border-rule p-5 text-left group hover:border-espresso/30 transition-colors">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-5 h-5 bg-espresso text-paper flex items-center justify-center
                                 font-sans text-[0.45rem] font-semibold flex-shrink-0">{{ $s['no'] }}</span>
                    <i class="fas {{ $s['icon'] }} text-ghost text-[0.7rem]"></i>
                </div>
                <p class="font-sans text-[0.65rem] font-semibold tracking-[0.1em] uppercase text-ink mb-1.5">{{ $s['title'] }}</p>
                <p class="font-sans text-[0.6rem] leading-relaxed text-label">{{ $s['desc'] }}</p>
                <div class="mt-4 h-px w-0 bg-espresso/20 group-hover:w-full transition-all duration-500"></div>
            </div>
            @endforeach
        </div>

        {{-- Info Tips --}}
        <div class="bg-paper border border-rule px-6 py-4 max-w-md w-full text-left">
            <p class="font-sans text-[0.65rem] text-dim leading-relaxed">
                <i class="fas fa-lightbulb text-ghost mr-2 text-[0.58rem]"></i>
                <span class="font-semibold text-ink">Tips:</span>
                Kamera HP modern bisa scan QR langsung. Jika tidak bisa, gunakan tombol
                <span class="font-semibold text-ink">Scan QR</span> di atas.
            </p>
        </div>

        <p class="mt-10 font-sans text-[0.5rem] tracking-[0.15em] uppercase text-ghost/50">
            {{ \App\Models\Pengaturan::ambil('nama_sekolah', 'Sistem Peminjaman') }}
        </p>

    </div>

    {{-- ══ SCANNER OVERLAY ══ --}}
    <div id="scannerOverlay"
         class="hidden fixed inset-0 z-[100] bg-black/85 flex items-center justify-center p-4">
        <div class="bg-paper border border-rule w-full max-w-sm">

            {{-- Header --}}
            <div class="border-b border-rule px-5 py-4 flex items-center justify-between">
                <div>
                    <p class="font-sans text-[0.48rem] font-semibold tracking-[0.28em] uppercase text-label">Scanner</p>
                    <h3 class="font-serif text-ink text-lg font-normal mt-0">Scan QR Alat</h3>
                </div>
                <button id="btnTutupScanner"
                        class="w-7 h-7 border border-rule flex items-center justify-center
                               text-ghost hover:bg-espresso hover:text-paper hover:border-espresso transition-all">
                    <i class="fas fa-xmark text-[0.5rem]"></i>
                </button>
            </div>

            {{-- Video area --}}
            <div class="px-5 py-5">
                <div class="relative bg-black overflow-hidden border border-rule mb-4" style="aspect-ratio:1">
                    <video id="qrVideo" playsinline autoplay muted class="w-full h-full object-cover"></video>

                    {{-- Scan frame corners --}}
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="relative w-48 h-48">
                            <span class="absolute top-0 left-0 w-7 h-7 border-t-2 border-l-2 border-paper"></span>
                            <span class="absolute top-0 right-0 w-7 h-7 border-t-2 border-r-2 border-paper"></span>
                            <span class="absolute bottom-0 left-0 w-7 h-7 border-b-2 border-l-2 border-paper"></span>
                            <span class="absolute bottom-0 right-0 w-7 h-7 border-b-2 border-r-2 border-paper"></span>
                            {{-- Scan line --}}
                            <div id="scanLine"
                                 class="absolute left-0 right-0 h-px bg-white/60"
                                 style="top:0;animation:scanMove 2s linear infinite;"></div>
                        </div>
                    </div>
                </div>

                <p id="scanStatus" class="font-sans text-[0.62rem] tracking-wide text-label text-center min-h-5 mb-4"></p>

                <button id="btnTutupScanner2"
                        class="w-full flex items-center justify-center gap-2 border border-rule text-label py-3
                               font-sans text-[0.6rem] font-semibold tracking-[0.22em] uppercase
                               hover:bg-sand transition-colors">
                    <i class="fas fa-xmark text-[0.5rem]"></i> Tutup
                </button>
            </div>

        </div>
    </div>

    <style>
        @keyframes scanMove {
            0%   { top: 5%; }
            50%  { top: 90%; }
            100% { top: 5%; }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script>
        // ── PWA Install ──
        let deferredPrompt = null;
        const installBanner  = document.getElementById('installBanner');
        const btnInstall     = document.getElementById('btnInstall');
        const btnTutupInstall = document.getElementById('btnTutupInstall');

        window.addEventListener('beforeinstallprompt', e => {
            e.preventDefault();
            deferredPrompt = e;
            installBanner.classList.remove('hidden');
            installBanner.classList.add('flex');
        });

        btnInstall?.addEventListener('click', async () => {
            if (!deferredPrompt) return;
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            if (outcome === 'accepted') {
                installBanner.classList.add('hidden');
            }
            deferredPrompt = null;
        });

        btnTutupInstall?.addEventListener('click', () => {
            installBanner.classList.add('hidden');
        });

        window.addEventListener('appinstalled', () => {
            installBanner.classList.add('hidden');
        });

        // ── QR Scanner ──
        let stream       = null;
        let scanInterval = null;
        let sudahScan    = false;

        const overlay    = document.getElementById('scannerOverlay');
        const video      = document.getElementById('qrVideo');
        const statusEl   = document.getElementById('scanStatus');

        function bukaScanner() {
            sudahScan = false;
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            statusEl.textContent = '';

            navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
            }).then(s => {
                stream = s;
                video.srcObject = s;
                video.play();
                mulaiScan();
            }).catch(() => {
                statusEl.textContent = '⚠ Kamera tidak dapat diakses. Pastikan izin kamera sudah diberikan.';
            });
        }

        function mulaiScan() {
            const canvas  = document.createElement('canvas');
            const ctx     = canvas.getContext('2d');

            scanInterval = setInterval(() => {
                if (sudahScan || video.readyState !== video.HAVE_ENOUGH_DATA) return;

                canvas.width  = video.videoWidth;
                canvas.height = video.videoHeight;
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const result    = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: 'dontInvert',
                });

                if (result) {
                    sudahScan = true;
                    prosesHasil(result.data);
                }
            }, 200);
        }

        function prosesHasil(teks) {
            statusEl.textContent = 'QR berhasil dibaca...';

            const match = teks.match(/\/pinjam\/qr\/([a-f0-9]{64})/);
            const hash  = match ? match[1] : null;

            if (hash) {
                statusEl.textContent = 'Mengarahkan ke form...';
                setTimeout(() => {
                    hentikanKamera();
                    window.location.href = `/pinjam/qr/${hash}`;
                }, 500);
            } else {
                statusEl.textContent = '✕ QR tidak dikenal. Pastikan scan QR yang ada pada alat.';
                setTimeout(() => {
                    sudahScan = false;
                    statusEl.textContent = '';
                }, 2500);
            }
        }

        function tutupScanner() {
            hentikanKamera();
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
            statusEl.textContent = '';
        }

        function hentikanKamera() {
            if (scanInterval) { clearInterval(scanInterval); scanInterval = null; }
            if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
            video.srcObject = null;
        }

        document.getElementById('btnScanUtama').addEventListener('click', bukaScanner);
        document.getElementById('btnTutupScanner').addEventListener('click', tutupScanner);
        document.getElementById('btnTutupScanner2').addEventListener('click', tutupScanner);

        overlay.addEventListener('click', e => {
            if (e.target === overlay) tutupScanner();
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') tutupScanner();
        });

        // Handle shortcut ?action=scan dari manifest PWA
        if (new URLSearchParams(window.location.search).get('action') === 'scan') {
            window.addEventListener('load', () => setTimeout(bukaScanner, 500));
        }

        if ('serviceWorker' in navigator) navigator.serviceWorker.register('/sw.js').catch(() => {});
    </script>
</body>
</html>
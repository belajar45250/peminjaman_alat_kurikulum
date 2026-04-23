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

    {{-- ══ TOMBOL LOGIN ADMIN (FIXED TOP RIGHT) ══ --}}
    <a href="{{ route('login') }}"
       class="fixed top-5 right-5 z-50 flex items-center gap-2
              bg-espresso border border-espresso px-4 py-2
              font-sans text-[0.55rem] font-semibold tracking-[0.22em] uppercase text-paper
              hover:bg-ink transition-all duration-200">
        <i class="fas fa-shield-halved text-[0.6rem]"></i>
        <span>Petugas / Admin</span>
    </a>

    {{-- ══ INSTALL PWA BANNER (FIXED BOTTOM CENTER) ══ --}}
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

    {{-- ══ HERO SECTION ══ --}}
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

        {{-- CTA Utama (Scan QR) --}}
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

        {{-- Help text --}}
        <p class="font-sans text-[0.58rem] tracking-[0.12em] text-ghost mb-6">
            <i class="fas fa-info-circle mr-1 text-[0.5rem]"></i>
            Atau arahkan kamera HP langsung ke QR pada alat
        </p>

        {{-- Tombol Katalog --}}
        <button id="btnKatalog"
            class="flex items-center justify-center gap-2 border border-rule text-label px-6 py-3 mb-12
                   font-sans text-[0.6rem] font-semibold tracking-[0.25em] uppercase
                   hover:bg-espresso hover:text-paper hover:border-espresso transition-all duration-200">
            <i class="fas fa-list text-[0.55rem]"></i>
            Lihat Ketersediaan Alat
        </button>

        {{-- Steps (4 columns) --}}
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

    </div> {{-- ══ END HERO ══ --}}

    {{-- ════════════════════════════════════════════ --}}
    {{-- ══ OVERLAYS & MODALS (OUTSIDE HERO) ══ --}}
    {{-- ════════════════════════════════════════════ --}}

    {{-- ══ KATALOG OVERLAY (z-[90]) ══ --}}
    <div id="katalogOverlay"
         class="hidden fixed inset-0 z-[90] bg-black/70 flex items-end md:items-center justify-center p-4">
        <div class="bg-paper border border-rule w-full max-w-2xl max-h-[85vh] flex flex-col md:rounded-none">

            {{-- Header --}}
            <div class="border-b border-rule px-6 py-4 flex items-center justify-between flex-shrink-0">
                <div>
                    <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Inventaris</p>
                    <h2 class="font-serif text-ink text-xl font-normal mt-0">Ketersediaan Alat</h2>
                </div>
                <button id="btnTutupKatalog"
                        class="w-8 h-8 border border-rule flex items-center justify-center
                               text-ghost hover:bg-espresso hover:text-paper transition-all">
                    <i class="fas fa-xmark text-[0.55rem]"></i>
                </button>
            </div>

            {{-- Search --}}
            <div class="px-6 py-3 border-b border-rule/60 flex-shrink-0">
                <input type="text" id="searchKatalog" placeholder="Cari nama alat..."
                       class="w-full border-b border-rule bg-transparent pb-2 pt-1
                              font-sans text-[0.85rem] text-ink outline-none placeholder-ghost focus:border-ink">
            </div>

            {{-- Legend --}}
            <div class="px-6 py-2.5 border-b border-rule/40 flex items-center gap-4 flex-shrink-0 bg-cream/30">
                @foreach([
                    ['color'=>'bg-emerald-500','label'=>'Tersedia'],
                    ['color'=>'bg-amber-500',  'label'=>'Dipinjam'],
                    ['color'=>'bg-gray-400',   'label'=>'Tidak Tersedia'],
                ] as $leg)
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full {{ $leg['color'] }}"></span>
                    <span class="font-sans text-[0.55rem] tracking-[0.1em] uppercase text-ghost">{{ $leg['label'] }}</span>
                </div>
                @endforeach
            </div>

            {{-- List --}}
            <div id="katalogList" class="flex-1 overflow-y-auto px-6 py-4">
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-rule text-2xl"></i>
                </div>
            </div>

            {{-- Footer --}}
            <div class="border-t border-rule px-6 py-3 flex-shrink-0">
                <p class="font-sans text-[0.55rem] tracking-[0.1em] text-ghost text-center">
                    Data diperbarui secara otomatis
                </p>
            </div>
        </div>
    </div>

    {{-- ══ KERANJANG BUTTON (FIXED BOTTOM RIGHT, z-[40]) ══ --}}
    <div id="cartBtn"
         class="hidden fixed bottom-6 right-6 z-40 cursor-pointer"
         onclick="bukaKeranjang()">
        <div class="relative bg-espresso text-paper px-4 py-3 flex items-center gap-3 shadow-xl border border-espresso/80">
            <i class="fas fa-basket-shopping text-[0.9rem]"></i>
            <div>
                <p class="font-sans text-[0.65rem] font-semibold tracking-[0.15em] uppercase">Keranjang Pinjam</p>
                <p class="font-sans text-[0.55rem] text-paper/60"><span id="cartCount">0</span> alat dipilih</p>
            </div>
            <span id="cartBadge"
                  class="absolute -top-2 -right-2 w-5 h-5 bg-red-600 flex items-center justify-center
                         font-sans text-[0.5rem] font-bold text-paper rounded-full">0</span>
        </div>
    </div>

    {{-- ══ KERANJANG MODAL OVERLAY (z-[95]) ══ --}}
    <div id="keranjangOverlay"
         class="hidden fixed inset-0 z-[95] bg-black/70 flex items-end md:items-center justify-center p-4">
        <div class="bg-paper border border-rule w-full max-w-lg max-h-[90vh] flex flex-col md:rounded-none">
            <div class="border-b border-rule px-6 py-4 flex items-center justify-between flex-shrink-0">
                <div>
                    <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Peminjaman</p>
                    <h2 class="font-serif text-ink text-xl font-normal mt-0">Keranjang Pinjam</h2>
                </div>
                <button onclick="tutupKeranjang()"
                        class="w-8 h-8 border border-rule flex items-center justify-center
                               text-ghost hover:bg-espresso hover:text-paper transition-all">
                    <i class="fas fa-xmark text-[0.55rem]"></i>
                </button>
            </div>

            <div id="keranjangList" class="flex-1 overflow-y-auto divide-y divide-rule/40 px-6 py-4">
                <p class="text-center font-sans text-[0.65rem] tracking-[0.15em] uppercase text-ghost py-8">
                    Keranjang kosong. Scan QR alat untuk menambahkan.
                </p>
            </div>

            <div class="border-t border-rule px-6 py-4 flex-shrink-0 space-y-3">
                <button id="btnScanTambah"
                        class="w-full flex items-center justify-center gap-2 border border-rule text-label py-3
                               font-sans text-[0.6rem] font-semibold tracking-[0.22em] uppercase
                               hover:bg-sand transition-colors">
                    <i class="fas fa-plus text-[0.5rem]"></i> Scan Alat Lagi
                </button>
                <button id="btnLanjutForm"
                        class="w-full flex items-center justify-center gap-2 bg-espresso text-paper py-3.5
                               font-sans text-[0.62rem] font-semibold tracking-[0.25em] uppercase
                               hover:bg-ink transition-colors disabled:opacity-40"
                        disabled>
                    <i class="fas fa-arrow-right text-[0.5rem]"></i> Lanjut Isi Form
                </button>
            </div>
        </div>
    </div>

    {{-- ══ SCANNER OVERLAY (z-[100]) ══ --}}
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

    {{-- ══ STYLES ══ --}}
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


        

       // ════════════════════════════════════════════════════════════════
        // ══ KATALOG + KERANJANG (INTEGRATED) ══
        // ════════════════════════════════════════════════════════════════

        // ── STATE ──
        let keranjang = [];
        let katalogData = {};

        // ── KATALOG ELEMENTS ──
        const katalogOverlay = document.getElementById('katalogOverlay');
        const katalogList    = document.getElementById('katalogList');
        const searchInput    = document.getElementById('searchKatalog');

        // ── KERANJANG ELEMENTS ──
        const keranjangOverlay = document.getElementById('keranjangOverlay');
        const keranjangList    = document.getElementById('keranjangList');
        const cartBtn          = document.getElementById('cartBtn');
        const cartBadge        = document.getElementById('cartBadge');
        const cartCount        = document.getElementById('cartCount');
        const btnLanjutForm    = document.getElementById('btnLanjutForm');

        // ════════════════════════════════════════════════════════════════
        // ── KATALOG FUNCTIONS ──
        // ════════════════════════════════════��═══════════════════════════

        document.getElementById('btnKatalog').addEventListener('click', () => {
            katalogOverlay.classList.remove('hidden');
            katalogOverlay.classList.add('flex');
            muatKatalog();
        });

        document.getElementById('btnTutupKatalog').addEventListener('click', tutupKatalog);

        katalogOverlay.addEventListener('click', e => {
            if (e.target === katalogOverlay) tutupKatalog();
        });

        function tutupKatalog() {
            katalogOverlay.classList.add('hidden');
            katalogOverlay.classList.remove('flex');
        }

        async function muatKatalog() {
            try {
                const res  = await fetch('{{ route("publik.katalog") }}');
                const data = await res.json();
                katalogData = data.alat;
                renderKatalog(katalogData);
            } catch(e) {
                console.error('Katalog error:', e);
                katalogList.innerHTML = `<p class="text-center font-sans text-[0.65rem] text-ghost py-8">Gagal memuat data.</p>`;
            }
        }

        function renderKatalog(data) {
            const keyword = searchInput.value.toLowerCase();
            let html = '';

            Object.entries(data).forEach(([kategori, items]) => {
                const filtered = items.filter(a => a.nama.toLowerCase().includes(keyword));
                if (filtered.length === 0) return;

                html += `
                    <div class="mb-5">
                        <p class="font-sans text-[0.48rem] font-semibold tracking-[0.25em] uppercase text-ghost mb-3 flex items-center gap-2">
                            <span>${kategori || 'Umum'}</span>
                            <span class="flex-1 h-px bg-rule/40"></span>
                            <span>${filtered.length} item</span>
                        </p>
                        <div class="space-y-1.5">
                            ${filtered.map(a => {
                                const statusClass = a.status === 'tersedia'
                                    ? 'bg-emerald-500'
                                    : a.kondisi === 'tidak_tersedia' ? 'bg-gray-400' : 'bg-amber-500';
                                const statusLabel = a.status === 'tersedia' ? 'Tersedia'
                                    : a.kondisi === 'tidak_tersedia' ? 'Tidak Tersedia' : 'Dipinjam';
                                const nomorLabel  = a.nomor ? `<span class="font-sans text-[0.6rem] text-ghost ml-1">#${a.nomor}</span>` : '';

                                return `
                                <div class="flex items-center justify-between px-3 py-2.5 border border-rule/60
                                            hover:bg-cream/50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <span class="w-2 h-2 rounded-full ${statusClass} flex-shrink-0"></span>
                                        <div>
                                            <span class="font-sans text-[0.78rem] text-ink">${a.nama}</span>
                                            ${nomorLabel}
                                            <span class="font-mono text-[0.6rem] text-ghost ml-2">${a.kode}</span>
                                        </div>
                                    </div>
                                    <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border
                                        ${a.status === 'tersedia'
                                            ? 'bg-emerald-50 text-emerald-800 border-emerald-200'
                                            : 'bg-amber-50 text-amber-800 border-amber-200'}">
                                        ${statusLabel}
                                    </span>
                                </div>`;
                            }).join('')}
                        </div>
                    </div>`;
            });

            katalogList.innerHTML = html || `
                <p class="text-center font-sans text-[0.65rem] tracking-[0.15em] uppercase text-ghost py-8">
                    Tidak ada alat ditemukan
                </p>`;
        }

        searchInput.addEventListener('input', () => renderKatalog(katalogData));

        // ════════════════════════════════════════════════════════════════
        // ── KERANJANG FUNCTIONS ──
        // ════════════════════════════════════════════════════════════════

        function updateKeranjangUI() {
            const count = keranjang.length;

            cartCount.textContent = count;
            cartBadge.textContent = count;

            if (count > 0) {
                cartBtn.classList.remove('hidden');
                btnLanjutForm.disabled = false;
            } else {
                cartBtn.classList.add('hidden');
                btnLanjutForm.disabled = true;
            }

            if (count === 0) {
                keranjangList.innerHTML = `
                    <p class="text-center font-sans text-[0.65rem] tracking-[0.15em] uppercase text-ghost py-8">
                        Keranjang kosong. Scan QR alat untuk menambahkan.
                    </p>`;
                return;
            }

            keranjangList.innerHTML = keranjang.map((item, i) => `
                <div class="flex items-center justify-between py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 bg-cream border border-rule flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-wrench text-ghost text-[0.5rem]"></i>
                        </div>
                        <div>
                            <p class="font-sans text-[0.78rem] text-ink font-medium">${item.nama}</p>
                            <p class="font-mono text-[0.62rem] text-ghost">${item.kode}${item.nomor ? ' · #'+item.nomor : ''}</p>
                        </div>
                    </div>
                    <button onclick="hapusDariKeranjang(${i})"
                            class="w-7 h-7 border border-rule flex items-center justify-center
                                   text-ghost hover:bg-red-900 hover:text-paper hover:border-red-900 transition-all">
                        <i class="fas fa-trash text-[0.45rem]"></i>
                    </button>
                </div>`).join('');
        }

        function hapusDariKeranjang(index) {
            keranjang.splice(index, 1);
            updateKeranjangUI();
        }

        function bukaKeranjang() {
            keranjangOverlay.classList.remove('hidden');
            keranjangOverlay.classList.add('flex');
        }

        function tutupKeranjang() {
            keranjangOverlay.classList.add('hidden');
            keranjangOverlay.classList.remove('flex');
        }

        document.getElementById('btnScanTambah').addEventListener('click', () => {
            tutupKeranjang();
            bukaScanner();
        });

        document.getElementById('btnLanjutForm').addEventListener('click', () => {
            if (keranjang.length === 0) return;
            const hashes = keranjang.map(i => i.hash).join(',');
            window.location.href = `{{ route('publik.form-multi', ['hashes' => ':hashes']) }}`.replace(':hashes', hashes);
        });

        keranjangOverlay.addEventListener('click', e => {
            if (e.target === keranjangOverlay) tutupKeranjang();
        });

        // Expose untuk onclick di HTML
        window.bukaKeranjang = bukaKeranjang;
        window.tutupKeranjang = tutupKeranjang;
        window.hapusDariKeranjang = hapusDariKeranjang;

        // ════════════════════════════════════════════════════════════════
        // ── SCANNER + KERANJANG INTEGRATION ──
        // ════════════════════════════════════════════════════════════════

        // Override prosesHasil untuk tambah ke keranjang
        function prosesHasil(teks) {
            statusEl.textContent = 'QR berhasil dibaca...';
            const match = teks.match(/\/pinjam\/qr\/([a-f0-9]{64})/);
            const hash  = match ? match[1] : null;

            if (!hash) {
                statusEl.textContent = '✕ QR tidak dikenal.';
                setTimeout(() => { sudahScan = false; statusEl.textContent = ''; }, 2000);
                return;
            }

            // Cek duplikat
            if (keranjang.find(i => i.hash === hash)) {
                statusEl.textContent = '⚠ Alat ini sudah ada di keranjang.';
                setTimeout(() => { 
                    sudahScan = false; 
                    statusEl.textContent = ''; 
                    hentikanKamera(); 
                    tutupScanner(); 
                }, 1500);
                return;
            }

            // Validasi ke server
            const validasiUrl = `{{ route('publik.validasi-qr', ['hash' => ':hash']) }}`.replace(':hash', hash);
            
            fetch(validasiUrl)
                .then(r => r.json())
                .then(data => {
                    if (!data.valid) {
                        statusEl.textContent = `✕ ${data.pesan}`;
                        setTimeout(() => { sudahScan = false; statusEl.textContent = ''; }, 2000);
                        return;
                    }

                    keranjang.push({
                        hash  : hash,
                        nama  : data.nama_alat,
                        kode  : data.kode_alat,
                        nomor : data.nomor_urut ?? null,
                    });

                    updateKeranjangUI();
                    statusEl.innerHTML = `<span class="text-emerald-700">✓ ${data.nama_alat} ditambahkan!</span>`;

                    setTimeout(() => {
                        hentikanKamera();
                        tutupScanner();
                        bukaKeranjang();
                    }, 800);
                })
                .catch(e => {
                    console.error('Validasi error:', e);
                    statusEl.textContent = 'Gagal validasi. Coba lagi.';
                    setTimeout(() => { sudahScan = false; statusEl.textContent = ''; }, 2000);
                });
        }

        // Init
        updateKeranjangUI();
    </script>
</body>
</html>
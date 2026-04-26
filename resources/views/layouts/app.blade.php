{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'Dashboard') — Sistem Peminjaman</title>

    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <meta name="theme-color" content="#1c1917">
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
                        espresso: '#1c1917',
                        ink:      '#1a1714',
                        dim:      '#4a4540',
                        label:    '#6e665e',
                        rule:     '#c8bfb0',
                        ghost:    '#a89f94',
                        paper:    '#fffdf9',
                        cream:    '#f5f0e8',
                        sand:     '#e8e0d0',
                    },
                }
            }
        }
    </script>
    @yield('styles')
</head>
<body class="bg-cream font-sans min-h-screen">

    {{-- ══ HEADER ══ --}}
    <header class="fixed top-0 left-0 right-0 z-40 bg-espresso border-b border-white/[0.07]">
        <div class="h-[1px] w-full" style="background:linear-gradient(90deg,transparent,rgba(200,191,176,.25),transparent)"></div>
        <div class="px-4 md:px-6 flex items-stretch h-[60px]">

            {{-- Mobile toggle --}}
            <button onclick="toggleSidebar()" class="md:hidden flex items-center justify-center w-10 flex-shrink-0">
                <i class="fas fa-bars text-paper/70 text-sm"></i>
            </button>

            {{-- Ganti bagian brand di topbar --}}
            <div class="flex items-center gap-3 flex-shrink-0">
                @php $logo = \App\Models\Pengaturan::ambil('logo_sekolah'); @endphp

                {{-- Logo atau ikon default --}}
                <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 overflow-hidden">
                    @if($logo)
                        <img src="{{ asset('storage/' . $logo) }}"
                            alt="Logo"
                            class="w-full h-full object-contain">
                    @else
                        <div class="w-7 h-7 border border-white/20 flex items-center justify-center">
                            <i class="fas fa-wrench text-paper text-[0.5rem]"></i>
                        </div>
                    @endif
                </div>

                <div class="hidden sm:block">
                    <h1 class="font-serif text-paper text-[0.9rem] font-normal leading-none tracking-[0.15em] uppercase">
                        Sistem Peminjaman
                    </h1>
                    <p class="font-sans text-[0.42rem] tracking-[0.38em] uppercase text-paper/35 mt-[3px]">
                        Platform Manajemen
                    </p>
                </div>
            </div>

            <div class="flex-1"></div>

            {{-- PWA Install --}}
            <button id="btnInstall" style="display:none;"
                class="hidden md:flex items-center gap-2 px-4 border-l border-white/[0.08]
                       font-sans text-[0.52rem] tracking-[0.2em] uppercase text-paper/50
                       hover:text-paper/80 transition-colors">
                <i class="fas fa-download text-[0.55rem]"></i>
                <span>Install App</span>
            </button>

            {{-- ══ NOTIFIKASI BELL (TAMBAH DISINI) ══ --}}
            <div class="relative flex items-center px-3 border-l border-white/[0.08]" id="notifWrapper">
                <button id="btnNotif"
                        class="relative w-8 h-8 flex items-center justify-center
                            text-paper/50 hover:text-paper transition-colors">
                    <i class="fas fa-bell text-[0.75rem]"></i>
                    <span id="notifBadge"
                        class="hidden absolute -top-0.5 -right-0.5 min-w-[16px] h-4 px-1
                                bg-red-600 text-paper flex items-center justify-center
                                font-sans text-[0.45rem] font-bold leading-none">
                        0
                    </span>
                </button>

                {{-- Dropdown Notifikasi --}}
                <div id="notifDropdown"
                    class="hidden absolute right-0 top-[48px] w-80 bg-paper border border-rule shadow-2xl z-50">

                    {{-- Header --}}
                    <div class="flex items-center justify-between px-5 py-3.5 border-b border-rule">
                        <div>
                            <p class="font-sans text-[0.48rem] font-semibold tracking-[0.22em] uppercase text-ghost">Sistem</p>
                            <h3 class="font-serif text-ink text-base font-normal mt-0">Notifikasi</h3>
                        </div>
                        <button id="btnBacaSemua"
                                class="font-sans text-[0.52rem] tracking-[0.12em] uppercase text-ghost
                                    hover:text-ink transition-colors border border-rule px-2 py-1
                                    hover:bg-sand">
                            Baca Semua
                        </button>
                    </div>

                    {{-- List --}}
                    <div id="notifList" class="max-h-72 overflow-y-auto divide-y divide-rule/40">
                        <div class="py-8 text-center">
                            <i class="fas fa-bell-slash text-rule text-2xl block mb-2"></i>
                            <p class="font-sans text-[0.6rem] tracking-[0.15em] uppercase text-ghost">
                                Tidak ada notifikasi
                            </p>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="border-t border-rule px-5 py-3 text-center">
                        <a href="{{ route('admin.peminjaman.index', ['status' => 'dipinjam']) }}"
                        class="font-sans text-[0.52rem] tracking-[0.12em] uppercase text-label
                                hover:text-ink transition-colors">
                            Lihat Semua Peminjaman Aktif →
                        </a>
                    </div>
                </div>
            </div>

            {{-- User --}}
            <div class="flex items-center gap-3 px-4 border-l border-white/[0.08]">
                <div class="relative">
                    <div class="w-7 h-7 bg-white/[0.08] border border-white/15 flex items-center justify-content-center flex items-center justify-center">
                        <i class="fas fa-user text-paper/70 text-[0.5rem]"></i>
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-[6px] h-[6px] bg-emerald-400 border border-espresso rounded-full"></span>
                </div>
                <div class="hidden sm:block">
                    <p class="font-sans text-[0.7rem] font-semibold text-paper leading-none">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="font-sans text-[0.44rem] tracking-[0.22em] uppercase text-paper/40 mt-1">Admin</p>
                </div>
            </div>

            {{-- Logout --}}
            <div class="flex items-center pl-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-2 border border-white/20 px-3 py-2
                               font-sans text-[0.52rem] font-semibold tracking-[0.2em] uppercase text-paper/60
                               hover:bg-white/[0.08] hover:text-paper hover:border-white/35
                               transition-all duration-150 group">
                        <i class="fas fa-right-from-bracket text-[0.55rem] group-hover:translate-x-0.5 transition-transform"></i>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- Mobile overlay --}}
    <div id="sidebarBackdrop" class="hidden fixed inset-0 bg-black/50 z-20 md:hidden" onclick="toggleSidebar()"></div>

    <div class="flex pt-[60px] min-h-[calc(100vh-60px)]">

        {{-- ══ SIDEBAR ══ --}}
        <aside id="sidebar"
            class="fixed left-0 top-[60px] bottom-0 z-30 w-56 bg-espresso flex flex-col
                   transition-transform duration-300 -translate-x-full md:translate-x-0">

            <div class="absolute right-0 top-0 bottom-0 w-px bg-white/[0.06]"></div>

            <nav class="flex-1 px-3 py-5 overflow-y-auto space-y-5">

                {{-- Utama --}}
                <div>
                    <p class="px-3 mb-2 font-sans text-[0.42rem] font-semibold tracking-[0.35em] uppercase text-paper/25">Utama</p>
                    <a href="{{ route('admin.dashboard') }}"
                       class="relative flex items-center gap-3 px-3 py-2.5 transition-all duration-150
                              {{ request()->routeIs('admin.dashboard') ? 'bg-white/[0.10] text-paper' : 'text-paper/45 hover:bg-white/[0.05] hover:text-paper/75' }}">
                        @if(request()->routeIs('admin.dashboard'))
                            <span class="absolute left-0 top-1 bottom-1 w-[2px] bg-rule rounded-r-full"></span>
                        @endif
                        <i class="fas fa-gauge-high text-[0.6rem] w-3.5 text-center {{ request()->routeIs('admin.dashboard') ? 'text-paper/80' : 'text-paper/30' }}"></i>
                        <span class="font-sans text-[0.68rem] font-medium tracking-wide">Dashboard</span>
                    </a>
                </div>

                {{-- Inventaris --}}
                <div>
                    <p class="px-3 mb-2 font-sans text-[0.42rem] font-semibold tracking-[0.35em] uppercase text-paper/25">Inventaris</p>
                    @foreach([
                        ['route' => 'admin.alat.index', 'label' => 'Alat', 'icon' => 'fa-wrench', 'match' => 'admin.alat.*'],
                    ] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="relative flex items-center gap-3 px-3 py-2.5 transition-all duration-150
                              {{ request()->routeIs($item['match']) ? 'bg-white/[0.10] text-paper' : 'text-paper/45 hover:bg-white/[0.05] hover:text-paper/75' }}">
                        @if(request()->routeIs($item['match']))
                            <span class="absolute left-0 top-1 bottom-1 w-[2px] bg-rule rounded-r-full"></span>
                        @endif
                        <i class="fas {{ $item['icon'] }} text-[0.6rem] w-3.5 text-center {{ request()->routeIs($item['match']) ? 'text-paper/80' : 'text-paper/30' }}"></i>
                        <span class="font-sans text-[0.68rem] font-medium tracking-wide">{{ $item['label'] }}</span>
                    </a>
                    @endforeach
                </div>

                {{-- Transaksi --}}
                <div>
                    <p class="px-3 mb-2 font-sans text-[0.42rem] font-semibold tracking-[0.35em] uppercase text-paper/25">Transaksi</p>
                    @foreach([
                        ['route' => 'admin.pengembalian.index', 'label' => 'Pengembalian', 'icon' => 'fa-rotate-left', 'match' => 'admin.pengembalian.*'],
                        ['route' => 'admin.peminjaman.index',   'label' => 'Riwayat Pinjam','icon' => 'fa-clipboard-list','match' => 'admin.peminjaman.*'],
                    ] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="relative flex items-center gap-3 px-3 py-2.5 transition-all duration-150
                              {{ request()->routeIs($item['match']) ? 'bg-white/[0.10] text-paper' : 'text-paper/45 hover:bg-white/[0.05] hover:text-paper/75' }}">
                        @if(request()->routeIs($item['match']))
                            <span class="absolute left-0 top-1 bottom-1 w-[2px] bg-rule rounded-r-full"></span>
                        @endif
                        <i class="fas {{ $item['icon'] }} text-[0.6rem] w-3.5 text-center {{ request()->routeIs($item['match']) ? 'text-paper/80' : 'text-paper/30' }}"></i>
                        <span class="font-sans text-[0.68rem] font-medium tracking-wide">{{ $item['label'] }}</span>
                    </a>
                    @endforeach
                </div>

                {{-- Administrasi --}}
                <div>
                    <p class="px-3 mb-2 font-sans text-[0.42rem] font-semibold tracking-[0.35em] uppercase text-paper/25">Administrasi</p>
                    @foreach([
                        ['route' => 'admin.laporan.index',           'label' => 'Laporan',          'icon' => 'fa-chart-line',   'match' => 'admin.laporan.*'],
                        ['route' => 'admin.history-kerusakan.index', 'label' => 'History Kerusakan','icon' => 'fa-triangle-exclamation','match' => 'admin.history-kerusakan.*'],
                        ['route' => 'admin.pengaturan.index',        'label' => 'Pengaturan',       'icon' => 'fa-gear',         'match' => 'admin.pengaturan.*'],
                    ] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="relative flex items-center gap-3 px-3 py-2.5 transition-all duration-150
                              {{ request()->routeIs($item['match']) ? 'bg-white/[0.10] text-paper' : 'text-paper/45 hover:bg-white/[0.05] hover:text-paper/75' }}">
                        @if(request()->routeIs($item['match']))
                            <span class="absolute left-0 top-1 bottom-1 w-[2px] bg-rule rounded-r-full"></span>
                        @endif
                        <i class="fas {{ $item['icon'] }} text-[0.6rem] w-3.5 text-center {{ request()->routeIs($item['match']) ? 'text-paper/80' : 'text-paper/30' }}"></i>
                        <span class="font-sans text-[0.68rem] font-medium tracking-wide">{{ $item['label'] }}</span>
                        @if($item['route'] === 'admin.history-kerusakan.index')
                            @php $menunggu = \App\Models\HistoryKerusakan::where('status_tindak_lanjut','menunggu')->count(); @endphp
                            @if($menunggu > 0)
                                <span class="ml-auto bg-red-800/60 text-paper/80 text-[0.45rem] tracking-wider px-1.5 py-0.5">
                                    {{ $menunggu }}
                                </span>
                            @endif
                        @endif
                    </a>
                    @endforeach
                </div>

            </nav>

            <div class="border-t border-white/[0.08] px-6 py-4">
                <p class="font-sans text-[0.42rem] tracking-[0.2em] uppercase text-paper/15">
                    &copy; {{ date('Y') }} &nbsp;·&nbsp; Akses Terbatas
                </p>
            </div>
        </aside>

        {{-- ══ MAIN CONTENT ══ --}}
        <main class="flex-1 w-full md:ml-56 overflow-y-auto">
            <div class="p-5 md:p-8">

                {{-- Flash Messages --}}
                @if(session('success'))
                <div class="mb-6 border-l-2 border-emerald-700 bg-paper px-4 py-3 flex items-start gap-3">
                    <i class="fas fa-check text-emerald-700 text-[0.6rem] mt-0.5 flex-shrink-0"></i>
                    <p class="font-sans text-[0.72rem] tracking-wide text-ink leading-relaxed">{{ session('success') }}</p>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-ghost hover:text-ink text-xs">✕</button>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 border-l-2 border-red-800 bg-paper px-4 py-3 flex items-start gap-3">
                    <i class="fas fa-xmark text-red-800 text-[0.6rem] mt-0.5 flex-shrink-0"></i>
                    <p class="font-sans text-[0.72rem] tracking-wide text-ink leading-relaxed">{{ session('error') }}</p>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-ghost hover:text-ink text-xs">✕</button>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 border-l-2 border-red-800 bg-paper px-4 py-3">
                    <ul class="space-y-1">
                        @foreach($errors->all() as $error)
                        <li class="font-sans text-[0.72rem] tracking-wide text-red-900">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar  = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) toggleSidebar();
            });
        });

        // PWA
        let deferredPrompt = null;
        const btnInstall = document.getElementById('btnInstall');
        window.addEventListener('beforeinstallprompt', e => {
            e.preventDefault();
            deferredPrompt = e;
            if (btnInstall) btnInstall.style.display = 'flex';
        });
        btnInstall?.addEventListener('click', async () => {
            if (!deferredPrompt) return;
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            if (outcome === 'accepted') btnInstall.style.display = 'none';
            deferredPrompt = null;
        });
        if ('serviceWorker' in navigator) navigator.serviceWorker.register('/sw.js').catch(() => {});
    </script>

    <script>
// ── Notifikasi Polling ──
const notifBadge    = document.getElementById('notifBadge');
const notifList     = document.getElementById('notifList');
const notifDropdown = document.getElementById('notifDropdown');
const btnNotif      = document.getElementById('btnNotif');
const btnBacaSemua  = document.getElementById('btnBacaSemua');

let notifTerbuka = false;

// Toggle dropdown
btnNotif.addEventListener('click', () => {
    notifTerbuka = !notifTerbuka;
    notifDropdown.classList.toggle('hidden', !notifTerbuka);
    if (notifTerbuka) ambilNotifikasi();
});

// Tutup jika klik di luar
document.addEventListener('click', e => {
    if (!document.getElementById('notifWrapper').contains(e.target)) {
        notifDropdown.classList.add('hidden');
        notifTerbuka = false;
    }
});

// Baca semua
btnBacaSemua.addEventListener('click', async () => {
    await fetch('{{ route("admin.notifikasi.baca-semua") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
    });
    ambilNotifikasi();
});

// Render notifikasi
function renderNotifikasi(data) {
    const { jumlah, notifikasi } = data;

    // Badge
    if (jumlah > 0) {
        notifBadge.textContent = jumlah > 99 ? '99+' : jumlah;
        notifBadge.classList.remove('hidden');
        notifBadge.classList.add('flex');
    } else {
        notifBadge.classList.add('hidden');
        notifBadge.classList.remove('flex');
    }

    // List
    if (notifikasi.length === 0) {
        notifList.innerHTML = `
            <div class="py-8 text-center">
                <i class="fas fa-bell-slash text-rule text-2xl block mb-2"></i>
                <p class="font-sans text-[0.6rem] tracking-[0.15em] uppercase text-ghost">Tidak ada notifikasi</p>
            </div>`;
        return;
    }

    notifList.innerHTML = notifikasi.map(n => `
        <div class="flex items-start gap-3 px-4 py-3 hover:bg-cream/50 transition-colors cursor-pointer notif-item"
             data-id="${n.id}" data-peminjaman="${n.peminjaman_id}">
            <div class="w-7 h-7 flex-shrink-0 bg-red-50 border border-red-200 flex items-center justify-center mt-0.5">
                <i class="fas fa-clock text-red-700 text-[0.5rem]"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-sans text-[0.65rem] font-semibold text-ink leading-tight mb-0.5">${n.judul}</p>
                <p class="font-sans text-[0.6rem] text-label leading-relaxed">${n.pesan}</p>
                <p class="font-sans text-[0.52rem] tracking-wide text-ghost mt-1">${n.waktu}</p>
            </div>
        </div>
    `).join('');

    // Klik notif → baca & buka detail
    document.querySelectorAll('.notif-item').forEach(el => {
        el.addEventListener('click', async () => {
            const id         = el.dataset.id;
            const peminjamanId = el.dataset.peminjaman;
            await fetch(`/admin/notifikasi/${id}/baca`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            });
            window.location.href = `/admin/peminjaman/${peminjamanId}`;
        });
    });
}

// Fetch dari server
async function ambilNotifikasi() {
    try {
        const res  = await fetch('{{ route("admin.notifikasi.index") }}');
        const data = await res.json();
        renderNotifikasi(data);
    } catch (e) {
        console.error('Notif error:', e);
    }
}

// Polling setiap 30 detik
ambilNotifikasi();
setInterval(ambilNotifikasi, 30000);
</script>
    @yield('scripts')
    
</body>
</html>
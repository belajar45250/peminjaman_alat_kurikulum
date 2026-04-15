{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — PinjamAlat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 255px;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --topbar-height: 60px;
        }
        body { background: #f1f5f9; font-size: 0.925rem; }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            z-index: 200;
            transition: transform .25s ease;
        }
        .sidebar-brand {
            padding: 20px 18px 16px;
            border-bottom: 1px solid #334155;
        }
        .sidebar-brand .brand-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
        }
        .sidebar-brand .brand-sub {
            font-size: .72rem;
            color: #64748b;
        }
        .sidebar-nav { padding: 10px 10px; flex: 1; }
        .nav-section-label {
            font-size: .68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #475569;
            padding: 14px 8px 6px;
        }
        .sidebar-nav .nav-link {
            color: #94a3b8;
            border-radius: 8px;
            padding: 9px 12px;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: .875rem;
            transition: background .15s, color .15s;
        }
        .sidebar-nav .nav-link:hover { background: var(--sidebar-hover); color: #e2e8f0; }
        .sidebar-nav .nav-link.active { background: #3b82f6; color: #fff; font-weight: 600; }
        .sidebar-nav .nav-link .bi { font-size: 1rem; flex-shrink: 0; }
        .sidebar-footer {
            padding: 14px;
            border-top: 1px solid #334155;
        }
        .sidebar-footer .user-name { color: #cbd5e1; font-size: .82rem; font-weight: 600; }
        .sidebar-footer .user-role { color: #64748b; font-size: .72rem; }

        /* ── Main ── */
        .main-wrapper { margin-left: var(--sidebar-width); min-height: 100vh; display: flex; flex-direction: column; }
        .topbar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 100;
            gap: 12px;
        }
        .topbar .page-title { font-weight: 700; font-size: 1rem; color: #0f172a; }
        .topbar .breadcrumb { font-size: .78rem; margin: 0; }
        .topbar .breadcrumb-item + .breadcrumb-item::before { color: #94a3b8; }
        .page-content { padding: 24px; flex: 1; }

        /* ── Cards ── */
        .card { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.07); }
        .card-header { background: transparent; border-bottom: 1px solid #f1f5f9; padding: 16px 20px; font-weight: 600; }
        .stat-card { border-radius: 12px; border: none; overflow: hidden; }
        .stat-card .icon-box {
            width: 48px; height: 48px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
        }

        /* ── Misc ── */
        .badge { font-size: .72rem; font-weight: 600; border-radius: 6px; }
        .table th { font-size: .78rem; text-transform: uppercase; letter-spacing: .04em; color: #64748b; border-bottom: 2px solid #f1f5f9; }
        .table td { vertical-align: middle; font-size: .875rem; }
        .btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; }

        /* ── Mobile ── */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 199; }
            .sidebar-overlay.show { display: block; }
        }
    </style>
    @yield('styles')
</head>
<body>

{{-- Overlay mobile --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- ══ SIDEBAR ══ --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <div style="width:34px;height:34px;background:#3b82f6;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-tools text-white" style="font-size:.9rem;"></i>
            </div>
            <div>
                <div class="brand-name">PinjamAlat</div>
                <div class="brand-sub">Sistem Peminjaman</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Menu Utama</div>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="{{ route('admin.alat.index') }}"
           class="nav-link {{ request()->routeIs('admin.alat.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Manajemen Alat
        </a>

        <a href="{{ route('admin.pengembalian.index') }}"
           class="nav-link {{ request()->routeIs('admin.pengembalian.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-return-left"></i> Pengembalian
        </a>

        <a href="{{ route('admin.peminjaman.index') }}"
           class="nav-link {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> Riwayat Pinjam
        </a>

        <div class="nav-section-label">Laporan & Sistem</div>

        <a href="{{ route('admin.laporan.index') }}"
           class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i> Laporan
        </a>

        <a href="{{ route('admin.pengaturan.index') }}"
           class="nav-link {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Pengaturan
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div style="width:30px;height:30px;background:#334155;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-person-fill text-slate-300" style="color:#94a3b8;font-size:.85rem;"></i>
            </div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </div>
</aside>

{{-- ══ MAIN WRAPPER ══ --}}
<div class="main-wrapper">

    {{-- Topbar --}}
    <div class="topbar">
        <button class="btn btn-sm btn-light d-lg-none me-1" id="sidebarToggle">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div class="flex-grow-1">
            <div class="page-title">@yield('title', 'Dashboard')</div>
            @hasSection('breadcrumb')
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Home</a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>
            @endif
        </div>
        <div class="d-flex align-items-center gap-2">
            @yield('topbar_actions')
        </div>
    </div>

    {{-- Page Content --}}
    <div class="page-content">

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle mobile
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggle  = document.getElementById('sidebarToggle');

    toggle?.addEventListener('click', () => {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    });
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });
</script>
@yield('scripts')
</body>
</html>
{{-- resources/views/admin/history-kerusakan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'History Kerusakan Alat')

@section('breadcrumb')
    <li class="breadcrumb-item active">History Kerusakan</li>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.history-kerusakan.create') }}" class="btn btn-danger btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Catat Manual
    </a>
@endsection

@section('content')

{{-- Statistik --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="icon-box" style="background:#fee2e2;">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#dc2626;"></i>
                </div>
                <div>
                    <div class="text-muted small">Menunggu Tindakan</div>
                    <div class="fw-bold fs-4 text-danger">{{ $stats['menunggu'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="icon-box" style="background:#fef9c3;">
                    <i class="bi bi-wrench" style="color:#ca8a04;"></i>
                </div>
                <div>
                    <div class="text-muted small">Sedang Diperbaiki</div>
                    <div class="fw-bold fs-4 text-warning">{{ $stats['diperbaiki'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="icon-box" style="background:#fee2e2;">
                    <i class="bi bi-cash-coin" style="color:#dc2626;"></i>
                </div>
                <div>
                    <div class="text-muted small">Denda Belum Lunas</div>
                    <div class="fw-bold fs-4 text-danger">{{ $stats['belum_lunas'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="icon-box" style="background:#fee2e2;">
                    <i class="bi bi-wallet2" style="color:#dc2626;"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Denda Tertunggak</div>
                    <div class="fw-bold" style="font-size:1.1rem;color:#dc2626;">
                        Rp {{ number_format($stats['total_denda'], 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Cari nama, kelas, alat..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="menunggu"           {{ request('status') === 'menunggu'           ? 'selected':'' }}>Menunggu</option>
                    <option value="diperbaiki"         {{ request('status') === 'diperbaiki'         ? 'selected':'' }}>Diperbaiki</option>
                    <option value="sudah_diperbaiki"   {{ request('status') === 'sudah_diperbaiki'   ? 'selected':'' }}>Sudah Diperbaiki</option>
                    <option value="diganti_baru"       {{ request('status') === 'diganti_baru'       ? 'selected':'' }}>Diganti Baru</option>
                    <option value="dihapuskan"         {{ request('status') === 'dihapuskan'         ? 'selected':'' }}>Dihapuskan</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="jenis" class="form-select form-select-sm">
                    <option value="">Semua Jenis</option>
                    <option value="rusak_ringan" {{ request('jenis') === 'rusak_ringan' ? 'selected':'' }}>Rusak Ringan</option>
                    <option value="rusak_berat"  {{ request('jenis') === 'rusak_berat'  ? 'selected':'' }}>Rusak Berat</option>
                    <option value="hilang"       {{ request('jenis') === 'hilang'       ? 'selected':'' }}>Hilang</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="denda" class="form-select form-select-sm">
                    <option value="">Semua Denda</option>
                    <option value="belum_lunas" {{ request('denda') === 'belum_lunas' ? 'selected':'' }}>Belum Lunas</option>
                    <option value="lunas"       {{ request('denda') === 'lunas'       ? 'selected':'' }}>Lunas</option>
                    <option value="tidak_ada"   {{ request('denda') === 'tidak_ada'   ? 'selected':'' }}>Tidak Ada</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                @if(request()->hasAny(['search','status','jenis','denda']))
                    <a href="{{ route('admin.history-kerusakan.index') }}" class="btn btn-light btn-sm">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Alat</th>
                        <th>Penanggung Jawab</th>
                        <th>Jenis Kerusakan</th>
                        <th>Tanggal</th>
                        <th>Denda</th>
                        <th>Status Tindak Lanjut</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $item)
                    <tr>
                        <td>
                            <div class="fw-semibold small">{{ $item->nama_alat_snapshot }}</div>
                            <code style="font-size:.72rem;">{{ $item->kode_alat_snapshot }}</code>
                        </td>
                        <td>
                            @if($item->nama_peminjam)
                                <div class="small">{{ $item->nama_peminjam }}</div>
                                <span class="badge bg-light text-dark border" style="font-size:.7rem;">
                                    {{ $item->kelas }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeJenis = ['rusak_ringan'=>'warning','rusak_berat'=>'danger','hilang'=>'dark'];
                            @endphp
                            <span class="badge bg-{{ $badgeJenis[$item->jenis_kerusakan] ?? 'secondary' }}">
                                {{ $item->label_jenis_kerusakan }}
                            </span>
                        </td>
                        <td class="small">{{ $item->tanggal_rusak->format('d/m/Y') }}</td>
                        <td>
                            @if($item->jumlah_denda > 0)
                                <div class="small fw-semibold {{ $item->status_denda === 'belum_lunas' ? 'text-danger' : 'text-success' }}">
                                    Rp {{ number_format($item->jumlah_denda, 0, ',', '.') }}
                                </div>
                                <span class="badge bg-{{ $item->badge_denda }}" style="font-size:.68rem;">
                                    {{ $item->label_status_denda }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $item->badge_status }}">
                                {{ $item->label_status_tindak_lanjut }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.history-kerusakan.show', $item->id) }}"
                               class="btn btn-icon btn-light border" title="Detail & Tindak Lanjut">
                                <i class="bi bi-eye small"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-check-circle fs-1 d-block mb-2 opacity-25"></i>
                            Tidak ada history kerusakan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($history->hasPages())
            <div class="p-3 border-top">{{ $history->links() }}</div>
        @endif
    </div>
</div>

@endsection
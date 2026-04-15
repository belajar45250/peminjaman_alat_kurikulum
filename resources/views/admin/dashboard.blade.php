{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="icon-box" style="background:#dbeafe;">
                    <i class="bi bi-box-seam" style="color:#2563eb;"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Alat</div>
                    <div class="fw-bold fs-4">{{ $totalAlat }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="icon-box" style="background:#fef9c3;">
                    <i class="bi bi-arrow-up-right-circle" style="color:#ca8a04;"></i>
                </div>
                <div>
                    <div class="text-muted small">Sedang Dipinjam</div>
                    <div class="fw-bold fs-4">{{ $sedangDipinjam }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="icon-box" style="background:#dcfce7;">
                    <i class="bi bi-check2-circle" style="color:#16a34a;"></i>
                </div>
                <div>
                    <div class="text-muted small">Tersedia</div>
                    <div class="fw-bold fs-4">{{ $tersedia }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="icon-box" style="background:#fee2e2;">
                    <i class="bi bi-exclamation-triangle" style="color:#dc2626;"></i>
                </div>
                <div>
                    <div class="text-muted small">Ada Denda</div>
                    <div class="fw-bold fs-4">{{ $adaDenda }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Peminjaman Aktif --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history me-2 text-warning"></i>Peminjaman Aktif Saat Ini</span>
        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-sm btn-outline-primary">
            Lihat Semua
        </a>
    </div>
    <div class="card-body p-0">
        @if($peminjamanAktif->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
            Tidak ada peminjaman aktif saat ini.
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Alat</th>
                        <th>Peminjam</th>
                        <th>Kelas</th>
                        <th>Mapel</th>
                        <th>Waktu Pinjam</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjamanAktif as $item)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $item->nama_alat_snapshot }}</div>
                            <small class="text-muted">{{ $item->kode_alat_snapshot }}</small>
                        </td>
                        <td>{{ $item->nama_peminjam }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $item->kelas }}</span></td>
                        <td>{{ $item->mata_pelajaran }}</td>
                        <td>
                            <div>{{ $item->waktu_pinjam->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ $item->waktu_pinjam->format('H:i') }}</small>
                        </td>
                        <td>
                            @if($item->terlambat)
                                <span class="badge bg-danger">Terlambat</span>
                            @else
                                <span class="badge bg-warning text-dark">Dipinjam</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.peminjaman.show', $item->id) }}"
                               class="btn btn-icon btn-light border" title="Detail">
                                <i class="bi bi-eye small"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

@endsection
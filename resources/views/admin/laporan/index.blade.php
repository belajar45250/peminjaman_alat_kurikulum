{{-- resources/views/admin/laporan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Peminjaman')

@section('breadcrumb')
    <li class="breadcrumb-item active">Laporan</li>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.laporan.pdf', request()->query()) }}"
       class="btn btn-danger btn-sm" target="_blank">
        <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
    </a>
@endsection

@section('content')

{{-- Statistik --}}
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="card text-center p-3">
            <div class="fs-3 fw-bold text-primary">{{ $laporan->total() }}</div>
            <div class="small text-muted">Total Transaksi</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card text-center p-3">
            <div class="fs-3 fw-bold text-danger">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
            <div class="small text-muted">Total Denda</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card text-center p-3">
            <div class="fs-3 fw-bold text-warning">Rp {{ number_format($dendaBelumLunas, 0, ',', '.') }}</div>
            <div class="small text-muted">Denda Belum Lunas</div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small mb-1">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control form-control-sm"
                       value="{{ request('dari') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control form-control-sm"
                       value="{{ request('sampai') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="dipinjam"     {{ request('status') === 'dipinjam'     ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Nama Peminjam</label>
                <input type="text" name="search" class="form-control form-control-sm"
                       value="{{ request('search') }}" placeholder="Cari nama...">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                @if(request()->hasAny(['dari','sampai','status','search']))
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-light btn-sm">Reset</a>
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
                        <th>#</th>
                        <th>Kode Transaksi</th>
                        <th>Alat</th>
                        <th>Peminjam</th>
                        <th>Kelas</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $item)
                    <tr>
                        <td class="text-muted small">{{ $laporan->firstItem() + $loop->index }}</td>
                        <td><code class="small">{{ $item->kode_transaksi }}</code></td>
                        <td class="small">{{ $item->nama_alat_snapshot }}</td>
                        <td>{{ $item->nama_peminjam }}</td>
                        <td>{{ $item->kelas }}</td>
                        <td class="small">{{ $item->waktu_pinjam->format('d/m/Y') }}</td>
                        <td class="small">
                            {{ $item->pengembalian?->waktu_kembali->format('d/m/Y') ?? '-' }}
                        </td>
                        <td>
                            @if($item->status === 'dipinjam')
                                <span class="badge bg-warning text-dark">Dipinjam</span>
                            @else
                                <span class="badge bg-success">Kembali</span>
                            @endif
                        </td>
                        <td>
                            @if($item->pengembalian?->ada_denda)
                                <span class="text-danger fw-semibold small">
                                    Rp {{ number_format($item->pengembalian->jumlah_denda, 0, ',', '.') }}
                                </span>
                                @if(!$item->pengembalian->denda_lunas)
                                    <span class="badge bg-danger ms-1" style="font-size:.65rem;">Belum Lunas</span>
                                @endif
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                            Tidak ada data laporan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($laporan->hasPages())
            <div class="p-3 border-top">{{ $laporan->links() }}</div>
        @endif
    </div>
</div>

@endsection
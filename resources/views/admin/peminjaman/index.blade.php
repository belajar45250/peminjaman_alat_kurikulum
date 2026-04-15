{{-- resources/views/admin/peminjaman/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('breadcrumb')
    <li class="breadcrumb-item active">Riwayat Pinjam</li>
@endsection

@section('content')

{{-- Ringkasan --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <div class="card text-center p-3">
            <div class="fs-3 fw-bold text-warning">{{ $totalDipinjam }}</div>
            <div class="small text-muted">Sedang Dipinjam</div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card text-center p-3">
            <div class="fs-3 fw-bold text-success">{{ $totalDikembalikan }}</div>
            <div class="small text-muted">Sudah Dikembalikan</div>
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
                    <option value="dipinjam"     {{ request('status') === 'dipinjam'     ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="dari" class="form-control form-control-sm"
                       value="{{ request('dari') }}" placeholder="Dari tanggal">
            </div>
            <div class="col-md-2">
                <input type="date" name="sampai" class="form-control form-control-sm"
                       value="{{ request('sampai') }}" placeholder="Sampai tanggal">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                @if(request()->hasAny(['search','status','dari','sampai']))
                    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-light btn-sm">Reset</a>
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
                        <th>Kode Transaksi</th>
                        <th>Alat</th>
                        <th>Peminjam</th>
                        <th>Kelas</th>
                        <th>Waktu Pinjam</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $item)
                    <tr>
                        <td><code class="small">{{ $item->kode_transaksi }}</code></td>
                        <td>
                            <div class="fw-semibold small">{{ $item->nama_alat_snapshot }}</div>
                            <span class="text-muted" style="font-size:.75rem;">{{ $item->kode_alat_snapshot }}</span>
                        </td>
                        <td>{{ $item->nama_peminjam }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $item->kelas }}</span></td>
                        <td>
                            <div class="small">{{ $item->waktu_pinjam->format('d/m/Y') }}</div>
                            <div class="text-muted" style="font-size:.75rem;">{{ $item->waktu_pinjam->format('H:i') }}</div>
                        </td>
                        <td>
                            @if($item->status === 'dipinjam')
                                @if($item->terlambat)
                                    <span class="badge bg-danger">Terlambat</span>
                                @else
                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                @endif
                            @else
                                <span class="badge bg-success">Dikembalikan</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.peminjaman.show', $item->id) }}"
                               class="btn btn-icon btn-light border" title="Detail">
                                <i class="bi bi-eye small"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                            Tidak ada data peminjaman.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($peminjaman->hasPages())
            <div class="p-3 border-top">{{ $peminjaman->links() }}</div>
        @endif
    </div>
</div>

@endsection
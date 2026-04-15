{{-- resources/views/admin/alat/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Manajemen Alat')

@section('breadcrumb')
    <li class="breadcrumb-item active">Alat</li>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.alat.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Tambah Alat
    </a>
    <a href="{{ route('admin.alat.qr-semua') }}" class="btn btn-outline-secondary btn-sm"
       onclick="return confirm('Download QR semua alat?')">
        <i class="bi bi-qr-code me-1"></i> Download Semua QR
    </a>
@endsection

@section('content')

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Cari nama atau kode alat..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ request('status') === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="kondisi" class="form-select form-select-sm">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('kondisi') === 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('kondisi') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('kondisi') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-search me-1"></i>Cari
                </button>
                @if(request()->hasAny(['search','status','kondisi']))
                <a href="{{ route('admin.alat.index') }}" class="btn btn-light btn-sm">Reset</a>
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
                        <th style="width:50px;">#</th>
                        <th>Alat</th>
                        <th>Kode</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th>Harga</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alat as $item)
                    <tr>
                        <td class="text-muted small">{{ $alat->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-semibold">{{ $item->nama_alat }}</div>
                            @if($item->kategori)
                                <small class="text-muted">{{ $item->kategori }}</small>
                            @endif
                        </td>
                        <td><code class="small">{{ $item->kode_alat }}</code></td>
                        <td>
                            @php
                                $kondisiLabel = ['baik'=>'Baik','rusak_ringan'=>'Rusak Ringan','rusak_berat'=>'Rusak Berat','tidak_tersedia'=>'Tidak Tersedia'];
                                $kondisiBadge = ['baik'=>'success','rusak_ringan'=>'warning','rusak_berat'=>'danger','tidak_tersedia'=>'secondary'];
                            @endphp
                            <span class="badge bg-{{ $kondisiBadge[$item->kondisi] ?? 'secondary' }}">
                                {{ $kondisiLabel[$item->kondisi] ?? $item->kondisi }}
                            </span>
                        </td>
                        <td>
                            @if($item->status === 'dipinjam')
                                <span class="badge bg-warning text-dark">Dipinjam</span>
                            @else
                                <span class="badge bg-success">Tersedia</span>
                            @endif
                        </td>
                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('admin.alat.qr-pdf', $item->id) }}"
                                   class="btn btn-icon btn-light border" title="Download QR">
                                    <i class="bi bi-qr-code small"></i>
                                </a>
                                <a href="{{ route('admin.alat.edit', $item->id) }}"
                                   class="btn btn-icon btn-light border" title="Edit">
                                    <i class="bi bi-pencil small"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.alat.destroy', $item->id) }}"
                                      onsubmit="return confirm('Yakin hapus alat ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-icon btn-light border text-danger"
                                            title="Hapus"
                                            {{ $item->sedangDipinjam() ? 'disabled' : '' }}>
                                        <i class="bi bi-trash small"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                            Belum ada data alat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($alat->hasPages())
        <div class="p-3 border-top">
            {{ $alat->links() }}
        </div>
        @endif
    </div>
</div>

@endsection
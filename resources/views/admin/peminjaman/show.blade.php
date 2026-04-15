{{-- resources/views/admin/peminjaman/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.peminjaman.index') }}" class="text-decoration-none text-muted">Riwayat Pinjam</a>
    </li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')

<div class="row g-4">

    {{-- Kolom Kiri --}}
    <div class="col-lg-7">

        {{-- Info Transaksi --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt me-2"></i>Detail Transaksi</span>
                @if($peminjaman->status === 'dipinjam')
                    @if($peminjaman->terlambat)
                        <span class="badge bg-danger">Terlambat</span>
                    @else
                        <span class="badge bg-warning text-dark">Sedang Dipinjam</span>
                    @endif
                @else
                    <span class="badge bg-success">Dikembalikan</span>
                @endif
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted fw-normal small">Kode Transaksi</dt>
                    <dd class="col-7"><code>{{ $peminjaman->kode_transaksi }}</code></dd>

                    <dt class="col-5 text-muted fw-normal small">Nama Alat</dt>
                    <dd class="col-7 fw-semibold">{{ $peminjaman->nama_alat_snapshot }}</dd>

                    <dt class="col-5 text-muted fw-normal small">Kode Alat</dt>
                    <dd class="col-7"><code class="small">{{ $peminjaman->kode_alat_snapshot }}</code></dd>

                    <dt class="col-5 text-muted fw-normal small">Peminjam</dt>
                    <dd class="col-7">{{ $peminjaman->nama_peminjam }}</dd>

                    <dt class="col-5 text-muted fw-normal small">Kelas</dt>
                    <dd class="col-7">{{ $peminjaman->kelas }}</dd>

                    <dt class="col-5 text-muted fw-normal small">Mata Pelajaran</dt>
                    <dd class="col-7">{{ $peminjaman->mata_pelajaran }}</dd>

                    @if($peminjaman->guru_pengampu)
                    <dt class="col-5 text-muted fw-normal small">Guru Pengampu</dt>
                    <dd class="col-7">{{ $peminjaman->guru_pengampu }}</dd>
                    @endif

                    @if($peminjaman->nomor_hp)
                    <dt class="col-5 text-muted fw-normal small">No. HP</dt>
                    <dd class="col-7">{{ $peminjaman->nomor_hp }}</dd>
                    @endif

                    @if($peminjaman->keperluan)
                    <dt class="col-5 text-muted fw-normal small">Keperluan</dt>
                    <dd class="col-7">{{ $peminjaman->keperluan }}</dd>
                    @endif

                    <dt class="col-5 text-muted fw-normal small">Waktu Pinjam</dt>
                    <dd class="col-7">{{ $peminjaman->waktu_pinjam->format('d/m/Y H:i') }}</dd>

                    <dt class="col-5 text-muted fw-normal small">Estimasi Kembali</dt>
                    <dd class="col-7">
                        {{ $peminjaman->estimasi_kembali?->format('d/m/Y H:i') ?? '-' }}
                        @if($peminjaman->terlambat)
                            <span class="badge bg-danger ms-1">Terlambat!</span>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>

    </div>

    {{-- Kolom Kanan --}}
    <div class="col-lg-5">

        {{-- Status Pengembalian --}}
        @if($peminjaman->pengembalian)
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-arrow-return-left me-2 text-success"></i>Data Pengembalian
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-6 text-muted fw-normal small">Kode Pengembalian</dt>
                    <dd class="col-6"><code class="small">{{ $peminjaman->pengembalian->kode_pengembalian }}</code></dd>

                    <dt class="col-6 text-muted fw-normal small">Waktu Kembali</dt>
                    <dd class="col-6">{{ $peminjaman->pengembalian->waktu_kembali->format('d/m/Y H:i') }}</dd>

                    <dt class="col-6 text-muted fw-normal small">Kondisi Kembali</dt>
                    <dd class="col-6">{{ ucfirst(str_replace('_', ' ', $peminjaman->pengembalian->kondisi_kembali)) }}</dd>

                    <dt class="col-6 text-muted fw-normal small">Diproses oleh</dt>
                    <dd class="col-6">{{ $peminjaman->pengembalian->diprosesoleh?->name ?? '-' }}</dd>

                    @if($peminjaman->pengembalian->ada_denda)
                    <dt class="col-6 text-muted fw-normal small">Jumlah Denda</dt>
                    <dd class="col-6 fw-bold text-danger">
                        Rp {{ number_format($peminjaman->pengembalian->jumlah_denda, 0, ',', '.') }}
                    </dd>

                    <dt class="col-6 text-muted fw-normal small">Status Denda</dt>
                    <dd class="col-6">
                        @if($peminjaman->pengembalian->denda_lunas)
                            <span class="badge bg-success">Lunas</span>
                        @else
                            <span class="badge bg-danger">Belum Lunas</span>
                        @endif
                    </dd>
                    @else
                    <dt class="col-6 text-muted fw-normal small">Denda</dt>
                    <dd class="col-6"><span class="badge bg-success">Tidak Ada</span></dd>
                    @endif
                </dl>
            </div>
        </div>
        @else
        <div class="card mb-4">
            <div class="card-body text-center py-4 text-muted">
                <i class="bi bi-hourglass-split fs-2 d-block mb-2 opacity-25"></i>
                Belum dikembalikan
            </div>
        </div>
        @endif

        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-light w-100">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
</div>

@endsection
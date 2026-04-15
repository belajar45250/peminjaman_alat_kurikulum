{{-- resources/views/admin/pengembalian/sukses.blade.php --}}
@extends('layouts.app')

@section('title', 'Pengembalian Berhasil')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-6 text-center">
        <div class="card">
            <div class="card-body p-5">
                <div style="width:72px;height:72px;background:#dcfce7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                    <i class="bi bi-check-lg text-success" style="font-size:2rem;"></i>
                </div>
                <h4 class="fw-bold mb-1">Pengembalian Berhasil!</h4>
                <p class="text-muted mb-4">Alat telah berhasil dicatat sebagai dikembalikan.</p>

                <div class="bg-light rounded p-3 text-start mb-4">
                    <div class="row g-2 small">
                        <div class="col-6 text-muted">Kode Pengembalian</div>
                        <div class="col-6 fw-semibold">{{ $pengembalian->kode_pengembalian }}</div>
                        <div class="col-6 text-muted">Alat</div>
                        <div class="col-6 fw-semibold">{{ $pengembalian->alat->nama_alat }}</div>
                        <div class="col-6 text-muted">Peminjam</div>
                        <div class="col-6">{{ $pengembalian->peminjaman->nama_peminjam }}</div>
                        <div class="col-6 text-muted">Kondisi Kembali</div>
                        <div class="col-6">{{ ucfirst(str_replace('_', ' ', $pengembalian->kondisi_kembali)) }}</div>
                        @if($pengembalian->ada_denda)
                        <div class="col-6 text-muted">Denda</div>
                        <div class="col-6 fw-bold text-danger">
                            Rp {{ number_format($pengembalian->jumlah_denda, 0, ',', '.') }}
                        </div>
                        @endif
                    </div>
                </div>

                @if($pengembalian->ada_denda && !$pengembalian->denda_lunas)
                <div class="alert alert-warning text-start mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Denda belum lunas.</strong> Harap tagih denda sebesar
                    <strong>Rp {{ number_format($pengembalian->jumlah_denda, 0, ',', '.') }}</strong>
                    kepada peminjam.
                </div>
                @endif

                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-return-left me-1"></i>Pengembalian Lagi
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-light">
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
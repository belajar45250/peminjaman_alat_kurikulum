{{-- resources/views/publik/qr-tidak-valid.blade.php --}}
@extends('layouts.guest')

@section('title', 'QR Tidak Valid')

@section('card_header')
    <div style="width:60px;height:60px;background:rgba(255,255,255,.2);border-radius:50%;
                display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
        <i class="bi bi-exclamation-triangle-fill fs-3"></i>
    </div>
    <h5 class="fw-bold mb-1">Tidak Dapat Dipinjam</h5>
    <p class="mb-0 opacity-75 small">QR Code tidak valid atau alat tidak tersedia</p>
@endsection

@section('content')
    <div class="text-center py-2">
        <p class="text-muted mb-4">{{ $pesan }}</p>

        @if($alat)
        <div class="bg-light rounded-3 p-3 mb-4 text-start">
            <div class="small">
                <div class="text-muted">Alat</div>
                <div class="fw-semibold">{{ $alat->nama_alat }}</div>
                <div class="mt-2 text-muted">Status</div>
                @if($alat->status === 'dipinjam')
                    <span class="badge bg-warning text-dark">
                        <i class="bi bi-clock me-1"></i>Sedang Dipinjam
                    </span>
                @else
                    <span class="badge bg-secondary">{{ ucfirst($alat->status) }}</span>
                @endif
            </div>
        </div>
        @endif

        <p class="text-muted small mb-4">
            Hubungi petugas untuk bantuan lebih lanjut.
        </p>

        {{-- Tombol kembali ke home --}}
        <a href="{{ route('home') }}" class="btn btn-primary w-100">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Halaman Utama
        </a>
    </div>
@endsection
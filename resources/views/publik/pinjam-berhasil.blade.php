{{-- resources/views/publik/pinjam-berhasil.blade.php --}}
@extends('layouts.guest')

@section('title', 'Peminjaman Berhasil')

@section('card_header')
    <div style="width:60px;height:60px;background:rgba(255,255,255,.25);border-radius:50%;
                display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
        <i class="bi bi-check-lg fs-3"></i>
    </div>
    <h5 class="fw-bold mb-1">Peminjaman Berhasil! 🎉</h5>
    <p class="mb-0 opacity-75 small">Jaga alat dengan baik ya!</p>
@endsection

@section('content')
    <div class="bg-light rounded-3 p-3 mb-4">
        <div class="row g-2 small">
            <div class="col-5 text-muted">Kode Transaksi</div>
            <div class="col-7 fw-bold font-monospace small">
                {{ $peminjaman->kode_transaksi }}
            </div>

            <div class="col-5 text-muted">Alat</div>
            <div class="col-7 fw-semibold">{{ $peminjaman->nama_alat_snapshot }}</div>

            <div class="col-5 text-muted">Peminjam</div>
            <div class="col-7">{{ $peminjaman->nama_peminjam }}</div>

            <div class="col-5 text-muted">Kelas</div>
            <div class="col-7">{{ $peminjaman->kelas }}</div>

            <div class="col-5 text-muted">Jam Pinjam</div>
            <div class="col-7">
                Jam ke-{{ $peminjaman->jam_pelajaran_mulai }}
                ({{ $peminjaman->waktu_mulai_pinjam }})
            </div>

            <div class="col-5 text-muted">Jam Selesai</div>
            <div class="col-7 fw-semibold text-warning">
                Jam ke-{{ $peminjaman->jam_pelajaran_selesai }}
                ({{ $peminjaman->waktu_selesai_pinjam }})
            </div>

            <div class="col-5 text-muted">Kembali Paling Lambat</div>
            <div class="col-7 fw-semibold text-danger">
                {{ $peminjaman->estimasi_kembali?->format('d/m/Y') }}
                pukul {{ $peminjaman->waktu_selesai_pinjam }}
            </div>
        </div>
    </div>

    <div class="alert alert-info small mb-4">
        <i class="bi bi-info-circle me-1"></i>
        Kembalikan alat ke petugas sebelum jam
        <strong>{{ $peminjaman->waktu_selesai_pinjam }}</strong>.
        Petugas akan scan QR untuk mencatat pengembalian.
    </div>

    {{-- Tombol kembali ke home --}}
    <a href="{{ route('home') }}" class="btn btn-primary w-100 fw-bold">
        <i class="bi bi-house me-2"></i>Kembali ke Halaman Utama
    </a>
@endsection
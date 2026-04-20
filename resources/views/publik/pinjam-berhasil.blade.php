{{-- resources/views/publik/pinjam-berhasil.blade.php --}}
@extends('layouts.guest')
@section('title', 'Peminjaman Berhasil')

@section('content')

<div class="min-h-screen bg-cream flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-md">

        <div class="bg-paper border border-rule relative overflow-hidden">

            {{-- Corner ornament --}}
            <div class="pointer-events-none absolute top-5 right-5 h-9 w-9 border-t border-r border-rule"></div>
            <div class="pointer-events-none absolute bottom-5 left-5 h-9 w-9 border-b border-l border-rule"></div>

            <div class="px-8 py-10 text-center">

                {{-- Icon --}}
                <div class="w-16 h-16 bg-emerald-50 border border-emerald-200 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check text-emerald-700 text-xl"></i>
                </div>

                <p class="font-sans text-[0.52rem] font-semibold tracking-[0.35em] uppercase text-label mb-2">
                    Transaksi
                </p>
                <h1 class="font-serif text-ink text-2xl font-normal leading-none mb-3">
                    Peminjaman Berhasil!
                </h1>
                <div class="h-px w-10 bg-rule mx-auto mb-2"></div>
                <p class="font-sans text-[0.65rem] text-ghost mb-8">
                    Jaga alat dengan baik ya!
                </p>

                {{-- Detail --}}
                <div class="text-left bg-cream/50 border border-rule/60 divide-y divide-rule/40 mb-6">
                    @php
                        $rows = [
                            ['label' => 'Kode Transaksi', 'value' => $peminjaman->kode_transaksi, 'mono' => true],
                            ['label' => 'Alat',           'value' => $peminjaman->nama_alat_snapshot],
                            ['label' => 'Peminjam',       'value' => $peminjaman->nama_peminjam],
                            ['label' => 'Kelas',          'value' => $peminjaman->kelas],
                        ];
                    @endphp
                    @foreach($rows as $row)
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="font-sans text-[0.5rem] tracking-[0.15em] uppercase text-ghost">{{ $row['label'] }}</span>
                        @if($row['mono'] ?? false)
                            <code class="font-mono text-[0.68rem] text-dim bg-cream px-1.5 py-0.5">{{ $row['value'] }}</code>
                        @else
                            <span class="font-sans text-[0.78rem] text-ink font-medium">{{ $row['value'] }}</span>
                        @endif
                    </div>
                    @endforeach

                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="font-sans text-[0.5rem] tracking-[0.15em] uppercase text-ghost">Jam Pinjam</span>
                        <span class="font-sans text-[0.78rem] text-dim">
                            Jam ke-{{ $peminjaman->jam_pelajaran_mulai }}
                            ({{ $peminjaman->waktu_mulai_pinjam }})
                        </span>
                    </div>

                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="font-sans text-[0.5rem] tracking-[0.15em] uppercase text-ghost">Jam Selesai</span>
                        <span class="font-sans text-[0.78rem] font-semibold text-amber-800">
                            Jam ke-{{ $peminjaman->jam_pelajaran_selesai }}
                            ({{ $peminjaman->waktu_selesai_pinjam }})
                        </span>
                    </div>

                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="font-sans text-[0.5rem] tracking-[0.15em] uppercase text-ghost">Kembali Paling Lambat</span>
                        <span class="font-sans text-[0.78rem] font-semibold text-red-800">
                            {{ $peminjaman->estimasi_kembali?->format('d/m/Y') }}
                            pukul {{ $peminjaman->waktu_selesai_pinjam }}
                        </span>
                    </div>
                </div>

                {{-- Info --}}
                <div class="border-l-2 border-rule bg-cream/50 px-4 py-3 text-left mb-8">
                    <p class="font-sans text-[0.65rem] tracking-wide text-label leading-relaxed">
                        <i class="fas fa-info-circle text-ghost mr-1.5 text-[0.58rem]"></i>
                        Kembalikan alat ke petugas sebelum jam
                        <span class="font-semibold text-ink">{{ $peminjaman->waktu_selesai_pinjam }}</span>.
                        Petugas akan scan QR untuk mencatat pengembalian.
                    </p>
                </div>

                {{-- CTA --}}
                <a href="{{ route('home') }}"
                   class="flex items-center justify-center gap-2 w-full bg-espresso text-paper py-3.5
                          font-sans text-[0.6rem] font-semibold tracking-[0.28em] uppercase
                          hover:bg-ink transition-colors active:scale-[0.99]">
                    <i class="fas fa-house text-[0.5rem]"></i>
                    Kembali ke Halaman Utama
                </a>

            </div>
        </div>

        <p class="text-center font-sans text-[0.52rem] tracking-[0.12em] uppercase text-ghost/60 mt-5">
            {{ \App\Models\Pengaturan::ambil('nama_sekolah', 'Sistem Peminjaman') }}
        </p>
    </div>
</div>

@endsection
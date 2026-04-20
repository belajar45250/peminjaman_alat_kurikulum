{{-- resources/views/publik/qr-tidak-valid.blade.php --}}
@extends('layouts.guest')
@section('title', 'QR Tidak Valid')

@section('content')

<div class="min-h-screen bg-cream flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-sm">

        <div class="bg-paper border border-rule relative overflow-hidden">

            {{-- Corner ornament --}}
            <div class="pointer-events-none absolute top-5 right-5 h-8 w-8 border-t border-r border-rule"></div>
            <div class="pointer-events-none absolute bottom-5 left-5 h-8 w-8 border-b border-l border-rule"></div>

            <div class="px-8 py-10 text-center">

                {{-- Icon --}}
                <div class="w-16 h-16 bg-red-50 border border-red-200 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-triangle-exclamation text-red-700 text-xl"></i>
                </div>

                <p class="font-sans text-[0.52rem] font-semibold tracking-[0.35em] uppercase text-label mb-2">
                    Perhatian
                </p>
                <h1 class="font-serif text-ink text-2xl font-normal leading-none mb-3">
                    Tidak Dapat Dipinjam
                </h1>
                <div class="h-px w-10 bg-rule mx-auto mb-6"></div>

                {{-- Pesan error --}}
                <div class="border-l-2 border-red-300 bg-red-50/50 px-4 py-3 text-left mb-6">
                    <p class="font-sans text-[0.72rem] tracking-wide text-red-800 leading-relaxed">
                        {{ $pesan }}
                    </p>
                </div>

                {{-- Info Alat --}}
                @if($alat)
                <div class="bg-cream/50 border border-rule/60 divide-y divide-rule/40 mb-6 text-left">
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="font-sans text-[0.5rem] tracking-[0.15em] uppercase text-ghost">Alat</span>
                        <span class="font-sans text-[0.78rem] text-ink font-medium">{{ $alat->nama_alat }}</span>
                    </div>
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="font-sans text-[0.5rem] tracking-[0.15em] uppercase text-ghost">Kode</span>
                        <code class="font-mono text-[0.68rem] text-dim bg-cream px-1.5 py-0.5">{{ $alat->kode_alat }}</code>
                    </div>
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="font-sans text-[0.5rem] tracking-[0.15em] uppercase text-ghost">Status</span>
                        @if($alat->status === 'dipinjam')
                            <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-amber-50 text-amber-800 border-amber-200">
                                Sedang Dipinjam
                            </span>
                        @else
                            <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-sand text-dim border-rule">
                                {{ ucfirst($alat->status) }}
                            </span>
                        @endif
                    </div>
                </div>
                @endif

                <p class="font-sans text-[0.62rem] tracking-wide text-ghost mb-8">
                    Hubungi petugas untuk bantuan lebih lanjut.
                </p>

                {{-- CTA --}}
                <a href="{{ route('home') }}"
                   class="flex items-center justify-center gap-2 w-full bg-espresso text-paper py-3.5
                          font-sans text-[0.6rem] font-semibold tracking-[0.28em] uppercase
                          hover:bg-ink transition-colors active:scale-[0.99]">
                    <i class="fas fa-arrow-left text-[0.5rem]"></i>
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
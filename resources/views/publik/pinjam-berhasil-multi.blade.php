{{-- resources/views/publik/pinjam-berhasil-multi.blade.php --}}
@extends('layouts.guest')
@section('title', 'Peminjaman Berhasil')

@section('content')

<div class="min-h-screen bg-cream flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-lg">

        <div class="bg-paper border border-rule relative overflow-hidden">
            <div class="pointer-events-none absolute top-5 right-5 h-9 w-9 border-t border-r border-rule"></div>
            <div class="pointer-events-none absolute bottom-5 left-5 h-9 w-9 border-b border-l border-rule"></div>

            <div class="px-8 py-8 text-center">
                <div class="w-16 h-16 bg-emerald-50 border border-emerald-200 flex items-center justify-center mx-auto mb-5">
                    <i class="fas fa-check text-emerald-700 text-xl"></i>
                </div>

                <p class="font-sans text-[0.52rem] font-semibold tracking-[0.35em] uppercase text-label mb-2">Berhasil</p>
                <h1 class="font-serif text-ink text-2xl font-normal leading-none mb-2">
                    {{ count($peminjamanList) }} Alat Dipinjam
                </h1>
                <div class="h-px w-10 bg-rule mx-auto mb-6"></div>

                {{-- Info peminjam (dari transaksi pertama) --}}
                @php $first = $peminjamanList[0]; @endphp
                <div class="text-left bg-cream/50 border border-rule/60 divide-y divide-rule/40 mb-5">
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="font-sans text-[0.5rem] tracking-[0.15em] uppercase text-ghost">Peminjam</span>
                        <span class="font-sans text-[0.78rem] text-ink font-medium">{{ $first->nama_peminjam }}</span>
                    </div>
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="font-sans text-[0.5rem] tracking-[0.15em] uppercase text-ghost">Kelas</span>
                        <span class="font-sans text-[0.78rem] text-dim">{{ $first->kelas }}</span>
                    </div>
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="font-sans text-[0.5rem] tracking-[0.15em] uppercase text-ghost">Mata Pelajaran</span>
                        <span class="font-sans text-[0.78rem] text-dim">{{ $first->mata_pelajaran }}</span>
                    </div>
                    @if($first->guru_pengampu)
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="font-sans text-[0.5rem] tracking-[0.15em] uppercase text-ghost">Guru Pengampu</span>
                        <span class="font-sans text-[0.78rem] text-dim">{{ $first->guru_pengampu }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="font-sans text-[0.5rem] tracking-[0.15em] uppercase text-ghost">Jam Pelajaran</span>
                        <span class="font-sans text-[0.78rem] text-amber-800 font-semibold">
                            Ke-{{ $first->jam_pelajaran_mulai }} s/d Ke-{{ $first->jam_pelajaran_selesai }}
                            ({{ $first->waktu_mulai_pinjam }} – {{ $first->waktu_selesai_pinjam }})
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="font-sans text-[0.5rem] tracking-[0.15em] uppercase text-ghost">Kembali Paling Lambat</span>
                        <span class="font-sans text-[0.78rem] text-red-800 font-semibold">
                            Pukul {{ $first->waktu_selesai_pinjam }}
                        </span>
                    </div>
                </div>

                {{-- Daftar Alat --}}
                <div class="text-left mb-6">
                    <p class="font-sans text-[0.5rem] font-semibold tracking-[0.25em] uppercase text-ghost mb-3">
                        Daftar Alat yang Dipinjam
                    </p>
                    <div class="space-y-2">
                        @foreach($peminjamanList as $i => $p)
                        <div class="flex items-center gap-3 border border-rule/60 px-4 py-3 bg-cream/30">
                            <div class="w-6 h-6 bg-espresso flex items-center justify-center flex-shrink-0">
                                <span class="font-sans text-[0.5rem] font-bold text-paper">{{ $i + 1 }}</span>
                            </div>
                            <div class="flex-1">
                                <p class="font-sans text-[0.78rem] text-ink font-medium">{{ $p->nama_alat_snapshot }}</p>
                                <code class="font-mono text-[0.6rem] text-ghost">{{ $p->kode_alat_snapshot }}</code>
                            </div>
                            <code class="font-mono text-[0.58rem] text-ghost bg-cream px-1.5 py-0.5">
                                {{ $p->kode_transaksi }}
                            </code>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Info --}}
                <div class="border-l-2 border-rule bg-cream/50 px-4 py-3 text-left mb-6">
                    <p class="font-sans text-[0.65rem] tracking-wide text-label leading-relaxed">
                        <i class="fas fa-info-circle text-ghost mr-1.5 text-[0.58rem]"></i>
                        Kembalikan <strong class="text-ink">semua alat</strong> ke petugas sebelum jam
                        <strong class="text-ink">{{ $first->waktu_selesai_pinjam }}</strong>.
                        Petugas akan scan QR satu per satu.
                    </p>
                </div>

                <a href="{{ route('home') }}"
                   class="flex items-center justify-center gap-2 w-full bg-espresso text-paper py-3.5
                          font-sans text-[0.6rem] font-semibold tracking-[0.28em] uppercase
                          hover:bg-ink transition-colors">
                    <i class="fas fa-house text-[0.5rem]"></i> Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
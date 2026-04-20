{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

<div class="mb-8">
    <p class="font-sans text-[0.55rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">Ringkasan Sistem</p>
    <h1 class="font-serif text-ink text-3xl font-normal leading-none">Dashboard</h1>
    <div class="mt-3 h-px w-10 bg-rule"></div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @php
        $stats = [
            ['label'=>'Total Alat',      'value'=>$totalAlat,      'icon'=>'fa-box-open'],
            ['label'=>'Sedang Dipinjam', 'value'=>$sedangDipinjam, 'icon'=>'fa-arrow-up-right-from-square'],
            ['label'=>'Tersedia',        'value'=>$tersedia,       'icon'=>'fa-circle-check'],
            ['label'=>'Ada Denda',       'value'=>$adaDenda,       'icon'=>'fa-triangle-exclamation'],
        ];
    @endphp
    @foreach($stats as $s)
    <div class="bg-paper border border-rule p-5 group hover:border-espresso/30 transition-colors duration-200">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="font-sans text-[0.52rem] font-semibold tracking-[0.22em] uppercase text-label mb-2">{{ $s['label'] }}</p>
                <p class="font-serif text-[2rem] font-normal leading-none text-ink">{{ $s['value'] }}</p>
            </div>
            <div class="w-10 h-10 bg-cream flex items-center justify-center flex-shrink-0">
                <i class="fas {{ $s['icon'] }} text-ghost text-[0.65rem]"></i>
            </div>
        </div>
        <div class="mt-5 h-px w-0 bg-espresso/20 group-hover:w-full transition-all duration-500"></div>
    </div>
    @endforeach
</div>

{{-- Peminjaman Aktif --}}
<div class="bg-paper border border-rule">
    <div class="border-b border-rule px-5 py-4 flex items-center justify-between">
        <div>
            <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Transaksi</p>
            <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Peminjaman Aktif Saat Ini</h2>
        </div>
        <a href="{{ route('admin.peminjaman.index') }}"
           class="flex items-center gap-2 border border-rule text-label px-3 py-2
                  font-sans text-[0.52rem] font-semibold tracking-[0.18em] uppercase
                  hover:bg-sand transition-colors">
            Lihat Semua
        </a>
    </div>

    @if($peminjamanAktif->isEmpty())
    <div class="py-16 text-center">
        <i class="fas fa-inbox text-rule text-3xl block mb-3"></i>
        <p class="font-sans text-[0.62rem] tracking-[0.2em] uppercase text-ghost">Tidak ada peminjaman aktif</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    @foreach(['Alat','Peminjam','Kelas','Mapel','Jam','Waktu Pinjam','Status',''] as $th)
                    <th class="font-sans text-[0.48rem] font-semibold tracking-[0.2em] uppercase text-label py-3.5 px-5 text-left border-b border-rule bg-cream">{{ $th }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($peminjamanAktif as $item)
                <tr class="hover:bg-cream/40 transition-colors">
                    <td class="py-4 px-5 border-b border-rule/40">
                        <p class="font-serif text-ink text-[0.9rem] leading-tight">{{ $item->nama_alat_snapshot }}</p>
                        <code class="font-mono text-[0.65rem] text-ghost bg-cream px-1 py-0.5 mt-0.5 inline-block">{{ $item->kode_alat_snapshot }}</code>
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40 font-sans text-[0.78rem] text-dim">{{ $item->nama_peminjam }}</td>
                    <td class="py-4 px-5 border-b border-rule/40">
                        <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-cream text-label border-rule">{{ $item->kelas }}</span>
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40 font-sans text-[0.75rem] text-dim">{{ $item->mata_pelajaran }}</td>
                    <td class="py-4 px-5 border-b border-rule/40 font-sans text-[0.72rem] text-dim">
                        @if(isset($item->jam_pelajaran_mulai))
                            Ke-{{ $item->jam_pelajaran_mulai }}–{{ $item->jam_pelajaran_selesai }}<br>
                            <span class="text-ghost text-[0.65rem]">{{ $item->waktu_mulai_pinjam }}–{{ $item->waktu_selesai_pinjam }}</span>
                        @else —
                        @endif
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40">
                        <p class="font-sans text-[0.75rem] text-dim">{{ $item->waktu_pinjam->format('d/m/Y') }}</p>
                        <p class="font-sans text-[0.65rem] text-ghost">{{ $item->waktu_pinjam->format('H:i') }}</p>
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40">
                        @if($item->terlambat)
                            <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-red-50 text-red-800 border-red-200">Terlambat</span>
                        @else
                            <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-amber-50 text-amber-800 border-amber-200">Dipinjam</span>
                        @endif
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40">
                        <a href="{{ route('admin.peminjaman.show', $item->id) }}"
                           class="w-7 h-7 border border-rule flex items-center justify-center text-ghost hover:bg-espresso hover:text-paper hover:border-espresso transition-all">
                            <i class="fas fa-arrow-right text-[0.5rem]"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
{{-- resources/views/admin/pengembalian/sukses.blade.php --}}
@extends('layouts.app')
@section('title', 'Pengembalian Berhasil')

@section('content')

<div class="max-w-lg mx-auto">
    <div class="bg-paper border border-rule relative overflow-hidden">
        {{-- Corner ornament --}}
        <div class="pointer-events-none absolute top-5 right-5 h-10 w-10 border-t border-r border-rule"></div>
        <div class="pointer-events-none absolute bottom-5 left-5 h-10 w-10 border-b border-l border-rule"></div>

        <div class="px-8 py-10 text-center">
            {{-- Icon --}}
            <div class="w-16 h-16 bg-emerald-50 border border-emerald-200 flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-emerald-700 text-xl"></i>
            </div>

            <p class="font-sans text-[0.52rem] font-semibold tracking-[0.35em] uppercase text-label mb-2">Berhasil</p>
            <h1 class="font-serif text-ink text-2xl font-normal leading-none mb-3">Pengembalian Dicatat</h1>
            <div class="h-px w-10 bg-rule mx-auto mb-6"></div>

            <p class="font-sans text-[0.72rem] text-ghost mb-8">Alat telah berhasil dicatat sebagai dikembalikan.</p>

            {{-- Detail --}}
            <div class="text-left bg-cream/50 border border-rule/60 divide-y divide-rule/40 mb-6">
                @php
                    $rows = [
                        ['label'=>'Kode Pengembalian','value'=>$pengembalian->kode_pengembalian, 'mono'=>true],
                        ['label'=>'Alat',             'value'=>$pengembalian->alat->nama_alat],
                        ['label'=>'Peminjam',         'value'=>$pengembalian->peminjaman->nama_peminjam],
                        ['label'=>'Kondisi Kembali',  'value'=>ucfirst(str_replace('_',' ',$pengembalian->kondisi_kembali))],
                    ];
                @endphp
                @foreach($rows as $row)
                <div class="flex items-center justify-between px-4 py-3">
                    <span class="font-sans text-[0.52rem] tracking-[0.15em] uppercase text-ghost">{{ $row['label'] }}</span>
                    @if($row['mono'] ?? false)
                        <code class="font-mono text-[0.7rem] text-dim bg-cream px-1.5 py-0.5">{{ $row['value'] }}</code>
                    @else
                        <span class="font-sans text-[0.78rem] text-ink font-medium">{{ $row['value'] }}</span>
                    @endif
                </div>
                @endforeach

                @if($pengembalian->ada_denda)
                <div class="flex items-center justify-between px-4 py-3">
                    <span class="font-sans text-[0.52rem] tracking-[0.15em] uppercase text-ghost">Denda</span>
                    <span class="font-sans text-[0.82rem] font-semibold text-red-800">
                        Rp {{ number_format($pengembalian->jumlah_denda, 0, ',', '.') }}
                    </span>
                </div>
                @endif
            </div>

            {{-- Peringatan denda --}}
            @if($pengembalian->ada_denda && !$pengembalian->denda_lunas)
            <div class="border-l-2 border-amber-400 bg-amber-50/50 px-4 py-3 text-left mb-6">
                <p class="font-sans text-[0.68rem] tracking-wide text-amber-800 leading-relaxed">
                    <i class="fas fa-triangle-exclamation mr-1.5 text-[0.58rem]"></i>
                    <span class="font-semibold">Denda belum lunas.</span>
                    Harap tagih denda sebesar
                    <span class="font-semibold">Rp {{ number_format($pengembalian->jumlah_denda, 0, ',', '.') }}</span>
                    kepada peminjam.
                </p>
            </div>
            @endif

            {{-- Actions --}}
            <div class="flex gap-3 justify-center">
                <a href="{{ route('admin.pengembalian.index') }}"
                   class="flex items-center gap-2 bg-espresso text-paper px-5 py-2.5
                          font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase
                          hover:bg-ink transition-colors">
                    <i class="fas fa-arrow-return-left text-[0.5rem]"></i> Pengembalian Lagi
                </a>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-2 border border-rule text-label px-5 py-2.5
                          font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase
                          hover:bg-sand transition-colors">
                    Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
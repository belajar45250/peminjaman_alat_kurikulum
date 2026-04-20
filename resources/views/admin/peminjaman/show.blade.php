{{-- resources/views/admin/peminjaman/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Peminjaman')

@section('content')

<div class="mb-8 flex items-end justify-between">
    <div>
        <p class="font-sans text-[0.55rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">Riwayat Peminjaman</p>
        <h1 class="font-serif text-ink text-3xl font-normal leading-none">Detail Transaksi</h1>
        <div class="mt-3 h-px w-10 bg-rule"></div>
    </div>
    @if($peminjaman->status === 'dipinjam')
        @if($peminjaman->terlambat)
            <span class="font-sans text-[0.52rem] tracking-[0.18em] uppercase px-3 py-1.5 border bg-red-50 text-red-800 border-red-200">Terlambat</span>
        @else
            <span class="font-sans text-[0.52rem] tracking-[0.18em] uppercase px-3 py-1.5 border bg-amber-50 text-amber-800 border-amber-200">Sedang Dipinjam</span>
        @endif
    @else
        <span class="font-sans text-[0.52rem] tracking-[0.18em] uppercase px-3 py-1.5 border bg-emerald-50 text-emerald-800 border-emerald-200">Dikembalikan</span>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

    {{-- Kolom Kiri --}}
    <div class="lg:col-span-3">
        <div class="bg-paper border border-rule">
            <div class="border-b border-rule px-5 py-4">
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Informasi</p>
                <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Data Peminjaman</h2>
            </div>
            <div class="px-5 py-5 space-y-4">
                @php
                    $rows = [
                        ['label'=>'Kode Transaksi', 'code'  => $peminjaman->kode_transaksi],
                        ['label'=>'Nama Alat',      'value' => $peminjaman->nama_alat_snapshot, 'bold'=>true],
                        ['label'=>'Kode Alat',      'code'  => $peminjaman->kode_alat_snapshot],
                        ['label'=>'Peminjam',       'value' => $peminjaman->nama_peminjam],
                        ['label'=>'Kelas',          'value' => $peminjaman->kelas],
                        ['label'=>'Mata Pelajaran', 'value' => $peminjaman->mata_pelajaran],
                    ];
                @endphp
                @foreach($rows as $row)
                <div class="flex items-start gap-4">
                    <span class="font-sans text-[0.52rem] tracking-[0.15em] uppercase text-ghost w-36 flex-shrink-0 pt-0.5">{{ $row['label'] }}</span>
                    @if(isset($row['code']))
                        <code class="font-mono text-[0.72rem] text-dim bg-cream px-2 py-0.5">{{ $row['code'] }}</code>
                    @elseif($row['bold'] ?? false)
                        <span class="font-serif text-ink text-[0.95rem]">{{ $row['value'] }}</span>
                    @else
                        <span class="font-sans text-[0.82rem] text-dim">{{ $row['value'] }}</span>
                    @endif
                </div>
                @endforeach

                @if($peminjaman->guru_pengampu)
                <div class="flex items-start gap-4">
                    <span class="font-sans text-[0.52rem] tracking-[0.15em] uppercase text-ghost w-36 flex-shrink-0 pt-0.5">Guru Pengampu</span>
                    <span class="font-sans text-[0.82rem] text-dim">{{ $peminjaman->guru_pengampu }}</span>
                </div>
                @endif

                @if(isset($peminjaman->jam_pelajaran_mulai))
                <div class="flex items-start gap-4">
                    <span class="font-sans text-[0.52rem] tracking-[0.15em] uppercase text-ghost w-36 flex-shrink-0 pt-0.5">Jam Pelajaran</span>
                    <span class="font-sans text-[0.82rem] text-dim">
                        Jam ke-{{ $peminjaman->jam_pelajaran_mulai }} ({{ $peminjaman->waktu_mulai_pinjam }})
                        s/d Jam ke-{{ $peminjaman->jam_pelajaran_selesai }} ({{ $peminjaman->waktu_selesai_pinjam }})
                    </span>
                </div>
                @endif

                @if($peminjaman->keperluan)
                <div class="flex items-start gap-4">
                    <span class="font-sans text-[0.52rem] tracking-[0.15em] uppercase text-ghost w-36 flex-shrink-0 pt-0.5">Keperluan</span>
                    <span class="font-sans text-[0.82rem] text-dim">{{ $peminjaman->keperluan }}</span>
                </div>
                @endif

                <div class="pt-3 border-t border-rule/50 space-y-3">
                    <div class="flex items-start gap-4">
                        <span class="font-sans text-[0.52rem] tracking-[0.15em] uppercase text-ghost w-36 flex-shrink-0 pt-0.5">Waktu Pinjam</span>
                        <span class="font-sans text-[0.82rem] text-dim">{{ $peminjaman->waktu_pinjam->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex items-start gap-4">
                        <span class="font-sans text-[0.52rem] tracking-[0.15em] uppercase text-ghost w-36 flex-shrink-0 pt-0.5">Estimasi Kembali</span>
                        <div>
                            <span class="font-sans text-[0.82rem] text-dim">{{ $peminjaman->estimasi_kembali?->format('d/m/Y H:i') ?? '—' }}</span>
                            @if($peminjaman->terlambat)
                                <span class="ml-2 font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-red-50 text-red-800 border-red-200">Terlambat!</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan --}}
    <div class="lg:col-span-2 space-y-5">

        @if($peminjaman->pengembalian)
        <div class="bg-paper border border-rule">
            <div class="border-b border-rule px-5 py-4">
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Pengembalian</p>
                <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Data Pengembalian</h2>
            </div>
            <div class="px-5 py-5 space-y-3">
                @foreach([
                    ['label'=>'Kode Pengembalian','code' =>$peminjaman->pengembalian->kode_pengembalian],
                    ['label'=>'Waktu Kembali',    'value'=>$peminjaman->pengembalian->waktu_kembali->format('d/m/Y H:i')],
                    ['label'=>'Kondisi Kembali',  'value'=>ucfirst(str_replace('_',' ',$peminjaman->pengembalian->kondisi_kembali))],
                    ['label'=>'Diproses Oleh',    'value'=>$peminjaman->pengembalian->diprosesoleh?->name ?? '—'],
                ] as $row)
                <div class="flex items-center gap-4">
                    <span class="font-sans text-[0.52rem] tracking-[0.15em] uppercase text-ghost w-36 flex-shrink-0">{{ $row['label'] }}</span>
                    @if(isset($row['code']))
                        <code class="font-mono text-[0.7rem] text-dim bg-cream px-1.5 py-0.5">{{ $row['code'] }}</code>
                    @else
                        <span class="font-sans text-[0.8rem] text-dim">{{ $row['value'] }}</span>
                    @endif
                </div>
                @endforeach

                @if($peminjaman->pengembalian->ada_denda)
                <div class="pt-3 border-t border-rule/50 space-y-3">
                    <div class="flex items-center gap-4">
                        <span class="font-sans text-[0.52rem] tracking-[0.15em] uppercase text-ghost w-36 flex-shrink-0">Jumlah Denda</span>
                        <span class="font-sans text-[0.88rem] font-semibold text-red-800">
                            Rp {{ number_format($peminjaman->pengembalian->jumlah_denda, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="font-sans text-[0.52rem] tracking-[0.15em] uppercase text-ghost w-36 flex-shrink-0">Status Denda</span>
                        @if($peminjaman->pengembalian->denda_lunas)
                            <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-emerald-50 text-emerald-800 border-emerald-200">Lunas</span>
                        @else
                            <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-red-50 text-red-800 border-red-200">Belum Lunas</span>
                        @endif
                    </div>
                </div>
                @else
                <div class="flex items-center gap-4 pt-3 border-t border-rule/50">
                    <span class="font-sans text-[0.52rem] tracking-[0.15em] uppercase text-ghost w-36 flex-shrink-0">Denda</span>
                    <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-emerald-50 text-emerald-800 border-emerald-200">Tidak Ada</span>
                </div>
                @endif
            </div>
        </div>
        @else
        <div class="bg-paper border border-rule p-8 text-center">
            <i class="fas fa-hourglass-half text-rule text-3xl block mb-3"></i>
            <p class="font-sans text-[0.62rem] tracking-[0.2em] uppercase text-ghost">Belum dikembalikan</p>
        </div>
        @endif

        <a href="{{ route('admin.peminjaman.index') }}"
           class="flex items-center justify-center gap-2 w-full border border-rule text-label py-3
                  font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase
                  hover:bg-sand transition-colors">
            <i class="fas fa-arrow-left text-[0.5rem]"></i> Kembali ke Daftar
        </a>
    </div>

</div>

@endsection
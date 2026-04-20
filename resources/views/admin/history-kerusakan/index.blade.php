{{-- resources/views/admin/history-kerusakan/index.blade.php --}}
@extends('layouts.app')
@section('title', 'History Kerusakan Alat')

@section('content')

{{-- Page Header --}}
<div class="mb-8 flex items-end justify-between">
    <div>
        <p class="font-sans text-[0.55rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">Administrasi</p>
        <h1 class="font-serif text-ink text-3xl font-normal leading-none">History Kerusakan</h1>
        <div class="mt-3 h-px w-10 bg-rule"></div>
    </div>
    <a href="{{ route('admin.history-kerusakan.create') }}"
       class="flex items-center gap-2 bg-espresso text-paper px-4 py-2.5
              font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase
              hover:bg-ink transition-colors">
        <i class="fas fa-plus text-[0.5rem]"></i> Catat Manual
    </a>
</div>

{{-- Statistik --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-paper border border-rule p-5">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="font-sans text-[0.52rem] font-semibold tracking-[0.22em] uppercase text-label mb-2">Menunggu Tindakan</p>
                <p class="font-serif text-[2rem] font-normal leading-none text-red-800">{{ $stats['menunggu'] }}</p>
            </div>
            <div class="w-10 h-10 bg-red-50 border border-red-100 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-triangle-exclamation text-red-700 text-[0.65rem]"></i>
            </div>
        </div>
    </div>

    <div class="bg-paper border border-rule p-5">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="font-sans text-[0.52rem] font-semibold tracking-[0.22em] uppercase text-label mb-2">Sedang Diperbaiki</p>
                <p class="font-serif text-[2rem] font-normal leading-none text-amber-800">{{ $stats['diperbaiki'] }}</p>
            </div>
            <div class="w-10 h-10 bg-amber-50 border border-amber-100 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-wrench text-amber-700 text-[0.65rem]"></i>
            </div>
        </div>
    </div>

    <div class="bg-paper border border-rule p-5">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="font-sans text-[0.52rem] font-semibold tracking-[0.22em] uppercase text-label mb-2">Denda Belum Lunas</p>
                <p class="font-serif text-[2rem] font-normal leading-none text-red-800">{{ $stats['belum_lunas'] }}</p>
            </div>
            <div class="w-10 h-10 bg-red-50 border border-red-100 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-file-invoice-dollar text-red-700 text-[0.65rem]"></i>
            </div>
        </div>
    </div>

    <div class="bg-paper border border-rule p-5">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="font-sans text-[0.52rem] font-semibold tracking-[0.22em] uppercase text-label mb-2">Total Tertunggak</p>
                <p class="font-serif text-[1.4rem] font-normal leading-none text-red-800">
                    Rp {{ number_format($stats['total_denda'], 0, ',', '.') }}
                </p>
            </div>
            <div class="w-10 h-10 bg-red-50 border border-red-100 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-wallet text-red-700 text-[0.65rem]"></i>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="bg-paper border border-rule mb-5">
    <div class="px-5 py-4 border-b border-rule/60">
        <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Filter</p>
    </div>
    <div class="px-5 py-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[160px]">
                <label class="block font-sans text-[0.48rem] tracking-[0.2em] uppercase text-ghost mb-2">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nama, kelas, alat..."
                       class="w-full border-b border-rule bg-transparent pb-2 pt-1 font-sans text-[0.82rem] text-ink outline-none focus:border-ink placeholder-ghost">
            </div>
            @foreach([
                ['name'=>'status','label'=>'Status','opts'=>[''=>'Semua Status','menunggu'=>'Menunggu','diperbaiki'=>'Diperbaiki','sudah_diperbaiki'=>'Sudah Diperbaiki','diganti_baru'=>'Diganti Baru','dihapuskan'=>'Dihapuskan']],
                ['name'=>'jenis','label'=>'Jenis','opts'=>[''=>'Semua Jenis','rusak_ringan'=>'Rusak Ringan','rusak_berat'=>'Rusak Berat','hilang'=>'Hilang']],
                ['name'=>'denda','label'=>'Denda','opts'=>[''=>'Semua','belum_lunas'=>'Belum Lunas','lunas'=>'Lunas','tidak_ada'=>'Tidak Ada']],
            ] as $f)
            <div class="min-w-[130px]">
                <label class="block font-sans text-[0.48rem] tracking-[0.2em] uppercase text-ghost mb-2">{{ $f['label'] }}</label>
                <select name="{{ $f['name'] }}"
                        class="w-full border-b border-rule bg-transparent pb-2 pt-1 font-sans text-[0.82rem] text-ink outline-none focus:border-ink">
                    @foreach($f['opts'] as $v => $l)
                        <option value="{{ $v }}" {{ request($f['name']) === $v ? 'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            @endforeach
            <div class="flex gap-2">
                <button type="submit"
                    class="flex items-center gap-1.5 bg-espresso text-paper px-4 py-2.5
                           font-sans text-[0.55rem] font-semibold tracking-[0.2em] uppercase hover:bg-ink transition-colors">
                    <i class="fas fa-search text-[0.5rem]"></i> Filter
                </button>
                @if(request()->hasAny(['search','status','jenis','denda']))
                <a href="{{ route('admin.history-kerusakan.index') }}"
                   class="flex items-center gap-1.5 border border-rule text-label px-4 py-2.5
                          font-sans text-[0.55rem] font-semibold tracking-[0.2em] uppercase hover:bg-sand transition-colors">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="bg-paper border border-rule">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    @foreach(['Alat','Penanggung Jawab','Jenis Kerusakan','Tanggal','Denda','Status Tindak Lanjut',''] as $th)
                    <th class="font-sans text-[0.48rem] font-semibold tracking-[0.2em] uppercase text-label
                               py-3.5 px-5 text-left border-b border-rule bg-cream {{ $loop->last ? 'text-center':'' }}">
                        {{ $th }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($history as $item)
                @php
                    $jenisMap = [
                        'rusak_ringan' => ['label'=>'Rusak Ringan','class'=>'bg-amber-50 text-amber-800 border-amber-200'],
                        'rusak_berat'  => ['label'=>'Rusak Berat', 'class'=>'bg-red-50 text-red-800 border-red-200'],
                        'hilang'       => ['label'=>'Hilang',       'class'=>'bg-espresso/10 text-ink border-rule'],
                    ];
                    $statusMap = [
                        'menunggu'           => ['label'=>'Menunggu',        'class'=>'bg-red-50 text-red-800 border-red-200'],
                        'diperbaiki'         => ['label'=>'Diperbaiki',      'class'=>'bg-amber-50 text-amber-800 border-amber-200'],
                        'sudah_diperbaiki'   => ['label'=>'Sudah Diperbaiki','class'=>'bg-emerald-50 text-emerald-800 border-emerald-200'],
                        'diganti_baru'       => ['label'=>'Diganti Baru',    'class'=>'bg-sky-50 text-sky-800 border-sky-200'],
                        'dihapuskan'         => ['label'=>'Dihapuskan',      'class'=>'bg-sand text-dim border-rule'],
                    ];
                    $dendaMap = [
                        'belum_lunas' => ['label'=>'Belum Lunas','class'=>'bg-red-50 text-red-800 border-red-200'],
                        'lunas'       => ['label'=>'Lunas',      'class'=>'bg-emerald-50 text-emerald-800 border-emerald-200'],
                        'tidak_ada'   => ['label'=>'Tidak Ada',  'class'=>'bg-sand text-dim border-rule'],
                    ];
                    $j = $jenisMap[$item->jenis_kerusakan]          ?? ['label'=>$item->jenis_kerusakan,'class'=>'bg-sand text-dim border-rule'];
                    $s = $statusMap[$item->status_tindak_lanjut]    ?? ['label'=>$item->status_tindak_lanjut,'class'=>'bg-sand text-dim border-rule'];
                    $d = $dendaMap[$item->status_denda]              ?? ['label'=>$item->status_denda,'class'=>'bg-sand text-dim border-rule'];
                @endphp
                <tr class="hover:bg-cream/40 transition-colors">
                    <td class="py-4 px-5 border-b border-rule/40">
                        <p class="font-serif text-ink text-[0.9rem] font-normal leading-tight">{{ $item->nama_alat_snapshot }}</p>
                        <code class="font-mono text-[0.65rem] text-ghost bg-cream px-1 py-0.5 mt-0.5 inline-block">{{ $item->kode_alat_snapshot }}</code>
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40">
                        @if($item->nama_peminjam)
                            <p class="font-sans text-[0.78rem] text-dim">{{ $item->nama_peminjam }}</p>
                            <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-cream text-label border-rule">
                                {{ $item->kelas }}
                            </span>
                        @else
                            <span class="text-ghost font-sans text-[0.7rem]">—</span>
                        @endif
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40">
                        <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border {{ $j['class'] }}">
                            {{ $j['label'] }}
                        </span>
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40 font-sans text-[0.75rem] text-dim">
                        {{ $item->tanggal_rusak->format('d/m/Y') }}
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40">
                        @if($item->jumlah_denda > 0)
                            <p class="font-sans text-[0.75rem] font-semibold {{ $item->status_denda === 'belum_lunas' ? 'text-red-800' : 'text-emerald-800' }}">
                                Rp {{ number_format($item->jumlah_denda, 0, ',', '.') }}
                            </p>
                            <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border {{ $d['class'] }}">
                                {{ $d['label'] }}
                            </span>
                        @else
                            <span class="text-ghost font-sans text-[0.7rem]">—</span>
                        @endif
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40">
                        <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border {{ $s['class'] }}">
                            {{ $s['label'] }}
                        </span>
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40 text-center">
                        <a href="{{ route('admin.history-kerusakan.show', $item->id) }}"
                           class="w-7 h-7 border border-rule flex items-center justify-center mx-auto
                                  text-ghost hover:bg-espresso hover:text-paper hover:border-espresso transition-all"
                           title="Detail">
                            <i class="fas fa-arrow-right text-[0.5rem]"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 text-center">
                        <i class="fas fa-check-circle text-rule text-3xl block mb-3"></i>
                        <p class="font-sans text-[0.62rem] tracking-[0.2em] uppercase text-ghost">Tidak ada history kerusakan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($history->hasPages())
    <div class="px-5 py-4 border-t border-rule/60">
        {{ $history->links() }}
    </div>
    @endif
</div>

@endsection
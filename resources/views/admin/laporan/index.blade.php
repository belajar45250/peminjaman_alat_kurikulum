{{-- resources/views/admin/laporan/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Laporan Peminjaman')

@section('content')

<div class="mb-8 flex items-end justify-between">
    <div>
        <p class="font-sans text-[0.55rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">Administrasi</p>
        <h1 class="font-serif text-ink text-3xl font-normal leading-none">Laporan Peminjaman</h1>
        <div class="mt-3 h-px w-10 bg-rule"></div>
    </div>
    <a href="{{ route('admin.laporan.pdf', request()->query()) }}" target="_blank"
       class="flex items-center gap-2 bg-espresso text-paper px-4 py-2.5
              font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase
              hover:bg-ink transition-colors">
        <i class="fas fa-file-pdf text-[0.55rem]"></i> Export PDF
    </a>
</div>

{{-- Statistik --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    @php
        $statCards = [
            ['label'=>'Total Transaksi',    'value'=>$laporan->total(),                                          'serif'=>true],
            ['label'=>'Total Denda',        'value'=>'Rp '.number_format($totalDenda, 0, ',', '.'),              'serif'=>false, 'red'=>true],
            ['label'=>'Denda Belum Lunas',  'value'=>'Rp '.number_format($dendaBelumLunas, 0, ',', '.'),         'serif'=>false, 'red'=>true],
        ];
    @endphp
    @foreach($statCards as $sc)
    <div class="bg-paper border border-rule p-5">
        <p class="font-sans text-[0.52rem] font-semibold tracking-[0.22em] uppercase text-label mb-2">{{ $sc['label'] }}</p>
        <p class="{{ $sc['serif'] ?? false ? 'font-serif text-[2rem]' : 'font-sans text-xl font-semibold' }}
                  {{ ($sc['red'] ?? false) ? 'text-red-800' : 'text-ink' }} leading-none">
            {{ $sc['value'] }}
        </p>
    </div>
    @endforeach
</div>

{{-- Filter --}}
<div class="bg-paper border border-rule mb-5">
    <div class="px-5 py-4 border-b border-rule/60">
        <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Filter & Pencarian</p>
    </div>
    <div class="px-5 py-4">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block font-sans text-[0.48rem] tracking-[0.2em] uppercase text-ghost mb-2">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ request('dari') }}"
                       class="border-b border-rule bg-transparent pb-2 pt-1 font-sans text-[0.82rem] text-ink outline-none focus:border-ink transition-colors">
            </div>
            <div>
                <label class="block font-sans text-[0.48rem] tracking-[0.2em] uppercase text-ghost mb-2">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}"
                       class="border-b border-rule bg-transparent pb-2 pt-1 font-sans text-[0.82rem] text-ink outline-none focus:border-ink transition-colors">
            </div>
            <div class="min-w-[120px]">
                <label class="block font-sans text-[0.48rem] tracking-[0.2em] uppercase text-ghost mb-2">Status</label>
                <select name="status" class="w-full border-b border-rule bg-transparent pb-2 pt-1 font-sans text-[0.82rem] text-ink outline-none focus:border-ink">
                    <option value="">Semua</option>
                    <option value="dipinjam"     {{ request('status') === 'dipinjam'     ? 'selected':'' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected':'' }}>Dikembalikan</option>
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block font-sans text-[0.48rem] tracking-[0.2em] uppercase text-ghost mb-2">Nama Peminjam</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama..."
                       class="w-full border-b border-rule bg-transparent pb-2 pt-1 font-sans text-[0.82rem] text-ink outline-none focus:border-ink placeholder-ghost">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="flex items-center gap-1.5 bg-espresso text-paper px-4 py-2.5
                           font-sans text-[0.55rem] font-semibold tracking-[0.2em] uppercase hover:bg-ink transition-colors">
                    <i class="fas fa-filter text-[0.5rem]"></i> Filter
                </button>
                @if(request()->hasAny(['dari','sampai','status','search']))
                <a href="{{ route('admin.laporan.index') }}"
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
                    @foreach(['#','Kode Transaksi','Alat','Peminjam','Kelas','Tgl Pinjam','Tgl Kembali','Status','Denda'] as $th)
                    <th class="font-sans text-[0.48rem] font-semibold tracking-[0.2em] uppercase text-label py-3.5 px-4 text-left border-b border-rule bg-cream">{{ $th }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($laporan as $item)
                <tr class="hover:bg-cream/40 transition-colors">
                    <td class="py-3.5 px-4 border-b border-rule/40 font-sans text-[0.68rem] text-ghost">{{ $laporan->firstItem() + $loop->index }}</td>
                    <td class="py-3.5 px-4 border-b border-rule/40">
                        <code class="font-mono text-[0.68rem] text-dim bg-cream px-1.5 py-0.5">{{ $item->kode_transaksi }}</code>
                    </td>
                    <td class="py-3.5 px-4 border-b border-rule/40 font-sans text-[0.75rem] text-dim">{{ $item->nama_alat_snapshot }}</td>
                    <td class="py-3.5 px-4 border-b border-rule/40 font-sans text-[0.78rem] text-ink">{{ $item->nama_peminjam }}</td>
                    <td class="py-3.5 px-4 border-b border-rule/40 font-sans text-[0.72rem] text-dim">{{ $item->kelas }}</td>
                    <td class="py-3.5 px-4 border-b border-rule/40 font-sans text-[0.72rem] text-dim">{{ $item->waktu_pinjam->format('d/m/Y') }}</td>
                    <td class="py-3.5 px-4 border-b border-rule/40 font-sans text-[0.72rem] text-dim">
                        {{ $item->pengembalian?->waktu_kembali->format('d/m/Y') ?? '—' }}
                    </td>
                    <td class="py-3.5 px-4 border-b border-rule/40">
                        @if($item->status === 'dipinjam')
                            <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-amber-50 text-amber-800 border-amber-200">Dipinjam</span>
                        @else
                            <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-emerald-50 text-emerald-800 border-emerald-200">Kembali</span>
                        @endif
                    </td>
                    <td class="py-3.5 px-4 border-b border-rule/40">
                        @if($item->pengembalian?->ada_denda)
                            <p class="font-sans text-[0.72rem] font-semibold text-red-800">
                                Rp {{ number_format($item->pengembalian->jumlah_denda, 0, ',', '.') }}
                            </p>
                            @if(!$item->pengembalian->denda_lunas)
                                <span class="font-sans text-[0.45rem] tracking-[0.1em] uppercase px-1.5 py-0.5 border bg-red-50 text-red-800 border-red-200 mt-0.5 inline-block">Belum Lunas</span>
                            @endif
                        @else
                            <span class="text-ghost font-sans text-[0.7rem]">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="py-16 text-center">
                        <i class="fas fa-inbox text-rule text-3xl block mb-3"></i>
                        <p class="font-sans text-[0.62rem] tracking-[0.2em] uppercase text-ghost">Tidak ada data laporan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($laporan->hasPages())
    <div class="px-5 py-4 border-t border-rule/60">{{ $laporan->links() }}</div>
    @endif
</div>

@endsection
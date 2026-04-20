{{-- resources/views/admin/peminjaman/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Riwayat Peminjaman')

@section('content')

<div class="mb-8">
    <p class="font-sans text-[0.55rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">Transaksi</p>
    <h1 class="font-serif text-ink text-3xl font-normal leading-none">Riwayat Peminjaman</h1>
    <div class="mt-3 h-px w-10 bg-rule"></div>
</div>

{{-- Ringkasan --}}
<div class="grid grid-cols-2 gap-4 mb-8 max-w-sm">
    <div class="bg-paper border border-rule p-5">
        <p class="font-sans text-[0.52rem] font-semibold tracking-[0.22em] uppercase text-label mb-2">Sedang Dipinjam</p>
        <p class="font-serif text-[2rem] font-normal leading-none text-amber-800">{{ $totalDipinjam }}</p>
    </div>
    <div class="bg-paper border border-rule p-5">
        <p class="font-sans text-[0.52rem] font-semibold tracking-[0.22em] uppercase text-label mb-2">Dikembalikan</p>
        <p class="font-serif text-[2rem] font-normal leading-none text-emerald-800">{{ $totalDikembalikan }}</p>
    </div>
</div>

{{-- Filter --}}
<div class="bg-paper border border-rule mb-5">
    <div class="px-5 py-4 border-b border-rule/60">
        <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Filter & Pencarian</p>
    </div>
    <div class="px-5 py-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[180px]">
                <label class="block font-sans text-[0.48rem] tracking-[0.2em] uppercase text-ghost mb-2">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, kelas, alat..."
                       class="w-full border-b border-rule bg-transparent pb-2 pt-1 font-sans text-[0.82rem] text-ink outline-none focus:border-ink placeholder-ghost">
            </div>
            <div class="min-w-[130px]">
                <label class="block font-sans text-[0.48rem] tracking-[0.2em] uppercase text-ghost mb-2">Status</label>
                <select name="status" class="w-full border-b border-rule bg-transparent pb-2 pt-1 font-sans text-[0.82rem] text-ink outline-none focus:border-ink">
                    <option value="">Semua</option>
                    <option value="dipinjam"     {{ request('status') === 'dipinjam'     ? 'selected':'' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected':'' }}>Dikembalikan</option>
                </select>
            </div>
            <div>
                <label class="block font-sans text-[0.48rem] tracking-[0.2em] uppercase text-ghost mb-2">Dari</label>
                <input type="date" name="dari" value="{{ request('dari') }}"
                       class="border-b border-rule bg-transparent pb-2 pt-1 font-sans text-[0.82rem] text-ink outline-none focus:border-ink">
            </div>
            <div>
                <label class="block font-sans text-[0.48rem] tracking-[0.2em] uppercase text-ghost mb-2">Sampai</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}"
                       class="border-b border-rule bg-transparent pb-2 pt-1 font-sans text-[0.82rem] text-ink outline-none focus:border-ink">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="flex items-center gap-1.5 bg-espresso text-paper px-4 py-2.5
                           font-sans text-[0.55rem] font-semibold tracking-[0.2em] uppercase hover:bg-ink transition-colors">
                    <i class="fas fa-search text-[0.5rem]"></i> Filter
                </button>
                @if(request()->hasAny(['search','status','dari','sampai']))
                <a href="{{ route('admin.peminjaman.index') }}"
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
                    @foreach(['Kode Transaksi','Alat','Peminjam','Kelas','Waktu Pinjam','Status',''] as $th)
                    <th class="font-sans text-[0.48rem] font-semibold tracking-[0.2em] uppercase text-label py-3.5 px-5 text-left border-b border-rule bg-cream {{ $loop->last ? 'text-center w-12':'' }}">{{ $th }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $item)
                <tr class="hover:bg-cream/40 transition-colors">
                    <td class="py-4 px-5 border-b border-rule/40">
                        <code class="font-mono text-[0.68rem] text-dim bg-cream px-1.5 py-0.5">{{ $item->kode_transaksi }}</code>
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40">
                        <p class="font-serif text-ink text-[0.88rem] leading-tight">{{ $item->nama_alat_snapshot }}</p>
                        <code class="font-mono text-[0.62rem] text-ghost bg-cream px-1 py-0.5 mt-0.5 inline-block">{{ $item->kode_alat_snapshot }}</code>
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40 font-sans text-[0.78rem] text-ink">{{ $item->nama_peminjam }}</td>
                    <td class="py-4 px-5 border-b border-rule/40">
                        <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-cream text-label border-rule">{{ $item->kelas }}</span>
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40">
                        <p class="font-sans text-[0.75rem] text-dim">{{ $item->waktu_pinjam->format('d/m/Y') }}</p>
                        <p class="font-sans text-[0.65rem] text-ghost">{{ $item->waktu_pinjam->format('H:i') }}</p>
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40">
                        @if($item->status === 'dipinjam')
                            @if($item->terlambat)
                                <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-red-50 text-red-800 border-red-200">Terlambat</span>
                            @else
                                <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-amber-50 text-amber-800 border-amber-200">Dipinjam</span>
                            @endif
                        @else
                            <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-emerald-50 text-emerald-800 border-emerald-200">Dikembalikan</span>
                        @endif
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40 text-center">
                        <a href="{{ route('admin.peminjaman.show', $item->id) }}"
                           class="w-7 h-7 border border-rule flex items-center justify-center mx-auto text-ghost hover:bg-espresso hover:text-paper hover:border-espresso transition-all">
                            <i class="fas fa-arrow-right text-[0.5rem]"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 text-center">
                        <i class="fas fa-inbox text-rule text-3xl block mb-3"></i>
                        <p class="font-sans text-[0.62rem] tracking-[0.2em] uppercase text-ghost">Tidak ada data peminjaman</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($peminjaman->hasPages())
    <div class="px-5 py-4 border-t border-rule/60">{{ $peminjaman->links() }}</div>
    @endif
</div>

@endsection
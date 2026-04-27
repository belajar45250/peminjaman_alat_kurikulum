@extends('layouts.app')
@section('title', 'Manajemen Alat')

@section('content')

{{-- Page Header --}}
<div class="mb-8 flex flex-col lg:flex-row lg:items-end justify-between gap-5">
    <div>
        <p class="font-sans text-[0.55rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">Inventaris</p>
        <h1 class="font-serif text-ink text-3xl font-normal leading-none">Manajemen Alat</h1>
        <div class="mt-3 h-px w-10 bg-rule"></div>
    </div>

    {{-- Container tombol dengan flex-wrap agar tidak ke pinggir (offsite) di HP --}}
    <div class="flex flex-wrap items-center gap-2 md:gap-3">

        {{-- Tombol Import --}}
        <button onclick="document.getElementById('modalImportAlat').classList.remove('hidden')"
                class="flex items-center gap-2 border border-rule text-label px-3 py-2.5 md:px-4
                       font-sans text-[0.55rem] md:text-[0.58rem] font-semibold tracking-[0.22em] uppercase
                       hover:bg-sand transition-colors">
            <i class="fas fa-file-import text-[0.5rem]"></i> Import
        </button>

        {{-- Tombol Export --}}
        <a href="{{ route('admin.alat.export') }}"
           class="flex items-center gap-2 border border-rule text-label px-3 py-2.5 md:px-4
                  font-sans text-[0.55rem] md:text-[0.58rem] font-semibold tracking-[0.22em] uppercase
                  hover:bg-sand transition-colors">
            <i class="fas fa-file-export text-[0.5rem]"></i> Export
        </a>

        {{-- Tombol Semua QR --}}
        <a href="{{ route('admin.alat.qr-semua') }}"
           class="flex items-center gap-2 border border-rule text-label px-3 py-2.5 md:px-4
                  font-sans text-[0.55rem] md:text-[0.58rem] font-semibold tracking-[0.22em] uppercase
                  hover:bg-sand transition-colors"
           onclick="return confirm('Download QR semua alat?')">
            <i class="fas fa-qrcode text-[0.5rem]"></i> Semua QR
        </a>

        {{-- Tombol Tambah Alat (Primary CTA — paling kanan/bawah) --}}
        <a href="{{ route('admin.alat.create') }}"
           class="flex items-center gap-2 bg-espresso text-paper px-3 py-2.5 md:px-4
                  font-sans text-[0.55rem] md:text-[0.58rem] font-semibold tracking-[0.22em] uppercase
                  hover:bg-ink transition-colors">
            <i class="fas fa-plus text-[0.5rem]"></i> Tambah Alat
        </a>

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
                <label class="block font-sans text-[0.5rem] tracking-[0.2em] uppercase text-ghost mb-2">Cari</label>
                <input type="text" name="search"
                       value="{{ request('search') }}"
                       placeholder="Nama atau kode alat..."
                       class="w-full border-b border-rule bg-transparent pb-2 pt-1 font-sans text-[0.82rem] text-ink outline-none focus:border-ink placeholder-ghost">
            </div>
            <div class="min-w-[130px]">
                <label class="block font-sans text-[0.5rem] tracking-[0.2em] uppercase text-ghost mb-2">Status</label>
                <select name="status" class="w-full border-b border-rule bg-transparent pb-2 pt-1 font-sans text-[0.82rem] text-ink outline-none focus:border-ink">
                    <option value="">Semua</option>
                    <option value="tersedia" {{ request('status') === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="dipinjam" {{ request('status') === 'dipinjam'  ? 'selected' : '' }}>Dipinjam</option>
                </select>
            </div>
            <div class="min-w-[130px]">
                <label class="block font-sans text-[0.5rem] tracking-[0.2em] uppercase text-ghost mb-2">Kondisi</label>
                <select name="kondisi" class="w-full border-b border-rule bg-transparent pb-2 pt-1 font-sans text-[0.82rem] text-ink outline-none focus:border-ink">
                    <option value="">Semua</option>
                    <option value="baik"        {{ request('kondisi') === 'baik'        ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('kondisi') === 'rusak_ringan'? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat"  {{ request('kondisi') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="flex items-center gap-1.5 bg-espresso text-paper px-4 py-2.5
                           font-sans text-[0.55rem] font-semibold tracking-[0.2em] uppercase hover:bg-ink transition-colors">
                    <i class="fas fa-search text-[0.5rem]"></i> Cari
                </button>
                @if(request()->hasAny(['search','status','kondisi']))
                <a href="{{ route('admin.alat.index') }}"
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
                    <th class="font-sans text-[0.48rem] font-semibold tracking-[0.2em] uppercase text-label py-3.5 px-5 text-left border-b border-rule bg-cream">#</th>
                    <th class="font-sans text-[0.48rem] font-semibold tracking-[0.2em] uppercase text-label py-3.5 px-5 text-left border-b border-rule bg-cream">Alat</th>
                    <th class="font-sans text-[0.48rem] font-semibold tracking-[0.2em] uppercase text-label py-3.5 px-5 text-left border-b border-rule bg-cream">Kode</th>
                    <th class="font-sans text-[0.48rem] font-semibold tracking-[0.2em] uppercase text-label py-3.5 px-5 text-left border-b border-rule bg-cream">Kondisi</th>
                    <th class="font-sans text-[0.48rem] font-semibold tracking-[0.2em] uppercase text-label py-3.5 px-5 text-left border-b border-rule bg-cream">Status</th>
                    <th class="font-sans text-[0.48rem] font-semibold tracking-[0.2em] uppercase text-label py-3.5 px-5 text-left border-b border-rule bg-cream">Harga</th>
                    <th class="font-sans text-[0.48rem] font-semibold tracking-[0.2em] uppercase text-label py-3.5 px-5 text-center border-b border-rule bg-cream">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alat as $item)
                <tr class="group hover:bg-cream/50 transition-colors">
                    <td class="font-sans text-[0.68rem] text-ghost py-4 px-5 border-b border-rule/40">
                        {{ $alat->firstItem() + $loop->index }}
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40">
                        <p class="font-serif text-ink text-[0.95rem] font-normal leading-tight">{{ $item->nama_alat }}</p>
                        @if($item->kategori)
                            <p class="font-sans text-[0.58rem] tracking-wide text-ghost mt-0.5">{{ $item->kategori }}</p>
                        @endif
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40">
                        <code class="font-mono text-[0.72rem] text-dim bg-cream px-1.5 py-0.5">{{ $item->kode_alat }}</code>
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40">
                        @php
                            $kondisiMap = [
                                'baik'          => ['label' => 'Baik',          'class' => 'bg-emerald-50 text-emerald-800 border-emerald-200'],
                                'rusak_ringan'  => ['label' => 'Rusak Ringan',  'class' => 'bg-amber-50 text-amber-800 border-amber-200'],
                                'rusak_berat'   => ['label' => 'Rusak Berat',   'class' => 'bg-red-50 text-red-800 border-red-200'],
                                'tidak_tersedia'=> ['label' => 'Tidak Tersedia','class' => 'bg-sand text-dim border-rule'],
                            ];
                            $k = $kondisiMap[$item->kondisi] ?? ['label' => $item->kondisi, 'class' => 'bg-sand text-dim border-rule'];
                        @endphp
                        <span class="font-sans text-[0.48rem] tracking-[0.12em] uppercase px-2 py-0.5 border {{ $k['class'] }}">
                            {{ $k['label'] }}
                        </span>
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40">
                        @if($item->status === 'dipinjam')
                            <span class="font-sans text-[0.48rem] tracking-[0.12em] uppercase px-2 py-0.5 border bg-amber-50 text-amber-800 border-amber-200">Dipinjam</span>
                        @else
                            <span class="font-sans text-[0.48rem] tracking-[0.12em] uppercase px-2 py-0.5 border bg-emerald-50 text-emerald-800 border-emerald-200">Tersedia</span>
                        @endif
                    </td>
                    <td class="font-sans text-[0.78rem] text-dim py-4 px-5 border-b border-rule/40">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </td>
                    <td class="py-4 px-5 border-b border-rule/40">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.alat.qr-pdf', $item->id) }}"
                               class="w-7 h-7 border border-rule flex items-center justify-center text-ghost hover:bg-espresso hover:text-paper hover:border-espresso transition-all"
                               title="Download QR">
                                <i class="fas fa-qrcode text-[0.5rem]"></i>
                            </a>
                            <a href="{{ route('admin.alat.edit', $item->id) }}"
                               class="w-7 h-7 border border-rule flex items-center justify-center text-ghost hover:bg-espresso hover:text-paper hover:border-espresso transition-all"
                               title="Edit">
                                <i class="fas fa-pen text-[0.5rem]"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.alat.destroy', $item->id) }}"
                                  onsubmit="return confirm('Yakin hapus alat ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-7 h-7 border border-rule flex items-center justify-center text-ghost hover:bg-red-900 hover:text-paper hover:border-red-900 transition-all disabled:opacity-40"
                                        title="Hapus"
                                        {{ $item->sedangDipinjam() ? 'disabled' : '' }}>
                                    <i class="fas fa-trash text-[0.5rem]"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 text-center">
                        <i class="fas fa-inbox text-rule text-3xl block mb-3"></i>
                        <p class="font-sans text-[0.65rem] tracking-[0.2em] uppercase text-ghost">Belum ada data alat</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- Pagination Custom Aesthetic --}}
    @if($alat->hasPages())
    <div class="px-6 py-5 border-t border-rule/60 flex items-center justify-between bg-paper">
        {{-- Info Status --}}
        <p class="font-sans text-[0.55rem] font-medium tracking-[0.15em] uppercase text-ghost">
            Data <span class="text-ink">{{ $alat->firstItem() }}</span> sampai <span class="text-ink">{{ $alat->lastItem() }}</span> dari <span class="text-ink">{{ $alat->total() }}</span> total alat
        </p>

        {{-- Navigasi Angka --}}
        <div class="flex items-center gap-1.5">
            {{-- Tombol Previous --}}
            @if ($alat->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center border border-rule/30 text-ghost/40 cursor-not-allowed">
                    <i class="fas fa-chevron-left text-[0.5rem]"></i>
                </span>
            @else
                <a href="{{ $alat->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center border border-rule text-ghost hover:bg-espresso hover:text-paper hover:border-espresso transition-all">
                    <i class="fas fa-chevron-left text-[0.5rem]"></i>
                </a>
            @endif

            {{-- Loop Angka Halaman --}}
            @foreach ($alat->getUrlRange(max(1, $alat->currentPage() - 1), min($alat->lastPage(), $alat->currentPage() + 1)) as $page => $url)
                @if ($page == $alat->currentPage())
                    <span class="w-8 h-8 flex items-center justify-center bg-espresso text-paper font-sans text-[0.65rem] font-bold shadow-sm">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center border border-rule text-ink font-sans text-[0.65rem] hover:bg-sand transition-all">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Tombol Next --}}
            @if ($alat->hasMorePages())
                <a href="{{ $alat->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center border border-rule text-ghost hover:bg-espresso hover:text-paper hover:border-espresso transition-all">
                    <i class="fas fa-chevron-right text-[0.5rem]"></i>
                </a>
            @else
                <span class="w-8 h-8 flex items-center justify-center border border-rule/30 text-ghost/40 cursor-not-allowed">
                    <i class="fas fa-chevron-right text-[0.5rem]"></i>
                </span>
            @endif
        </div>
    </div>
    @endif


{{-- ══ MODAL IMPORT CSV ══ --}}
<div id="modalImportAlat" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-paper border border-rule w-full max-w-sm mx-4">
        <div class="border-b border-rule px-6 py-4 flex items-center justify-between">
            <div>
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Database</p>
                <h3 class="font-serif text-ink text-lg font-normal mt-0.5">Import CSV Alat</h3>
            </div>
            <button onclick="document.getElementById('modalImportAlat').classList.add('hidden')"
                    class="w-7 h-7 border border-rule flex items-center justify-center text-ghost hover:bg-espresso hover:text-paper transition-all">
                <i class="fas fa-xmark text-[0.5rem]"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.alat.import') }}" enctype="multipart/form-data" class="px-6 py-6 space-y-5">
            @csrf
            <div>
                <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">File CSV (.csv)</label>
                <input type="file" name="file" accept=".csv" required
                       class="w-full text-xs text-ghost file:mr-4 file:py-2 file:px-4 file:bg-cream file:border file:border-rule file:text-[0.55rem] file:uppercase">

                <div class="mt-4 border-l-2 border-rule bg-cream/50 px-3 py-2">
                    <p class="font-sans text-[0.55rem] tracking-wide text-ghost leading-relaxed">
                        <span class="font-bold text-ink">Urutan Kolom Wajib:</span><br>
                        1. Nama Alat, 2. Nomor Urut, 3. Kode Alat, 4. Kategori, 5. Harga, 6. Kondisi, 7. Lokasi, 8. Deskripsi
                    </p>
                </div>
            </div>
            <div class="pt-2">
                <button type="submit" class="w-full bg-espresso text-paper py-3 font-sans text-[0.58rem] font-bold uppercase tracking-[0.2em] hover:bg-ink">
                    Mulai Import Data
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
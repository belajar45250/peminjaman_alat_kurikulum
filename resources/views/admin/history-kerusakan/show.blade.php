{{-- resources/views/admin/history-kerusakan/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Kerusakan')

@section('content')

@php
    $statusMap = [
        'menunggu'           => ['label'=>'Menunggu Tindakan', 'class'=>'bg-red-50 text-red-800 border-red-200'],
        'diperbaiki'         => ['label'=>'Sedang Diperbaiki', 'class'=>'bg-amber-50 text-amber-800 border-amber-200'],
        'sudah_diperbaiki'   => ['label'=>'Sudah Diperbaiki',  'class'=>'bg-emerald-50 text-emerald-800 border-emerald-200'],
        'diganti_baru'       => ['label'=>'Diganti Baru',      'class'=>'bg-sky-50 text-sky-800 border-sky-200'],
        'dihapuskan'         => ['label'=>'Dihapuskan',        'class'=>'bg-sand text-dim border-rule'],
    ];
    $jenisMap = [
        'rusak_ringan' => ['label'=>'Rusak Ringan','class'=>'bg-amber-50 text-amber-800 border-amber-200'],
        'rusak_berat'  => ['label'=>'Rusak Berat', 'class'=>'bg-red-50 text-red-800 border-red-200'],
        'hilang'       => ['label'=>'Hilang',       'class'=>'bg-espresso/10 text-ink border-rule'],
    ];
    $dendaMap = [
        'belum_lunas' => ['label'=>'Belum Lunas','class'=>'bg-red-50 text-red-800 border-red-200'],
        'lunas'       => ['label'=>'Lunas',      'class'=>'bg-emerald-50 text-emerald-800 border-emerald-200'],
        'tidak_ada'   => ['label'=>'Tidak Ada',  'class'=>'bg-sand text-dim border-rule'],
    ];
    $st = $statusMap[$historyKerusakan->status_tindak_lanjut] ?? ['label'=>$historyKerusakan->status_tindak_lanjut,'class'=>'bg-sand text-dim border-rule'];
    $jn = $jenisMap[$historyKerusakan->jenis_kerusakan]       ?? ['label'=>$historyKerusakan->jenis_kerusakan,'class'=>'bg-sand text-dim border-rule'];
    $dn = $dendaMap[$historyKerusakan->status_denda]           ?? ['label'=>$historyKerusakan->status_denda,'class'=>'bg-sand text-dim border-rule'];
@endphp

{{-- Page Header --}}
<div class="mb-8 flex items-end justify-between">
    <div>
        <p class="font-sans text-[0.55rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">History Kerusakan</p>
        <h1 class="font-serif text-ink text-3xl font-normal leading-none">Detail Kerusakan</h1>
        <div class="mt-3 h-px w-10 bg-rule"></div>
    </div>
    <span class="font-sans text-[0.52rem] tracking-[0.18em] uppercase px-3 py-1.5 border {{ $st['class'] }}">
        {{ $st['label'] }}
    </span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

    {{-- Kolom Kiri --}}
    <div class="lg:col-span-3 space-y-5">

        {{-- Info Utama --}}
        <div class="bg-paper border border-rule">
            <div class="border-b border-rule px-5 py-4">
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Detail</p>
                <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Informasi Kerusakan</h2>
            </div>
            <div class="px-5 py-5 space-y-4">
                @php
                    $rows = [
                        ['label'=>'Alat',            'value'=> $historyKerusakan->nama_alat_snapshot],
                        ['label'=>'Kode Alat',        'value'=> null, 'code'=> $historyKerusakan->kode_alat_snapshot],
                        ['label'=>'Tanggal Rusak',    'value'=> $historyKerusakan->tanggal_rusak->format('d/m/Y H:i')],
                        ['label'=>'Kondisi Sebelumnya','value'=> ucfirst(str_replace('_',' ',$historyKerusakan->kondisi_sebelum))],
                        ['label'=>'Dicatat Oleh',     'value'=> $historyKerusakan->dicatatOleh?->name ?? '—'],
                    ];
                @endphp
                @foreach($rows as $row)
                <div class="flex items-start gap-4">
                    <span class="font-sans text-[0.52rem] tracking-[0.15em] uppercase text-ghost w-36 flex-shrink-0 pt-0.5">{{ $row['label'] }}</span>
                    @if(isset($row['code']))
                        <code class="font-mono text-[0.72rem] text-dim bg-cream px-2 py-0.5">{{ $row['code'] }}</code>
                    @else
                        <span class="font-sans text-[0.82rem] text-ink">{{ $row['value'] }}</span>
                    @endif
                </div>
                @endforeach

                <div class="flex items-start gap-4">
                    <span class="font-sans text-[0.52rem] tracking-[0.15em] uppercase text-ghost w-36 flex-shrink-0 pt-0.5">Jenis Kerusakan</span>
                    <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border {{ $jn['class'] }}">{{ $jn['label'] }}</span>
                </div>

                @if($historyKerusakan->nama_peminjam)
                <div class="flex items-start gap-4">
                    <span class="font-sans text-[0.52rem] tracking-[0.15em] uppercase text-ghost w-36 flex-shrink-0 pt-0.5">Penanggung Jawab</span>
                    <div>
                        <p class="font-sans text-[0.82rem] text-ink">{{ $historyKerusakan->nama_peminjam }}</p>
                        @if($historyKerusakan->kelas)
                            <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-cream text-label border-rule mt-1 inline-block">
                                {{ $historyKerusakan->kelas }}
                            </span>
                        @endif
                    </div>
                </div>
                @endif

                <div class="pt-3 border-t border-rule/50">
                    <p class="font-sans text-[0.52rem] tracking-[0.15em] uppercase text-ghost mb-2">Deskripsi Kerusakan</p>
                    <p class="font-sans text-[0.82rem] text-dim leading-relaxed">{{ $historyKerusakan->deskripsi_kerusakan }}</p>
                </div>
            </div>
        </div>

        {{-- Foto --}}
        @if($historyKerusakan->foto_kerusakan && count($historyKerusakan->foto_kerusakan))
        <div class="bg-paper border border-rule">
            <div class="border-b border-rule px-5 py-4">
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Media</p>
                <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Foto Kerusakan</h2>
            </div>
            <div class="px-5 py-5 grid grid-cols-3 gap-3">
                @foreach($historyKerusakan->foto_kerusakan as $foto)
                <a href="{{ Storage::url($foto) }}" target="_blank" class="block overflow-hidden border border-rule hover:border-espresso transition-colors">
                    <img src="{{ Storage::url($foto) }}"
                         class="w-full h-24 object-cover"
                         alt="Foto kerusakan">
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Link ke Pengembalian --}}
        @if($historyKerusakan->pengembalian)
        <div class="bg-paper border border-rule">
            <div class="border-b border-rule px-5 py-4">
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Referensi</p>
                <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Dari Transaksi Pengembalian</h2>
            </div>
            <div class="px-5 py-5 space-y-3">
                @foreach([
                    ['label'=>'Kode Pengembalian','code'=>$historyKerusakan->pengembalian->kode_pengembalian],
                    ['label'=>'Waktu Kembali',    'value'=>$historyKerusakan->pengembalian->waktu_kembali->format('d/m/Y H:i')],
                ] as $row)
                <div class="flex items-center gap-4">
                    <span class="font-sans text-[0.52rem] tracking-[0.15em] uppercase text-ghost w-36 flex-shrink-0">{{ $row['label'] }}</span>
                    @if(isset($row['code']))
                        <code class="font-mono text-[0.72rem] text-dim bg-cream px-2 py-0.5">{{ $row['code'] }}</code>
                    @else
                        <span class="font-sans text-[0.82rem] text-ink">{{ $row['value'] }}</span>
                    @endif
                </div>
                @endforeach
                <div class="flex items-center gap-4">
                    <span class="font-sans text-[0.52rem] tracking-[0.15em] uppercase text-ghost w-36 flex-shrink-0">Kode Transaksi</span>
                    <a href="{{ route('admin.peminjaman.show', $historyKerusakan->pengembalian->peminjaman_id) }}"
                       class="font-sans text-[0.78rem] text-espresso underline underline-offset-2 hover:text-ink">
                        {{ $historyKerusakan->pengembalian->peminjaman->kode_transaksi ?? '—' }}
                    </a>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- Kolom Kanan --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Status Denda --}}
        <div class="bg-paper border border-rule">
            <div class="border-b border-rule px-5 py-4">
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Keuangan</p>
                <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Status Denda</h2>
            </div>
            <div class="px-5 py-5">
                @if($historyKerusakan->jumlah_denda > 0)
                    <div class="flex items-center justify-between mb-4">
                        <span class="font-sans text-[0.58rem] tracking-[0.15em] uppercase text-ghost">Jumlah Denda</span>
                        <span class="font-serif text-2xl text-red-800 font-normal">
                            Rp {{ number_format($historyKerusakan->jumlah_denda, 0, ',', '.') }}
                        </span>
                    </div>

                    <span class="w-full block text-center font-sans text-[0.5rem] tracking-[0.15em] uppercase px-3 py-2 border {{ $dn['class'] }} mb-4">
                        {{ $dn['label'] }}
                    </span>

                    @if($historyKerusakan->tanggal_lunas)
                        <p class="font-sans text-[0.6rem] tracking-wide text-ghost text-center mb-4">
                            Lunas pada {{ $historyKerusakan->tanggal_lunas->format('d/m/Y H:i') }}
                        </p>
                    @endif

                    @if($historyKerusakan->status_denda !== 'lunas')
                    <div class="border-t border-rule/60 pt-4">
                        <form method="POST" action="{{ route('admin.history-kerusakan.denda', $historyKerusakan->id) }}">
                            @csrf
                            <input type="hidden" name="status_denda" value="lunas">
                            <button type="submit"
                                    onclick="return confirm('Tandai denda sebagai sudah lunas?')"
                                    class="w-full flex items-center justify-center gap-2
                                           bg-emerald-800 text-paper py-3
                                           font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase
                                           hover:bg-emerald-900 transition-colors">
                                <i class="fas fa-check text-[0.5rem]"></i> Tandai Lunas
                            </button>
                        </form>
                    </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle text-rule text-2xl block mb-2"></i>
                        <p class="font-sans text-[0.62rem] tracking-[0.15em] uppercase text-ghost">Tidak ada denda</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Update Tindak Lanjut --}}
        <div class="bg-paper border border-rule">
            <div class="border-b border-rule px-5 py-4">
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Aksi</p>
                <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Update Tindak Lanjut</h2>
            </div>
            <div class="px-5 py-5">

                @if($historyKerusakan->catatan_tindak_lanjut)
                <div class="border-l-2 border-rule bg-cream/50 px-4 py-3 mb-5">
                    <p class="font-sans text-[0.5rem] tracking-[0.15em] uppercase text-ghost mb-1.5">Catatan sebelumnya</p>
                    <p class="font-sans text-[0.72rem] text-dim leading-relaxed">{{ $historyKerusakan->catatan_tindak_lanjut }}</p>
                    @if($historyKerusakan->biaya_perbaikan > 0)
                        <p class="font-sans text-[0.65rem] text-label mt-2">
                            Biaya: <span class="font-semibold text-ink">Rp {{ number_format($historyKerusakan->biaya_perbaikan, 0, ',', '.') }}</span>
                        </p>
                    @endif
                    @if($historyKerusakan->tanggal_selesai_perbaikan)
                        <p class="font-sans text-[0.65rem] text-label mt-1">
                            Selesai: {{ $historyKerusakan->tanggal_selesai_perbaikan->format('d/m/Y') }}
                        </p>
                    @endif
                </div>
                @endif

                <form method="POST"
                      action="{{ route('admin.history-kerusakan.tindak-lanjut', $historyKerusakan->id) }}"
                      class="space-y-5">
                    @csrf

                    <div>
                        <label class="block font-sans text-[0.52rem] font-semibold tracking-[0.25em] uppercase text-label mb-2.5">
                            Status Tindak Lanjut
                        </label>
                        <select name="status_tindak_lanjut" id="selectStatus"
                                class="w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                       font-sans text-[0.82rem] text-ink outline-none focus:border-ink transition-colors">
                            @foreach([
                                'menunggu'         => 'Menunggu Tindakan',
                                'diperbaiki'       => 'Sedang Diperbaiki',
                                'sudah_diperbaiki' => 'Sudah Diperbaiki',
                                'diganti_baru'     => 'Diganti Baru',
                                'dihapuskan'       => 'Dihapuskan dari Inventaris',
                            ] as $val => $lbl)
                            <option value="{{ $val }}"
                                {{ $historyKerusakan->status_tindak_lanjut === $val ? 'selected':'' }}>
                                {{ $lbl }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="fieldsPerbaikan" class="space-y-5">
                        <div>
                            <label class="block font-sans text-[0.52rem] font-semibold tracking-[0.25em] uppercase text-label mb-2.5">
                                Biaya Perbaikan
                            </label>
                            <div class="flex items-end gap-0">
                                <span class="pb-2.5 pt-1 font-sans text-[0.82rem] text-ghost border-b border-rule pr-2">Rp</span>
                                <input type="number" name="biaya_perbaikan"
                                       value="{{ $historyKerusakan->biaya_perbaikan }}" min="0"
                                       class="flex-1 border-b border-rule bg-transparent pb-2.5 pt-1
                                              font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors">
                            </div>
                        </div>
                        <div>
                            <label class="block font-sans text-[0.52rem] font-semibold tracking-[0.25em] uppercase text-label mb-2.5">
                                Tanggal Selesai
                            </label>
                            <input type="date" name="tanggal_selesai_perbaikan"
                                   value="{{ $historyKerusakan->tanggal_selesai_perbaikan?->format('Y-m-d') }}"
                                   class="w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                          font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block font-sans text-[0.52rem] font-semibold tracking-[0.25em] uppercase text-label mb-2.5">
                            Catatan
                        </label>
                        <textarea name="catatan_tindak_lanjut" rows="3"
                                  placeholder="Catatan tindak lanjut..."
                                  class="w-full border-b border-rule bg-transparent pb-2 pt-1
                                         font-sans text-[0.85rem] text-ink outline-none
                                         placeholder-ghost focus:border-ink resize-none transition-colors">{{ $historyKerusakan->catatan_tindak_lanjut }}</textarea>
                    </div>

                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2
                               bg-espresso text-paper py-3
                               font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase
                               hover:bg-ink transition-colors active:scale-[0.99]">
                        <i class="fas fa-check text-[0.5rem]"></i> Simpan Update
                    </button>
                </form>
            </div>
        </div>

        <a href="{{ route('admin.history-kerusakan.index') }}"
           class="flex items-center justify-center gap-2 w-full border border-rule text-label py-3
                  font-sans text-[0.58rem] font-semibold tracking-[0.22em] uppercase
                  hover:bg-sand transition-colors">
            <i class="fas fa-arrow-left text-[0.5rem]"></i> Kembali ke Daftar
        </a>
    </div>

</div>

@endsection

@section('scripts')
<script>
    const selectStatus    = document.getElementById('selectStatus');
    const fieldsPerbaikan = document.getElementById('fieldsPerbaikan');

    function toggleFields() {
        const show = ['diperbaiki','sudah_diperbaiki','diganti_baru'].includes(selectStatus.value);
        fieldsPerbaikan.style.display = show ? 'block' : 'none';
    }
    selectStatus.addEventListener('change', toggleFields);
    toggleFields();
</script>
@endsection
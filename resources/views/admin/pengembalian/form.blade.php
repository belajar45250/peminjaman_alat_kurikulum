{{-- resources/views/admin/pengembalian/form.blade.php --}}
@extends('layouts.app')
@section('title', 'Konfirmasi Pengembalian')

@section('content')

<div class="mb-8">
    <p class="font-sans text-[0.55rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">Pengembalian</p>
    <h1 class="font-serif text-ink text-3xl font-normal leading-none">Konfirmasi Pengembalian</h1>
    <div class="mt-3 h-px w-10 bg-rule"></div>
</div>

<div class="max-w-2xl mx-auto space-y-5">

    {{-- Info Peminjaman --}}
    <div class="bg-paper border border-rule">
        <div class="border-b border-rule px-5 py-4 flex items-center gap-3">
            <div class="w-8 h-8 bg-amber-50 border border-amber-200 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-info text-amber-700 text-[0.55rem]"></i>
            </div>
            <div>
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Aktif</p>
                <h2 class="font-serif text-ink text-lg font-normal mt-0">Data Peminjaman Aktif</h2>
            </div>
        </div>
        <div class="px-5 py-5 grid grid-cols-2 gap-4">
            @foreach([
                ['label'=>'Alat',           'value'=>$alat->nama_alat, 'sub'=>$alat->kode_alat],
                ['label'=>'Kode Transaksi', 'value'=>$peminjaman->kode_transaksi],
                ['label'=>'Peminjam',       'value'=>$peminjaman->nama_peminjam],
                ['label'=>'Kelas',          'value'=>$peminjaman->kelas],
                ['label'=>'Mata Pelajaran', 'value'=>$peminjaman->mata_pelajaran],
                ['label'=>'Waktu Pinjam',   'value'=>$peminjaman->waktu_pinjam->format('d/m/Y H:i'), 'late'=>$peminjaman->terlambat],
            ] as $row)
            <div>
                <p class="font-sans text-[0.5rem] tracking-[0.15em] uppercase text-ghost mb-1">{{ $row['label'] }}</p>
                <p class="font-sans text-[0.82rem] text-ink font-medium leading-tight">{{ $row['value'] }}</p>
                @if(isset($row['sub']))
                    <code class="font-mono text-[0.62rem] text-ghost bg-cream px-1 py-0.5 mt-0.5 inline-block">{{ $row['sub'] }}</code>
                @endif
                @if($row['late'] ?? false)
                    <span class="font-sans text-[0.48rem] tracking-[0.1em] uppercase px-2 py-0.5 border bg-red-50 text-red-800 border-red-200 mt-1 inline-block">Terlambat</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- Form Pengembalian --}}
    <div class="bg-paper border border-rule">
        <div class="border-b border-rule px-5 py-4">
            <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Formulir</p>
            <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Kondisi Pengembalian</h2>
        </div>
        <div class="px-5 py-6">
            <form method="POST" action="{{ route('admin.pengembalian.proses') }}">
                @csrf
                <input type="hidden" name="qr_hash" value="{{ $qrHash }}">
                <input type="hidden" name="peminjaman_id" value="{{ $peminjaman->id }}">

                {{-- Pilih Kondisi --}}
                <div class="mb-6">
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-4">
                        Kondisi Alat Saat Dikembalikan <span class="text-red-700">*</span>
                    </label>
                    @php
                        $kondisiList = [
                            'baik'        => ['label'=>'Baik',        'fa'=>'fa-circle-check',     'bg'=>'hover:border-emerald-400 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50'],
                            'rusak_ringan' => ['label'=>'Rusak Ringan','fa'=>'fa-circle-exclamation','bg'=>'hover:border-amber-400  has-[:checked]:border-amber-500  has-[:checked]:bg-amber-50'],
                            'rusak_berat'  => ['label'=>'Rusak Berat', 'fa'=>'fa-circle-xmark',     'bg'=>'hover:border-red-400    has-[:checked]:border-red-500    has-[:checked]:bg-red-50'],
                            'hilang'       => ['label'=>'Hilang',      'fa'=>'fa-circle-question',  'bg'=>'hover:border-espresso   has-[:checked]:border-espresso   has-[:checked]:bg-cream'],
                        ];
                    @endphp
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($kondisiList as $val => $k)
                        <label class="relative flex flex-col items-center gap-2 border border-rule p-4 cursor-pointer transition-all {{ $k['bg'] }}">
                            <input type="radio" name="kondisi_kembali" value="{{ $val }}"
                                   {{ old('kondisi_kembali','baik') === $val ? 'checked':'' }}
                                   class="absolute opacity-0">
                            <i class="fas {{ $k['fa'] }} text-2xl text-ghost"></i>
                            <span class="font-sans text-[0.6rem] font-semibold tracking-[0.15em] uppercase text-dim">{{ $k['label'] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Preview Denda --}}
                <div id="preview-denda" class="hidden mb-5 border-l-2 border-amber-400 bg-amber-50/50 px-4 py-3">
                    <p class="font-sans text-[0.62rem] tracking-wide text-amber-800">
                        <i class="fas fa-calculator mr-1.5 text-[0.55rem]"></i>
                        <span class="font-semibold">Estimasi Denda:</span>
                        <span id="estimasi-denda" class="font-semibold ml-1"></span>
                    </p>
                </div>

                {{-- Catatan --}}
                <div class="mb-6">
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">Catatan</label>
                    <textarea name="catatan" rows="2"
                              placeholder="Catatan kondisi alat (opsional)..."
                              class="w-full border-b border-rule bg-transparent pb-2 pt-1
                                     font-sans text-[0.88rem] text-ink outline-none placeholder-ghost
                                     focus:border-ink resize-none transition-colors">{{ old('catatan') }}</textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 bg-espresso text-paper py-3.5
                               font-sans text-[0.6rem] font-semibold tracking-[0.28em] uppercase
                               hover:bg-ink transition-colors active:scale-[0.99]">
                        <i class="fas fa-check-double text-[0.5rem]"></i> Proses Pengembalian
                    </button>
                    <a href="{{ route('admin.pengembalian.index') }}"
                       class="flex items-center gap-2 border border-rule text-label px-5 py-3.5
                              font-sans text-[0.6rem] font-semibold tracking-[0.28em] uppercase
                              hover:bg-sand transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    const harga       = {{ $alat->harga }};
    const persenRusak = {{ \App\Models\Pengaturan::ambil('persentase_denda_rusak', 30) }};
    const persenHilang= {{ \App\Models\Pengaturan::ambil('persentase_denda_hilang', 100) }};
    const previewEl   = document.getElementById('preview-denda');
    const estimasiEl  = document.getElementById('estimasi-denda');

    document.querySelectorAll('input[name="kondisi_kembali"]').forEach(radio => {
        radio.addEventListener('change', function() {
            let denda = 0;
            if (this.value === 'rusak_berat') denda = harga * (persenRusak  / 100);
            if (this.value === 'hilang')      denda = harga * (persenHilang / 100);

            if (denda > 0) {
                previewEl.classList.remove('hidden');
                estimasiEl.textContent = 'Rp ' + Math.round(denda).toLocaleString('id-ID');
            } else {
                previewEl.classList.add('hidden');
            }
        });
    });
</script>
@endsection
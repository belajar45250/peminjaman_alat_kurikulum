{{-- resources/views/publik/form-multi.blade.php --}}
@extends('layouts.guest')
@section('title', 'Form Peminjaman — ' . count($alatList) . ' Alat')

@section('content')
@php
    $jamPelajaran = $jamPelajaran ?? \App\Models\Pengaturan::getJamPelajaran();
    $kelasList    = $kelasList    ?? \App\Models\Pengaturan::getDaftarKelas();
@endphp

<div class="min-h-screen bg-cream flex items-start justify-center pt-16 pb-10 px-4">
    <div class="w-full max-w-5xl">

        {{-- Page Header --}}
        <div class="mb-8">
            <p class="font-sans text-[0.55rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">Transaksi</p>
            <h1 class="font-serif text-ink text-3xl font-normal leading-none">Formulir Peminjaman</h1>
            <div class="mt-3 h-px w-10 bg-rule"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- ── Form ── --}}
            <div class="lg:col-span-3 bg-paper border border-rule">

                <div class="border-b border-rule px-6 py-4">
                    <p class="font-sans text-[0.52rem] font-semibold tracking-[0.3em] uppercase text-label">Formulir</p>
                    <h2 class="font-serif text-ink text-xl font-normal mt-0.5">Data Peminjaman</h2>
                </div>

                {{-- Daftar Alat yang Dipinjam --}}
                <div class="border-b border-rule/60 px-6 py-4 bg-cream/40">
                    <p class="font-sans text-[0.5rem] font-semibold tracking-[0.25em] uppercase text-ghost mb-3">
                        {{ count($alatList) }} Alat Dipilih
                    </p>
                    <div class="space-y-2">
                        @foreach($alatList as $i => $item)
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-espresso flex items-center justify-center flex-shrink-0">
                                <span class="font-sans text-[0.48rem] font-bold text-paper">{{ $i + 1 }}</span>
                            </div>
                            <div class="flex-1">
                                <p class="font-serif text-ink text-[0.88rem] leading-tight">{{ $item['alat']->nama_alat }}</p>
                                <p class="font-sans text-[0.55rem] tracking-[0.12em] uppercase text-label mt-0.5">
                                    {{ $item['alat']->kode_alat }}
                                    @if($item['alat']->nomor_urut)
                                        <span class="ml-1 text-ghost">#{{ $item['alat']->nomor_urut }}</span>
                                    @endif
                                </p>
                            </div>
                            <span class="font-sans text-[0.48rem] tracking-[0.15em] uppercase px-2 py-0.5
                                         border bg-emerald-50 text-emerald-800 border-emerald-200">
                                Tersedia
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <form method="POST" action="{{ route('publik.submit-multi') }}" class="px-6 py-6 space-y-6">
                    @csrf
                    <input type="hidden" name="qr_hashes" value="{{ $hashes }}">

                    @if(session('error'))
                    <div class="border-l-2 border-red-800 bg-red-50/50 px-4 py-3">
                        <p class="font-sans text-[0.7rem] tracking-wide text-red-900">{{ session('error') }}</p>
                    </div>
                    @endif

                    {{-- Nama --}}
                    <div>
                        <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                            Nama Lengkap <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="nama_peminjam"
                                   value="{{ old('nama_peminjam') }}"
                                   placeholder="Masukkan nama lengkap"
                                   autocomplete="name" autofocus required
                                   class="peer w-full border-b {{ $errors->has('nama_peminjam') ? 'border-red-500' : 'border-rule' }}
                                          bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] tracking-wide text-ink
                                          outline-none placeholder-ghost transition-colors duration-200 focus:border-ink">
                            <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                        </div>
                        @error('nama_peminjam')
                            <p class="mt-1.5 font-sans text-[0.6rem] tracking-wide text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kelas --}}
                    <div>
                        <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                            Kelas <span class="text-red-600">*</span>
                        </label>
                        <select name="kelas" required
                                class="w-full border-b {{ $errors->has('kelas') ? 'border-red-500' : 'border-rule' }}
                                       bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] tracking-wide text-ink
                                       outline-none focus:border-ink transition-colors duration-200">
                            <option value="" disabled {{ old('kelas') ? '' : 'selected' }}>— Pilih Kelas —</option>
                            @foreach($kelasList as $tingkat => $daftarKelas)
                                <optgroup label="Kelas {{ $tingkat + 1 }}">
                                    @foreach($daftarKelas as $kelas)
                                        <option value="{{ $kelas }}" {{ old('kelas') === $kelas ? 'selected' : '' }}>
                                            {{ $kelas }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('kelas')
                            <p class="mt-1.5 font-sans text-[0.6rem] tracking-wide text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mata Pelajaran --}}
                    <div>
                        <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                            Mata Pelajaran <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="mata_pelajaran"
                                   value="{{ old('mata_pelajaran') }}"
                                   placeholder="Contoh: Pemrograman Dasar"
                                   required
                                   class="peer w-full border-b {{ $errors->has('mata_pelajaran') ? 'border-red-500' : 'border-rule' }}
                                          bg-transparent pb-2.5 pt-1 font-sans text-[0.88rem] tracking-wide text-ink
                                          outline-none placeholder-ghost transition-colors duration-200 focus:border-ink">
                            <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                        </div>
                        @error('mata_pelajaran')
                            <p class="mt-1.5 font-sans text-[0.6rem] tracking-wide text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Guru Pengampu --}}
                    <div>
                        <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                            Guru Pengampu
                        </label>
                        <div class="relative">
                            <input type="text" name="guru_pengampu"
                                   value="{{ old('guru_pengampu') }}"
                                   placeholder="Nama guru yang mengajar"
                                   class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                          font-sans text-[0.88rem] tracking-wide text-ink outline-none
                                          placeholder-ghost transition-colors duration-200 focus:border-ink">
                            <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                        </div>
                    </div>

                    {{-- Jam Pelajaran --}}
                    <div>
                        <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                            Jam Pelajaran <span class="text-red-600">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="font-sans text-[0.5rem] tracking-[0.2em] uppercase text-ghost mb-2">Mulai</p>
                                <select name="jam_pelajaran_mulai" id="jamMulai" required
                                        class="w-full border-b {{ $errors->has('jam_pelajaran_mulai') ? 'border-red-500' : 'border-rule' }}
                                               bg-transparent pb-2.5 pt-1 font-sans text-[0.82rem] text-ink
                                               outline-none focus:border-ink transition-colors">
                                    <option value="" disabled {{ old('jam_pelajaran_mulai') ? '' : 'selected' }}>— Jam Mulai —</option>
                                    @foreach($jamPelajaran as $ke => $jam)
                                        <option value="{{ $ke }}"
                                                {{ (int) old('jam_pelajaran_mulai') === $ke ? 'selected' : '' }}>
                                            Jam ke-{{ $ke }} ({{ $jam['mulai'] }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('jam_pelajaran_mulai')
                                    <p class="mt-1.5 font-sans text-[0.6rem] text-red-700">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <p class="font-sans text-[0.5rem] tracking-[0.2em] uppercase text-ghost mb-2">Selesai</p>
                                <select name="jam_pelajaran_selesai" id="jamSelesai" required
                                        class="w-full border-b {{ $errors->has('jam_pelajaran_selesai') ? 'border-red-500' : 'border-rule' }}
                                               bg-transparent pb-2.5 pt-1 font-sans text-[0.82rem] text-ink
                                               outline-none focus:border-ink transition-colors">
                                    <option value="" disabled {{ old('jam_pelajaran_selesai') ? '' : 'selected' }}>— Jam Selesai —</option>
                                    @foreach($jamPelajaran as $ke => $jam)
                                        <option value="{{ $ke }}"
                                                {{ (int) old('jam_pelajaran_selesai') === $ke ? 'selected' : '' }}>
                                            Jam ke-{{ $ke }} ({{ $jam['selesai'] }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('jam_pelajaran_selesai')
                                    <p class="mt-1.5 font-sans text-[0.6rem] text-red-700">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Preview jam --}}
                        <div id="previewJam" class="hidden mt-3 border-l-2 border-rule px-3 py-2">
                            <p id="previewJamTeks" class="font-sans text-[0.65rem] tracking-wide text-label"></p>
                        </div>
                    </div>

                    {{-- Keperluan --}}
                    <div>
                        <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                            Keperluan
                        </label>
                        <textarea name="keperluan" rows="2"
                                  placeholder="Untuk apa alat-alat ini dipinjam?"
                                  class="w-full border-b border-rule bg-transparent pb-2 pt-1
                                         font-sans text-[0.88rem] tracking-wide text-ink outline-none
                                         placeholder-ghost focus:border-ink resize-none transition-colors">{{ old('keperluan') }}</textarea>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-2">
                        <button type="submit"
                            class="relative w-full overflow-hidden bg-espresso px-6 py-4
                                   font-sans text-[0.6rem] font-semibold tracking-[0.35em] uppercase text-paper
                                   hover:bg-ink active:scale-[0.99] transition-colors duration-200
                                   after:content-[''] after:absolute after:inset-0 after:bg-white/[0.06]
                                   after:-translate-x-full after:transition-transform after:duration-[350ms]
                                   hover:after:translate-x-0">
                            <i class="fas fa-check mr-2 text-[0.55rem]"></i>
                            Pinjam {{ count($alatList) }} Alat Sekarang
                        </button>

                        <a href="{{ route('home') }}"
                           class="mt-4 flex items-center justify-center gap-2
                                  font-sans text-[0.55rem] tracking-[0.2em] uppercase text-label
                                  hover:text-ink transition-colors">
                            <i class="fas fa-arrow-left text-[0.5rem]"></i>
                            Kembali ke Halaman Utama
                        </a>
                    </div>

                </form>
            </div>

            {{-- ── Info Panel ── --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- Ringkasan Alat --}}
                <div class="bg-paper border border-rule p-6 relative overflow-hidden">
                    <div class="pointer-events-none absolute top-4 right-4 h-8 w-8 border-t border-r border-rule"></div>

                    <p class="font-sans text-[0.52rem] font-semibold tracking-[0.3em] uppercase text-label mb-3">Ringkasan</p>
                    <h3 class="font-serif text-ink text-lg font-normal mb-3">{{ count($alatList) }} Alat Dipilih</h3>
                    <div class="h-px w-8 bg-rule mb-5"></div>

                    <div class="space-y-3">
                        @foreach($alatList as $i => $item)
                        <div class="flex items-start gap-2.5">
                            <span class="w-5 h-5 bg-espresso text-paper flex items-center justify-center
                                         font-sans text-[0.45rem] font-bold flex-shrink-0 mt-0.5">
                                {{ $i + 1 }}
                            </span>
                            <div>
                                <p class="font-sans text-[0.72rem] text-ink font-medium leading-tight">
                                    {{ $item['alat']->nama_alat }}
                                    @if($item['alat']->nomor_urut)
                                        <span class="text-ghost font-normal">#{{ $item['alat']->nomor_urut }}</span>
                                    @endif
                                </p>
                                <code class="font-mono text-[0.6rem] text-ghost">{{ $item['alat']->kode_alat }}</code>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Petunjuk --}}
                <div class="bg-paper border border-rule p-6">
                    <p class="font-sans text-[0.52rem] font-semibold tracking-[0.3em] uppercase text-label mb-3">Petunjuk</p>
                    <h3 class="font-serif text-ink text-lg font-normal mb-3">Informasi Peminjaman</h3>
                    <div class="h-px w-8 bg-rule mb-5"></div>
                    <div class="space-y-4">
                        @foreach([
                            ['icon'=>'fa-check',               'bg'=>'bg-espresso','iconColor'=>'text-paper','title'=>'Satu Form',      'desc'=>'Isi satu form untuk semua alat sekaligus.'],
                            ['icon'=>'fa-clock',               'bg'=>'bg-rule',    'iconColor'=>'text-ink',  'title'=>'Pengembalian',   'desc'=>'Kembalikan semua alat ke petugas sesuai jam selesai.'],
                            ['icon'=>'fa-triangle-exclamation','bg'=>'bg-rule',    'iconColor'=>'text-ink',  'title'=>'Tanggung Jawab', 'desc'=>'Peminjam bertanggung jawab atas semua alat.'],
                        ] as $info)
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 {{ $info['bg'] }} flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas {{ $info['icon'] }} {{ $info['iconColor'] }} text-[0.4rem]"></i>
                            </div>
                            <div>
                                <p class="font-sans text-[0.62rem] font-semibold tracking-wide text-ink mb-1">{{ $info['title'] }}</p>
                                <p class="font-sans text-[0.65rem] leading-relaxed text-label">{{ $info['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const jamData     = @json($jamPelajaran);
    const jamMulai    = document.getElementById('jamMulai');
    const jamSelesai  = document.getElementById('jamSelesai');
    const preview     = document.getElementById('previewJam');
    const previewTeks = document.getElementById('previewJamTeks');

    function updatePreview() {
        const m = jamMulai.value;
        const s = jamSelesai.value;

        if (!m || !s) { preview.classList.add('hidden'); return; }
        preview.classList.remove('hidden');

        if (parseInt(s) < parseInt(m)) {
            preview.className = 'mt-3 border-l-2 border-red-300 bg-red-50/50 px-3 py-2';
            previewTeks.textContent = '⚠ Jam selesai tidak boleh sebelum jam mulai!';
            previewTeks.className   = 'font-sans text-[0.65rem] tracking-wide text-red-700';
        } else {
            preview.className = 'mt-3 border-l-2 border-rule px-3 py-2';
            previewTeks.className   = 'font-sans text-[0.65rem] tracking-wide text-label';
            previewTeks.textContent =
                `Pinjam jam ke-${m} (${jamData[m].mulai}) s/d jam ke-${s} (${jamData[s].selesai}) — Estimasi kembali: ${jamData[s].selesai}`;
        }
    }

    jamMulai.addEventListener('change', function () {
        const v = parseInt(this.value);
        Array.from(jamSelesai.options).forEach(opt => {
            if (opt.value === '') return;
            opt.disabled = parseInt(opt.value) < v;
        });
        if (parseInt(jamSelesai.value) < v) jamSelesai.value = '';
        updatePreview();
    });

    jamSelesai.addEventListener('change', updatePreview);
    if (jamMulai.value) jamMulai.dispatchEvent(new Event('change'));
</script>
@endsection
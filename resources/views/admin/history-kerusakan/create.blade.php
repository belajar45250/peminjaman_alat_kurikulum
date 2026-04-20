{{-- resources/views/admin/history-kerusakan/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Catat Kerusakan Manual')

@section('content')

<div class="mb-8">
    <p class="font-sans text-[0.55rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">History Kerusakan</p>
    <h1 class="font-serif text-ink text-3xl font-normal leading-none">Catat Kerusakan Manual</h1>
    <div class="mt-3 h-px w-10 bg-rule"></div>
</div>

<div class="max-w-2xl">

    {{-- Info --}}
    <div class="border-l-2 border-rule bg-paper px-4 py-3 mb-6">
        <p class="font-sans text-[0.7rem] tracking-wide text-dim leading-relaxed">
            <i class="fas fa-info-circle text-ghost mr-1.5"></i>
            Gunakan form ini untuk mencatat kerusakan yang tidak melalui proses pengembalian,
            misalnya kerusakan yang ditemukan saat pengecekan inventaris.
        </p>
    </div>

    <div class="bg-paper border border-rule">
        <div class="border-b border-rule px-6 py-4 flex items-center justify-between">
            <div>
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Formulir</p>
                <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Data Kerusakan</h2>
            </div>
            <a href="{{ route('admin.history-kerusakan.index') }}"
               class="flex items-center gap-2 border border-rule text-label px-3 py-2
                      font-sans text-[0.55rem] font-semibold tracking-[0.2em] uppercase
                      hover:bg-sand transition-colors">
                <i class="fas fa-arrow-left text-[0.5rem]"></i> Kembali
            </a>
        </div>

        <form method="POST"
              action="{{ route('admin.history-kerusakan.store') }}"
              enctype="multipart/form-data"
              class="px-6 py-6 space-y-6">
            @csrf

            {{-- Pilih Alat --}}
            <div>
                <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                    Pilih Alat <span class="text-red-700">*</span>
                </label>
                <select name="alat_id"
                        class="w-full border-b {{ $errors->has('alat_id') ? 'border-red-600' : 'border-rule' }} bg-transparent pb-2.5 pt-1
                               font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors"
                        required>
                    <option value="" disabled selected>— Pilih Alat —</option>
                    @foreach($alat as $item)
                        <option value="{{ $item->id }}" {{ old('alat_id') == $item->id ? 'selected':'' }}>
                            {{ $item->nama_alat }} ({{ $item->kode_alat }})
                        </option>
                    @endforeach
                </select>
                @error('alat_id')
                    <p class="mt-1.5 font-sans text-[0.6rem] tracking-wide text-red-700">{{ $message }}</p>
                @enderror
            </div>

            {{-- Penanggung Jawab --}}
            <div class="grid grid-cols-1 md:grid-cols-5 gap-5">
                <div class="md:col-span-3">
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                        Nama Penanggung Jawab
                    </label>
                    <div class="relative">
                        <input type="text" name="nama_peminjam"
                               value="{{ old('nama_peminjam') }}"
                               placeholder="Kosongkan jika tidak diketahui"
                               class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                      font-sans text-[0.88rem] tracking-wide text-ink outline-none
                                      placeholder-ghost transition-colors duration-200 focus:border-ink">
                        <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                        Kelas
                    </label>
                    <div class="relative">
                        <input type="text" name="kelas"
                               value="{{ old('kelas') }}"
                               placeholder="Misal: X RPL 1"
                               class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                      font-sans text-[0.88rem] tracking-wide text-ink outline-none
                                      placeholder-ghost transition-colors duration-200 focus:border-ink">
                        <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                    </div>
                </div>
            </div>

            {{-- Jenis & Kondisi --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                        Jenis Kerusakan <span class="text-red-700">*</span>
                    </label>
                    <select name="jenis_kerusakan"
                            class="w-full border-b {{ $errors->has('jenis_kerusakan') ? 'border-red-600' : 'border-rule' }} bg-transparent pb-2.5 pt-1
                                   font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors"
                            required>
                        <option value="rusak_ringan" {{ old('jenis_kerusakan') === 'rusak_ringan' ? 'selected':'' }}>Rusak Ringan</option>
                        <option value="rusak_berat"  {{ old('jenis_kerusakan') === 'rusak_berat'  ? 'selected':'' }}>Rusak Berat</option>
                        <option value="hilang"       {{ old('jenis_kerusakan') === 'hilang'       ? 'selected':'' }}>Hilang</option>
                    </select>
                    @error('jenis_kerusakan')
                        <p class="mt-1.5 font-sans text-[0.6rem] tracking-wide text-red-700">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                        Kondisi Sebelum Rusak <span class="text-red-700">*</span>
                    </label>
                    <select name="kondisi_sebelum"
                            class="w-full border-b {{ $errors->has('kondisi_sebelum') ? 'border-red-600' : 'border-rule' }} bg-transparent pb-2.5 pt-1
                                   font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors"
                            required>
                        <option value="baik"         {{ old('kondisi_sebelum') === 'baik'         ? 'selected':'' }}>Baik</option>
                        <option value="rusak_ringan"  {{ old('kondisi_sebelum') === 'rusak_ringan'  ? 'selected':'' }}>Rusak Ringan</option>
                    </select>
                    @error('kondisi_sebelum')
                        <p class="mt-1.5 font-sans text-[0.6rem] tracking-wide text-red-700">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                    Deskripsi Kerusakan <span class="text-red-700">*</span>
                </label>
                <textarea name="deskripsi_kerusakan" rows="4"
                          placeholder="Jelaskan detail kerusakannya..."
                          class="w-full border-b {{ $errors->has('deskripsi_kerusakan') ? 'border-red-600' : 'border-rule' }} bg-transparent pb-2 pt-1
                                 font-sans text-[0.88rem] tracking-wide text-ink outline-none
                                 placeholder-ghost focus:border-ink resize-none transition-colors"
                          required>{{ old('deskripsi_kerusakan') }}</textarea>
                @error('deskripsi_kerusakan')
                    <p class="mt-1.5 font-sans text-[0.6rem] tracking-wide text-red-700">{{ $message }}</p>
                @enderror
            </div>

            {{-- Foto --}}
            <div>
                <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                    Foto Kerusakan
                </label>
                <input type="file" name="foto_kerusakan[]" accept="image/*" multiple
                       class="w-full font-sans text-[0.78rem] text-dim
                              file:mr-4 file:py-2 file:px-4 file:border file:border-rule
                              file:bg-cream file:text-label file:font-sans file:text-[0.55rem]
                              file:tracking-[0.15em] file:uppercase file:cursor-pointer
                              hover:file:bg-sand file:transition-colors">
                <p class="mt-1.5 font-sans text-[0.58rem] tracking-wide text-ghost">Bisa upload lebih dari 1 foto. Maks 2MB per foto.</p>
            </div>

            {{-- Denda --}}
            <div>
                <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                    Jumlah Denda (jika ada)
                </label>
                <div class="flex items-end gap-0">
                    <span class="pb-2.5 pt-1 font-sans text-[0.82rem] text-ghost border-b border-rule pr-2">Rp</span>
                    <input type="number" name="jumlah_denda"
                           value="{{ old('jumlah_denda', 0) }}" min="0" step="1000"
                           class="flex-1 border-b border-rule bg-transparent pb-2.5 pt-1
                                  font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors">
                </div>
            </div>

            {{-- Actions --}}
            <div class="pt-4 border-t border-rule/60 flex items-center gap-3">
                <button type="submit"
                    class="flex items-center gap-2 bg-espresso text-paper px-6 py-3
                           font-sans text-[0.6rem] font-semibold tracking-[0.28em] uppercase
                           hover:bg-ink transition-colors active:scale-[0.99]">
                    <i class="fas fa-check text-[0.5rem]"></i> Simpan
                </button>
                <a href="{{ route('admin.history-kerusakan.index') }}"
                   class="flex items-center gap-2 border border-rule text-label px-6 py-3
                          font-sans text-[0.6rem] font-semibold tracking-[0.28em] uppercase
                          hover:bg-sand transition-colors">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
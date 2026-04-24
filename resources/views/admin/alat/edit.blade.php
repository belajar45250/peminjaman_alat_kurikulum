{{-- resources/views/admin/alat/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Alat')

@section('content')

<div class="mb-8 flex items-end justify-between">
    <div>
        <p class="font-sans text-[0.55rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">Inventaris</p>
        <h1 class="font-serif text-ink text-3xl font-normal leading-none">Edit Alat</h1>
        <div class="mt-3 h-px w-10 bg-rule"></div>
    </div>
    <a href="{{ route('admin.alat.qr-pdf', $alat->id) }}"
       class="flex items-center gap-2 border border-rule text-label px-4 py-2.5
              font-sans text-[0.55rem] font-semibold tracking-[0.2em] uppercase
              hover:bg-espresso hover:text-paper hover:border-espresso transition-all">
        <i class="fas fa-qrcode text-[0.5rem]"></i> Download QR
    </a>
</div>

<div class="max-w-3xl">
    <div class="bg-paper border border-rule">

        <div class="border-b border-rule px-6 py-4 flex items-center justify-between">
            <div>
                <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Formulir</p>
                <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Data Alat</h2>
            </div>
            <a href="{{ route('admin.alat.index') }}"
               class="flex items-center gap-2 border border-rule text-label px-3 py-2
                      font-sans text-[0.55rem] font-semibold tracking-[0.2em] uppercase
                      hover:bg-sand transition-colors">
                <i class="fas fa-arrow-left text-[0.5rem]"></i> Kembali
            </a>
        </div>

        {{-- Info QR --}}
        <div class="mx-6 mt-5 border-l-2 border-rule bg-cream/50 px-4 py-3">
            <p class="font-sans text-[0.68rem] tracking-wide text-dim leading-relaxed">
                <i class="fas fa-info-circle text-ghost mr-1.5"></i>
                QR Code di-generate otomatis dari kode alat.
                Kode alat <span class="font-semibold text-ink">tidak disarankan diubah</span> jika sudah ada riwayat peminjaman.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.alat.update', $alat->id) }}"
              enctype="multipart/form-data" class="px-6 py-6">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                {{-- Nama Alat --}}
                <div class="md:col-span-2">
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                        Nama Alat <span class="text-red-700">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="nama_alat"
                               value="{{ old('nama_alat', $alat->nama_alat) }}"
                               class="peer w-full border-b {{ $errors->has('nama_alat') ? 'border-red-600' : 'border-rule' }} bg-transparent pb-2.5 pt-1
                                      font-sans text-[0.88rem] tracking-wide text-ink outline-none
                                      transition-colors duration-200 focus:border-ink">
                        <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                    </div>
                    @error('nama_alat')
                        <p class="mt-1.5 font-sans text-[0.6rem] tracking-wide text-red-700">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Tambahkan setelah field nama_alat --}}
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                        Nomor Urut
                        <span class="text-ghost font-normal normal-case tracking-normal ml-1">(opsional)</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="nomor_urut"
                            value="{{ old('nomor_urut', $alat->nomor_urut ?? '') }}"
                            placeholder="Contoh: 01, LAP-03"
                            class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                    font-sans text-[0.88rem] tracking-wide text-ink outline-none
                                    placeholder-ghost transition-colors duration-200 focus:border-ink">
                        <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                    </div>
                    <p class="mt-1.5 font-sans text-[0.58rem] tracking-wide text-ghost">
                        Akan tampil di QR Code dan katalog.
                    </p>
                </div>

                {{-- Kode Alat --}}
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                        Kode Alat
                    </label>
                    <div class="relative">
                        <input type="text" name="kode_alat"
                               value="{{ old('kode_alat', $alat->kode_alat) }}"
                               {{ $alat->sedangDipinjam() ? 'readonly' : '' }}
                               class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                      font-sans text-[0.88rem] tracking-wide text-ink outline-none
                                      transition-colors duration-200 focus:border-ink
                                      {{ $alat->sedangDipinjam() ? 'opacity-50 cursor-not-allowed' : '' }}">
                        @if(!$alat->sedangDipinjam())
                            <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                        @endif
                    </div>
                    @if($alat->sedangDipinjam())
                        <p class="mt-1.5 font-sans text-[0.58rem] tracking-wide text-amber-700">
                            <i class="fas fa-lock mr-1 text-[0.5rem]"></i>Terkunci — alat sedang dipinjam.
                        </p>
                    @endif
                    @error('kode_alat')
                        <p class="mt-1.5 font-sans text-[0.6rem] tracking-wide text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                        Kategori
                    </label>
                    <div class="relative">
                        <input type="text" name="kategori"
                               value="{{ old('kategori', $alat->kategori) }}"
                               class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                      font-sans text-[0.88rem] tracking-wide text-ink outline-none
                                      placeholder-ghost transition-colors duration-200 focus:border-ink">
                        <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                    </div>
                </div>

                {{-- Harga --}}
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                        Harga Alat <span class="text-red-700">*</span>
                    </label>
                    <div class="flex items-end gap-0">
                        <span class="pb-2.5 pt-1 font-sans text-[0.82rem] text-ghost border-b border-rule pr-2">Rp</span>
                        <div class="relative flex-1">
                            <input type="number" name="harga"
                                   value="{{ old('harga', $alat->harga) }}"
                                   min="0" step="1000"
                                   class="peer w-full border-b {{ $errors->has('harga') ? 'border-red-600' : 'border-rule' }} bg-transparent pb-2.5 pt-1
                                          font-sans text-[0.88rem] tracking-wide text-ink outline-none
                                          transition-colors duration-200 focus:border-ink">
                            <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                        </div>
                    </div>
                    @error('harga')
                        <p class="mt-1 font-sans text-[0.6rem] tracking-wide text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kondisi --}}
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                        Kondisi <span class="text-red-700">*</span>
                    </label>
                    <select name="kondisi"
                            class="w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                   font-sans text-[0.88rem] text-ink outline-none focus:border-ink transition-colors">
                        @foreach(['baik'=>'Baik','rusak_ringan'=>'Rusak Ringan','rusak_berat'=>'Rusak Berat','tidak_tersedia'=>'Tidak Tersedia'] as $val => $lbl)
                            <option value="{{ $val }}" {{ old('kondisi', $alat->kondisi) === $val ? 'selected':'' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Lokasi --}}
                <div>
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                        Lokasi Penyimpanan
                    </label>
                    <div class="relative">
                        <input type="text" name="lokasi_penyimpanan"
                               value="{{ old('lokasi_penyimpanan', $alat->lokasi_penyimpanan) }}"
                               placeholder="Contoh: Rak A, Lemari Lab"
                               class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                      font-sans text-[0.88rem] tracking-wide text-ink outline-none
                                      placeholder-ghost transition-colors duration-200 focus:border-ink">
                        <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="md:col-span-2">
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" rows="3"
                              class="w-full border-b border-rule bg-transparent pb-2 pt-1
                                     font-sans text-[0.88rem] tracking-wide text-ink outline-none
                                     placeholder-ghost focus:border-ink resize-none transition-colors">{{ old('deskripsi', $alat->deskripsi) }}</textarea>
                </div>

                {{-- Foto --}}
                <div class="md:col-span-2">
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                        Foto Alat
                    </label>
                    @if($alat->gambar)
                        <div class="mb-3 flex items-center gap-4">
                            <img src="{{ Storage::exists($alat->gambar) ? Storage::url($alat->gambar) : asset('storage/' . $alat->gambar) }}"
                                class="h-20 w-20 object-cover border border-rule" alt="Foto alat"
                                onerror="this.style.display='none'">
                            <p class="font-sans text-[0.6rem] tracking-wide text-ghost">Upload foto baru untuk mengganti.</p>
                        </div>
                    @endif
                    <input type="file" name="gambar" accept="image/*"
                           class="w-full font-sans text-[0.78rem] text-dim
                                  file:mr-4 file:py-2 file:px-4 file:border file:border-rule
                                  file:bg-cream file:text-label file:font-sans file:text-[0.55rem]
                                  file:tracking-[0.15em] file:uppercase file:cursor-pointer
                                  hover:file:bg-sand file:transition-colors">
                    @error('gambar')
                        <p class="mt-1.5 font-sans text-[0.6rem] tracking-wide text-red-700">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="pt-4 border-t border-rule/60 flex items-center gap-3">
                <button type="submit"
                    class="flex items-center gap-2 bg-espresso text-paper px-6 py-3
                           font-sans text-[0.6rem] font-semibold tracking-[0.28em] uppercase
                           hover:bg-ink transition-colors active:scale-[0.99]">
                    <i class="fas fa-check text-[0.5rem]"></i> Update
                </button>
                <a href="{{ route('admin.alat.index') }}"
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
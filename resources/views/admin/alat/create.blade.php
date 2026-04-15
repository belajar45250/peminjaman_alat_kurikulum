{{-- resources/views/admin/alat/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Alat Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.alat.index') }}" class="text-decoration-none text-muted">Alat</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Alat Baru
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.alat.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Alat <span class="text-danger">*</span></label>
                            <input type="text" name="nama_alat"
                                   class="form-control @error('nama_alat') is-invalid @enderror"
                                   value="{{ old('nama_alat') }}"
                                   placeholder="Contoh: Multimeter Digital">
                            @error('nama_alat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Kode Alat</label>
                            <input type="text" name="kode_alat"
                                   class="form-control @error('kode_alat') is-invalid @enderror"
                                   value="{{ old('kode_alat') }}"
                                   placeholder="Auto-generate jika kosong">
                            @error('kode_alat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="kategori"
                                   class="form-control"
                                   value="{{ old('kategori') }}"
                                   placeholder="Contoh: Elektronik, Mekanik">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Harga Alat <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga"
                                       class="form-control @error('harga') is-invalid @enderror"
                                       value="{{ old('harga', 0) }}"
                                       min="0" step="1000">
                                @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-text">Digunakan untuk kalkulasi denda.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                            <select name="kondisi" class="form-select @error('kondisi') is-invalid @enderror">
                                <option value="baik" {{ old('kondisi') === 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak_ringan" {{ old('kondisi') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="rusak_berat" {{ old('kondisi') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                <option value="tidak_tersedia" {{ old('kondisi') === 'tidak_tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                            </select>
                            @error('kondisi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Lokasi Penyimpanan</label>
                            <input type="text" name="lokasi_penyimpanan"
                                   class="form-control"
                                   value="{{ old('lokasi_penyimpanan') }}"
                                   placeholder="Contoh: Rak A, Lemari Lab">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" rows="3"
                                      class="form-control"
                                      placeholder="Deskripsi singkat alat...">{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Foto Alat</label>
                            <input type="file" name="gambar"
                                   class="form-control @error('gambar') is-invalid @enderror"
                                   accept="image/*">
                            @error('gambar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Format: JPG, PNG, WEBP. Maks 2MB.</div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Simpan
                        </button>
                        <a href="{{ route('admin.alat.index') }}" class="btn btn-light px-4">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
{{-- resources/views/admin/alat/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Alat')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.alat.index') }}" class="text-decoration-none text-muted">Alat</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-pencil me-2 text-warning"></i>Edit Alat</span>
                <a href="{{ route('admin.alat.qr-pdf', $alat->id) }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-qr-code me-1"></i> Download QR
                </a>
            </div>
            <div class="card-body p-4">

                {{-- Info QR --}}
                <div class="alert alert-info d-flex align-items-start gap-2 mb-4">
                    <i class="bi bi-info-circle-fill mt-1"></i>
                    <div>
                        <strong>QR Code:</strong> Di-generate otomatis dari kode alat.
                        Kode alat <strong>tidak disarankan diubah</strong> jika sudah ada riwayat peminjaman.
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.alat.update', $alat->id) }}"
                      enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Alat <span class="text-danger">*</span></label>
                            <input type="text" name="nama_alat"
                                   class="form-control @error('nama_alat') is-invalid @enderror"
                                   value="{{ old('nama_alat', $alat->nama_alat) }}">
                            @error('nama_alat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Kode Alat</label>
                            <input type="text" name="kode_alat"
                                   class="form-control @error('kode_alat') is-invalid @enderror"
                                   value="{{ old('kode_alat', $alat->kode_alat) }}"
                                   {{ $alat->sedangDipinjam() ? 'readonly' : '' }}>
                            @if($alat->sedangDipinjam())
                                <div class="form-text text-warning">
                                    <i class="bi bi-lock-fill me-1"></i>Terkunci — alat sedang dipinjam.
                                </div>
                            @endif
                            @error('kode_alat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="kategori" class="form-control"
                                   value="{{ old('kategori', $alat->kategori) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Harga Alat <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga"
                                       class="form-control @error('harga') is-invalid @enderror"
                                       value="{{ old('harga', $alat->harga) }}" min="0" step="1000">
                                @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                            <select name="kondisi" class="form-select">
                                @foreach(['baik'=>'Baik','rusak_ringan'=>'Rusak Ringan','rusak_berat'=>'Rusak Berat','tidak_tersedia'=>'Tidak Tersedia'] as $val => $label)
                                <option value="{{ $val }}" {{ old('kondisi', $alat->kondisi) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Lokasi Penyimpanan</label>
                            <input type="text" name="lokasi_penyimpanan" class="form-control"
                                   value="{{ old('lokasi_penyimpanan', $alat->lokasi_penyimpanan) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" rows="3" class="form-control">{{ old('deskripsi', $alat->deskripsi) }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Foto Alat</label>
                            @if($alat->gambar)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($alat->gambar) }}"
                                         class="rounded" height="80" alt="Foto alat">
                                    <div class="form-text">Unggah foto baru untuk mengganti.</div>
                                </div>
                            @endif
                            <input type="file" name="gambar" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Update
                        </button>
                        <a href="{{ route('admin.alat.index') }}" class="btn btn-light px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
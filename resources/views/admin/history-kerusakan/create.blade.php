{{-- resources/views/admin/history-kerusakan/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Catat Kerusakan Manual')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.history-kerusakan.index') }}" class="text-decoration-none text-muted">History Kerusakan</a>
    </li>
    <li class="breadcrumb-item active">Catat Manual</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-2 text-danger"></i>Catat Kerusakan Manual
            </div>
            <div class="card-body p-4">

                <div class="alert alert-info small mb-4">
                    <i class="bi bi-info-circle me-1"></i>
                    Gunakan form ini untuk mencatat kerusakan yang tidak melalui proses pengembalian,
                    misalnya kerusakan yang baru ditemukan saat pengecekan inventaris.
                </div>

                <form method="POST"
                      action="{{ route('admin.history-kerusakan.store') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Pilih Alat <span class="text-danger">*</span></label>
                        <select name="alat_id"
                                class="form-select @error('alat_id') is-invalid @enderror"
                                required>
                            <option value="" disabled selected>— Pilih Alat —</option>
                            @foreach($alat as $item)
                                <option value="{{ $item->id }}"
                                        {{ old('alat_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_alat }} ({{ $item->kode_alat }})
                                </option>
                            @endforeach
                        </select>
                        @error('alat_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label">Nama Penanggung Jawab</label>
                            <input type="text" name="nama_peminjam" class="form-control"
                                   value="{{ old('nama_peminjam') }}"
                                   placeholder="Kosongkan jika tidak diketahui">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Kelas</label>
                            <input type="text" name="kelas" class="form-control"
                                   value="{{ old('kelas') }}" placeholder="Misal: X RPL 1">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jenis Kerusakan <span class="text-danger">*</span></label>
                            <select name="jenis_kerusakan"
                                    class="form-select @error('jenis_kerusakan') is-invalid @enderror"
                                    required>
                                <option value="rusak_ringan" {{ old('jenis_kerusakan') === 'rusak_ringan' ? 'selected':'' }}>Rusak Ringan</option>
                                <option value="rusak_berat"  {{ old('jenis_kerusakan') === 'rusak_berat'  ? 'selected':'' }}>Rusak Berat</option>
                                <option value="hilang"       {{ old('jenis_kerusakan') === 'hilang'       ? 'selected':'' }}>Hilang</option>
                            </select>
                            @error('jenis_kerusakan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kondisi Sebelum Rusak <span class="text-danger">*</span></label>
                            <select name="kondisi_sebelum"
                                    class="form-select @error('kondisi_sebelum') is-invalid @enderror"
                                    required>
                                <option value="baik"        {{ old('kondisi_sebelum') === 'baik'        ? 'selected':'' }}>Baik</option>
                                <option value="rusak_ringan" {{ old('kondisi_sebelum') === 'rusak_ringan' ? 'selected':'' }}>Rusak Ringan</option>
                            </select>
                            @error('kondisi_sebelum') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi Kerusakan <span class="text-danger">*</span></label>
                        <textarea name="deskripsi_kerusakan" rows="3"
                                  class="form-control @error('deskripsi_kerusakan') is-invalid @enderror"
                                  placeholder="Jelaskan detail kerusakannya..."
                                  required>{{ old('deskripsi_kerusakan') }}</textarea>
                        @error('deskripsi_kerusakan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Kerusakan</label>
                        <input type="file" name="foto_kerusakan[]"
                               class="form-control" accept="image/*" multiple>
                        <div class="form-text">Bisa upload lebih dari 1 foto. Maks 2MB per foto.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Jumlah Denda (jika ada)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="jumlah_denda"
                                   class="form-control"
                                   value="{{ old('jumlah_denda', 0) }}" min="0" step="1000">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="bi bi-check-lg me-1"></i>Simpan
                        </button>
                        <a href="{{ route('admin.history-kerusakan.index') }}" class="btn btn-light px-4">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
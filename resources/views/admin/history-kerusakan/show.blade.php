{{-- resources/views/admin/history-kerusakan/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Kerusakan')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.history-kerusakan.index') }}" class="text-decoration-none text-muted">History Kerusakan</a>
    </li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')

<div class="row g-4">

    {{-- Kolom Kiri: Info Kerusakan --}}
    <div class="col-lg-7">

        {{-- Info Utama --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-tools me-2 text-danger"></i>Detail Kerusakan</span>
                <span class="badge bg-{{ $historyKerusakan->badge_status }} fs-6">
                    {{ $historyKerusakan->label_status_tindak_lanjut }}
                </span>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted fw-normal small">Alat</dt>
                    <dd class="col-7 fw-semibold">{{ $historyKerusakan->nama_alat_snapshot }}</dd>

                    <dt class="col-5 text-muted fw-normal small">Kode Alat</dt>
                    <dd class="col-7"><code class="small">{{ $historyKerusakan->kode_alat_snapshot }}</code></dd>

                    <dt class="col-5 text-muted fw-normal small">Penanggung Jawab</dt>
                    <dd class="col-7">
                        {{ $historyKerusakan->nama_peminjam ?? '—' }}
                        @if($historyKerusakan->kelas)
                            <span class="badge bg-light text-dark border ms-1">{{ $historyKerusakan->kelas }}</span>
                        @endif
                    </dd>

                    <dt class="col-5 text-muted fw-normal small">Jenis Kerusakan</dt>
                    <dd class="col-7">
                        @php $bj = ['rusak_ringan'=>'warning','rusak_berat'=>'danger','hilang'=>'dark']; @endphp
                        <span class="badge bg-{{ $bj[$historyKerusakan->jenis_kerusakan] ?? 'secondary' }}">
                            {{ $historyKerusakan->label_jenis_kerusakan }}
                        </span>
                    </dd>

                    <dt class="col-5 text-muted fw-normal small">Kondisi Sebelumnya</dt>
                    <dd class="col-7">{{ $historyKerusakan->kondisi_sebelum }}</dd>

                    <dt class="col-5 text-muted fw-normal small">Tanggal Rusak</dt>
                    <dd class="col-7">{{ $historyKerusakan->tanggal_rusak->format('d/m/Y H:i') }}</dd>

                    <dt class="col-5 text-muted fw-normal small">Dicatat Oleh</dt>
                    <dd class="col-7">{{ $historyKerusakan->dicatatOleh?->name ?? '—' }}</dd>
                </dl>

                <hr>
                <div class="small text-muted mb-1 fw-semibold">Deskripsi Kerusakan</div>
                <p class="small mb-0">{{ $historyKerusakan->deskripsi_kerusakan }}</p>
            </div>
        </div>

        {{-- Foto Kerusakan --}}
        @if($historyKerusakan->foto_kerusakan && count($historyKerusakan->foto_kerusakan))
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-images me-2"></i>Foto Kerusakan
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach($historyKerusakan->foto_kerusakan as $foto)
                    <div class="col-4">
                        <a href="{{ Storage::url($foto) }}" target="_blank">
                            <img src="{{ Storage::url($foto) }}"
                                 class="img-fluid rounded"
                                 style="height:100px;width:100%;object-fit:cover;"
                                 alt="Foto kerusakan">
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Info Pengembalian (jika ada) --}}
        @if($historyKerusakan->pengembalian)
        <div class="card">
            <div class="card-header">
                <i class="bi bi-link-45deg me-2"></i>Dari Transaksi Pengembalian
            </div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted fw-normal">Kode Pengembalian</dt>
                    <dd class="col-7"><code>{{ $historyKerusakan->pengembalian->kode_pengembalian }}</code></dd>

                    <dt class="col-5 text-muted fw-normal">Kode Transaksi</dt>
                    <dd class="col-7">
                        <a href="{{ route('admin.peminjaman.show', $historyKerusakan->pengembalian->peminjaman_id) }}"
                           class="text-decoration-none">
                            {{ $historyKerusakan->pengembalian->peminjaman->kode_transaksi ?? '—' }}
                        </a>
                    </dd>

                    <dt class="col-5 text-muted fw-normal">Waktu Kembali</dt>
                    <dd class="col-7">
                        {{ $historyKerusakan->pengembalian->waktu_kembali->format('d/m/Y H:i') }}
                    </dd>
                </dl>
            </div>
        </div>
        @endif

    </div>

    {{-- Kolom Kanan: Update Status --}}
    <div class="col-lg-5">

        {{-- Status Denda --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-cash-coin me-2 text-warning"></i>Status Denda
            </div>
            <div class="card-body">
                @if($historyKerusakan->jumlah_denda > 0)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted small">Jumlah Denda</span>
                        <span class="fw-bold fs-5 text-danger">
                            Rp {{ number_format($historyKerusakan->jumlah_denda, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <span class="badge bg-{{ $historyKerusakan->badge_denda }} fs-6 w-100 py-2">
                            {{ $historyKerusakan->label_status_denda }}
                        </span>
                    </div>

                    @if($historyKerusakan->tanggal_lunas)
                        <div class="text-muted small text-center">
                            Lunas pada {{ $historyKerusakan->tanggal_lunas->format('d/m/Y H:i') }}
                        </div>
                    @endif

                    @if($historyKerusakan->status_denda !== 'lunas')
                    <hr>
                    <form method="POST"
                          action="{{ route('admin.history-kerusakan.denda', $historyKerusakan->id) }}">
                        @csrf
                        <input type="hidden" name="status_denda" value="lunas">
                        <button type="submit"
                                class="btn btn-success w-100"
                                onclick="return confirm('Tandai denda sebagai sudah lunas?')">
                            <i class="bi bi-check2-circle me-1"></i>Tandai Lunas
                        </button>
                    </form>
                    @endif
                @else
                    <div class="text-center text-muted py-2 small">
                        <i class="bi bi-check-circle me-1"></i>Tidak ada denda
                    </div>
                @endif
            </div>
        </div>

        {{-- Update Tindak Lanjut --}}
        <div class="card">
            <div class="card-header">
                <i class="bi bi-wrench me-2 text-primary"></i>Update Tindak Lanjut
            </div>
            <div class="card-body">
                @if($historyKerusakan->catatan_tindak_lanjut)
                <div class="bg-light rounded p-2 mb-3 small">
                    <div class="text-muted mb-1">Catatan sebelumnya:</div>
                    {{ $historyKerusakan->catatan_tindak_lanjut }}
                    @if($historyKerusakan->biaya_perbaikan > 0)
                        <div class="mt-1 text-muted">
                            Biaya: <strong>Rp {{ number_format($historyKerusakan->biaya_perbaikan, 0, ',', '.') }}</strong>
                        </div>
                    @endif
                    @if($historyKerusakan->tanggal_selesai_perbaikan)
                        <div class="mt-1 text-muted">
                            Selesai: {{ $historyKerusakan->tanggal_selesai_perbaikan->format('d/m/Y') }}
                        </div>
                    @endif
                </div>
                @endif

                <form method="POST"
                      action="{{ route('admin.history-kerusakan.tindak-lanjut', $historyKerusakan->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small">Status Tindak Lanjut</label>
                        <select name="status_tindak_lanjut" class="form-select form-select-sm" id="selectStatus">
                            @foreach([
                                'menunggu'         => 'Menunggu Tindakan',
                                'diperbaiki'       => 'Sedang Diperbaiki',
                                'sudah_diperbaiki' => 'Sudah Diperbaiki',
                                'diganti_baru'     => 'Diganti Baru',
                                'dihapuskan'       => 'Dihapuskan dari Inventaris',
                            ] as $val => $label)
                            <option value="{{ $val }}"
                                {{ $historyKerusakan->status_tindak_lanjut === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="fieldsPerbaikan">
                        <div class="mb-3">
                            <label class="form-label small">Biaya Perbaikan (jika ada)</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="biaya_perbaikan" class="form-control"
                                       value="{{ $historyKerusakan->biaya_perbaikan }}" min="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai_perbaikan" class="form-control form-control-sm"
                                   value="{{ $historyKerusakan->tanggal_selesai_perbaikan?->format('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Catatan</label>
                        <textarea name="catatan_tindak_lanjut" rows="3"
                                  class="form-control form-control-sm"
                                  placeholder="Catatan tindak lanjut...">{{ $historyKerusakan->catatan_tindak_lanjut }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-check-lg me-1"></i>Simpan Update
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.history-kerusakan.index') }}" class="btn btn-light w-100">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const selectStatus    = document.getElementById('selectStatus');
    const fieldsPerbaikan = document.getElementById('fieldsPerbaikan');

    function toggleFields() {
        const val = selectStatus.value;
        const show = ['diperbaiki', 'sudah_diperbaiki', 'diganti_baru'].includes(val);
        fieldsPerbaikan.style.display = show ? 'block' : 'none';
    }

    selectStatus.addEventListener('change', toggleFields);
    toggleFields();
</script>
@endsection
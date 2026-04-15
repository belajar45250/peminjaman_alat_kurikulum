{{-- resources/views/admin/pengembalian/form.blade.php --}}
@extends('layouts.app')

@section('title', 'Konfirmasi Pengembalian')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.pengembalian.index') }}" class="text-decoration-none text-muted">Pengembalian</a></li>
    <li class="breadcrumb-item active">Konfirmasi</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">

        {{-- Info Peminjaman --}}
        <div class="card mb-3">
            <div class="card-header bg-warning bg-opacity-10 border-warning">
                <i class="bi bi-info-circle me-2 text-warning"></i>
                Data Peminjaman Aktif
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <div class="text-muted small">Alat</div>
                        <div class="fw-semibold">{{ $alat->nama_alat }}</div>
                        <code class="small">{{ $alat->kode_alat }}</code>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Kode Transaksi</div>
                        <div class="fw-semibold">{{ $peminjaman->kode_transaksi }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Peminjam</div>
                        <div class="fw-semibold">{{ $peminjaman->nama_peminjam }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Kelas</div>
                        <div>{{ $peminjaman->kelas }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Mata Pelajaran</div>
                        <div>{{ $peminjaman->mata_pelajaran }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Waktu Pinjam</div>
                        <div>{{ $peminjaman->waktu_pinjam->format('d/m/Y H:i') }}</div>
                        @if($peminjaman->terlambat)
                            <span class="badge bg-danger">Terlambat {{ $peminjaman->waktu_pinjam->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Pengembalian --}}
        <div class="card">
            <div class="card-header">
                <i class="bi bi-arrow-return-left me-2 text-success"></i>Form Pengembalian
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.pengembalian.proses') }}">
                    @csrf
                    <input type="hidden" name="qr_hash" value="{{ $qrHash }}">
                    <input type="hidden" name="peminjaman_id" value="{{ $peminjaman->id }}">

                    <div class="mb-4">
                        <label class="form-label">Kondisi Alat Saat Dikembalikan <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            @php
                                $kondisiList = [
                                    'baik'        => ['label'=>'Baik',        'icon'=>'check-circle-fill', 'color'=>'success'],
                                    'rusak_ringan' => ['label'=>'Rusak Ringan','icon'=>'exclamation-circle','color'=>'warning'],
                                    'rusak_berat'  => ['label'=>'Rusak Berat', 'icon'=>'x-circle-fill',    'color'=>'danger'],
                                    'hilang'       => ['label'=>'Hilang',      'icon'=>'question-circle-fill','color'=>'dark'],
                                ];
                            @endphp
                            @foreach($kondisiList as $val => $k)
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="kondisi_kembali"
                                       id="kondisi_{{ $val }}" value="{{ $val }}"
                                       {{ old('kondisi_kembali', 'baik') === $val ? 'checked' : '' }}>
                                <label class="btn btn-outline-{{ $k['color'] }} w-100 py-3" for="kondisi_{{ $val }}">
                                    <i class="bi bi-{{ $k['icon'] }} d-block fs-4 mb-1"></i>
                                    {{ $k['label'] }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Preview Denda --}}
                    <div id="preview-denda" class="alert alert-warning d-none mb-3">
                        <i class="bi bi-calculator me-2"></i>
                        <strong>Estimasi Denda:</strong>
                        <span id="estimasi-denda">Rp 0</span>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" rows="2" class="form-control"
                                  placeholder="Catatan kondisi alat (opsional)...">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-lg px-5 fw-bold">
                            <i class="bi bi-check2-all me-2"></i>Proses Pengembalian
                        </button>
                        <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-light btn-lg">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Preview denda saat kondisi dipilih
    const harga = {{ $alat->harga }};
    const persenRusak = {{ \App\Models\Pengaturan::ambil('persentase_denda_rusak', 30) }};
    const persenHilang = {{ \App\Models\Pengaturan::ambil('persentase_denda_hilang', 100) }};
    const previewDenda = document.getElementById('preview-denda');
    const estimasiDenda = document.getElementById('estimasi-denda');

    document.querySelectorAll('input[name="kondisi_kembali"]').forEach(radio => {
        radio.addEventListener('change', function() {
            let denda = 0;
            if (this.value === 'rusak_berat') denda = harga * (persenRusak / 100);
            if (this.value === 'hilang')      denda = harga * (persenHilang / 100);

            if (denda > 0) {
                previewDenda.classList.remove('d-none');
                estimasiDenda.textContent = 'Rp ' + denda.toLocaleString('id-ID');
            } else {
                previewDenda.classList.add('d-none');
            }
        });
    });
</script>
@endsection
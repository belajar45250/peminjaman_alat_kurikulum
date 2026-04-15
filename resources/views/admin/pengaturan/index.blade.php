{{-- resources/views/admin/pengaturan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pengaturan</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-gear me-2 text-secondary"></i>Pengaturan Sistem
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.pengaturan.update') }}">
                    @csrf

                    <h6 class="fw-bold text-muted text-uppercase small mb-3">Identitas Sekolah</h6>
                    <div class="mb-4">
                        <label class="form-label">Nama Sekolah</label>
                        <input type="text" name="nama_sekolah" class="form-control"
                               value="{{ \App\Models\Pengaturan::ambil('nama_sekolah') }}"
                               placeholder="Nama sekolah untuk header laporan">
                    </div>

                    <hr>
                    <h6 class="fw-bold text-muted text-uppercase small mb-3">Aturan Peminjaman</h6>
                    <div class="mb-4">
                        <label class="form-label">Batas Waktu Peminjaman (Jam)</label>
                        <div class="input-group" style="max-width:200px;">
                            <input type="number" name="batas_jam_pinjam" class="form-control"
                                   value="{{ \App\Models\Pengaturan::ambil('batas_jam_pinjam', 8) }}"
                                   min="1" max="72">
                            <span class="input-group-text">Jam</span>
                        </div>
                        <div class="form-text">Waktu maksimal peminjaman sebelum dianggap terlambat.</div>
                    </div>

                    <hr>
                    <h6 class="fw-bold text-muted text-uppercase small mb-3">Pengaturan Denda</h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Denda Alat Rusak Berat</label>
                            <div class="input-group">
                                <input type="number" name="persentase_denda_rusak" class="form-control"
                                       value="{{ \App\Models\Pengaturan::ambil('persentase_denda_rusak', 30) }}"
                                       min="0" max="100" step="0.5">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text">Persentase dari harga alat.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Denda Alat Hilang</label>
                            <div class="input-group">
                                <input type="number" name="persentase_denda_hilang" class="form-control"
                                       value="{{ \App\Models\Pengaturan::ambil('persentase_denda_hilang', 100) }}"
                                       min="0" max="100" step="0.5">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text">Persentase dari harga alat.</div>
                        </div>
                    </div>

                    {{-- Preview kalkulasi denda --}}
                    <div class="bg-light rounded p-3 mb-4 small">
                        <strong>Contoh kalkulasi:</strong> Alat senilai Rp 500.000 →
                        Rusak berat = Rp <span id="contohRusak">0</span>,
                        Hilang = Rp <span id="contohHilang">0</span>
                    </div>

                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i> Simpan Pengaturan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function updateContoh() {
        const rusak  = parseFloat(document.querySelector('[name="persentase_denda_rusak"]').value) || 0;
        const hilang = parseFloat(document.querySelector('[name="persentase_denda_hilang"]').value) || 0;
        document.getElementById('contohRusak').textContent  = (500000 * rusak / 100).toLocaleString('id-ID');
        document.getElementById('contohHilang').textContent = (500000 * hilang / 100).toLocaleString('id-ID');
    }
    document.querySelectorAll('[name="persentase_denda_rusak"], [name="persentase_denda_hilang"]')
            .forEach(el => el.addEventListener('input', updateContoh));
    updateContoh();
</script>
@endsection
{{-- resources/views/publik/form-pinjam.blade.php --}}
@extends('layouts.guest')

@section('title', 'Pinjam Alat — ' . $alat->nama_alat)

@section('card_header')
    <div style="width:52px;height:52px;background:rgba(255,255,255,.2);border-radius:14px;
                display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
        <i class="bi bi-box-arrow-up fs-4"></i>
    </div>
    <h5 class="fw-bold mb-1">Form Peminjaman</h5>
    <div class="opacity-90 fw-semibold">{{ $alat->nama_alat }}</div>
    <small class="opacity-60">{{ $alat->kode_alat }}</small>
@endsection

@section('content')

    @php
        $jamPelajaran = config('sekolah.jam_pelajaran');
        $kelasList    = config('sekolah.kelas');
    @endphp

    <form method="POST" action="{{ route('publik.submit') }}">
        @csrf
        <input type="hidden" name="qr_hash" value="{{ $qrHash }}">

        {{-- Nama --}}
        <div class="mb-3">
            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text"
                   name="nama_peminjam"
                   class="form-control @error('nama_peminjam') is-invalid @enderror"
                   value="{{ old('nama_peminjam') }}"
                   placeholder="Nama lengkap kamu"
                   autocomplete="name"
                   autofocus
                   required>
            @error('nama_peminjam')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kelas (Dropdown) --}}
        <div class="mb-3">
            <label class="form-label">Kelas <span class="text-danger">*</span></label>
            <select name="kelas"
                    class="form-select @error('kelas') is-invalid @enderror"
                    required>
                <option value="" disabled {{ old('kelas') ? '' : 'selected' }}>
                    — Pilih Kelas —
                </option>
                @foreach($kelasList as $tingkat => $daftarKelas)
                    <optgroup label="Kelas {{ $tingkat }}">
                        @foreach($daftarKelas as $kelas)
                            <option value="{{ $kelas }}"
                                    {{ old('kelas') === $kelas ? 'selected' : '' }}>
                                {{ $kelas }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            @error('kelas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Mata Pelajaran --}}
        <div class="mb-3">
            <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
            <input type="text"
                   name="mata_pelajaran"
                   class="form-control @error('mata_pelajaran') is-invalid @enderror"
                   value="{{ old('mata_pelajaran') }}"
                   placeholder="Contoh: Pemrograman Dasar"
                   required>
            @error('mata_pelajaran')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Guru Pengampu --}}
        <div class="mb-3">
            <label class="form-label">Guru Pengampu</label>
            <input type="text"
                   name="guru_pengampu"
                   class="form-control"
                   value="{{ old('guru_pengampu') }}"
                   placeholder="Nama guru yang mengajar">
        </div>

        {{-- Jam Pelajaran --}}
        <div class="mb-3">
            <label class="form-label">
                Jam Pelajaran <span class="text-danger">*</span>
            </label>
            <div class="row g-2">
                <div class="col-6">
                    <label class="form-label small text-muted mb-1">Mulai</label>
                    <select name="jam_pelajaran_mulai"
                            id="jamMulai"
                            class="form-select @error('jam_pelajaran_mulai') is-invalid @enderror"
                            required>
                        <option value="" disabled {{ old('jam_pelajaran_mulai') ? '' : 'selected' }}>
                            — Jam Mulai —
                        </option>
                        @foreach($jamPelajaran as $ke => $jam)
                            <option value="{{ $ke }}"
                                    data-mulai="{{ $jam['mulai'] }}"
                                    data-selesai="{{ $jam['selesai'] }}"
                                    {{ (int) old('jam_pelajaran_mulai') === $ke ? 'selected' : '' }}>
                                Jam ke-{{ $ke }} ({{ $jam['mulai'] }})
                            </option>
                        @endforeach
                    </select>
                    @error('jam_pelajaran_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6">
                    <label class="form-label small text-muted mb-1">Selesai</label>
                    <select name="jam_pelajaran_selesai"
                            id="jamSelesai"
                            class="form-select @error('jam_pelajaran_selesai') is-invalid @enderror"
                            required>
                        <option value="" disabled {{ old('jam_pelajaran_selesai') ? '' : 'selected' }}>
                            — Jam Selesai —
                        </option>
                        @foreach($jamPelajaran as $ke => $jam)
                            <option value="{{ $ke }}"
                                    data-selesai="{{ $jam['selesai'] }}"
                                    {{ (int) old('jam_pelajaran_selesai') === $ke ? 'selected' : '' }}>
                                Jam ke-{{ $ke }} ({{ $jam['selesai'] }})
                            </option>
                        @endforeach
                    </select>
                    @error('jam_pelajaran_selesai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Preview estimasi kembali --}}
            <div id="previewJam" class="mt-2 px-3 py-2 rounded-3 small d-none"
                 style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;">
                <i class="bi bi-clock-history me-1"></i>
                <span id="previewJamTeks"></span>
            </div>
        </div>

        {{-- Keperluan --}}
        <div class="mb-4">
            <label class="form-label">Keperluan</label>
            <textarea name="keperluan"
                      rows="2"
                      class="form-control"
                      placeholder="Untuk apa alat ini dipinjam?">{{ old('keperluan') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
            <i class="bi bi-check2-circle me-2"></i>Pinjam Sekarang
        </button>
    </form>

    {{-- Tombol kembali ke home --}}
    <div class="text-center mt-3">
        <a href="{{ route('home') }}" class="text-muted small text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Halaman Utama
        </a>
    </div>

@endsection

@section('scripts')
<script>
    const jamData = @json(config('sekolah.jam_pelajaran'));
    const jamMulai   = document.getElementById('jamMulai');
    const jamSelesai = document.getElementById('jamSelesai');
    const preview    = document.getElementById('previewJam');
    const previewTeks = document.getElementById('previewJamTeks');

    function updatePreview() {
        const mulaiVal   = jamMulai.value;
        const selesaiVal = jamSelesai.value;

        if (!mulaiVal || !selesaiVal) {
            preview.classList.add('d-none');
            return;
        }

        const mulai   = jamData[mulaiVal];
        const selesai = jamData[selesaiVal];

        if (parseInt(selesaiVal) < parseInt(mulaiVal)) {
            preview.style.background = '#fef2f2';
            preview.style.border     = '1px solid #fecaca';
            preview.style.color      = '#991b1b';
            previewTeks.textContent  = '⚠️ Jam selesai tidak boleh sebelum jam mulai!';
            preview.classList.remove('d-none');
            return;
        }

        preview.style.background = '#f0fdf4';
        preview.style.border     = '1px solid #bbf7d0';
        preview.style.color      = '#166534';
        previewTeks.textContent  =
            `Pinjam jam ke-${mulaiVal} (${mulai.mulai}) s/d jam ke-${selesaiVal} (${selesai.selesai}) `
            + `— Estimasi kembali: ${selesai.selesai}`;
        preview.classList.remove('d-none');
    }

    // Saat jam mulai berubah, filter jam selesai agar >= jam mulai
    jamMulai.addEventListener('change', function () {
        const mulaiVal = parseInt(this.value);
        Array.from(jamSelesai.options).forEach(opt => {
            if (opt.value === '') return;
            opt.disabled = parseInt(opt.value) < mulaiVal;
        });
        // Reset jam selesai jika sekarang disabled
        if (parseInt(jamSelesai.value) < mulaiVal) {
            jamSelesai.value = '';
        }
        updatePreview();
    });

    jamSelesai.addEventListener('change', updatePreview);

    // Jalankan saat load jika ada old value
    if (jamMulai.value) {
        jamMulai.dispatchEvent(new Event('change'));
    }
</script>
@endsection
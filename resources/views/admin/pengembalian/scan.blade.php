{{-- resources/views/admin/pengembalian/scan.blade.php --}}
@extends('layouts.app')

@section('title', 'Proses Pengembalian')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pengembalian</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">

        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-qr-code-scan me-2 text-primary"></i>Scan QR Code Alat
            </div>
            <div class="card-body p-4">

                <form method="POST" action="{{ route('admin.pengembalian.validasi') }}" id="formScan">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Hash QR Code</label>
                        <input type="text" name="qr_hash" id="qrInput"
                               class="form-control form-control-lg font-monospace"
                               placeholder="Scan QR atau arahkan kamera..."
                               autocomplete="off" autofocus>
                        <div class="form-text">
                            Input otomatis saat menggunakan scanner USB/Bluetooth.
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning btn-lg fw-bold">
                            <i class="bi bi-search me-2"></i>Cari Data Peminjaman
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="btnKamera">
                            <i class="bi bi-camera me-1"></i>Gunakan Kamera HP
                        </button>
                    </div>
                </form>

                {{-- Area kamera --}}
                <div id="area-kamera" class="mt-3" style="display:none;">
                    <div id="qr-reader" style="width:100%;border-radius:8px;overflow:hidden;"></div>
                    <button class="btn btn-sm btn-outline-danger mt-2 w-100" id="btnTutupKamera">
                        <i class="bi bi-x-circle me-1"></i>Tutup Kamera
                    </button>
                </div>
            </div>
        </div>

        {{-- Tips --}}
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="fw-bold mb-2"><i class="bi bi-lightbulb me-1 text-warning"></i>Cara Pengembalian</h6>
                <ol class="mb-0 small text-muted">
                    <li>Scan QR Code yang menempel di alat</li>
                    <li>Sistem akan menampilkan data peminjaman aktif</li>
                    <li>Pilih kondisi alat saat dikembalikan</li>
                    <li>Klik "Proses Pengembalian"</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let scanner = null;
    const btnKamera    = document.getElementById('btnKamera');
    const btnTutup     = document.getElementById('btnTutupKamera');
    const areaKamera   = document.getElementById('area-kamera');
    const qrInput      = document.getElementById('qrInput');

    function stopScanner() {
        if (scanner) {
            scanner.stop().catch(() => {});
            scanner = null;
        }
        areaKamera.style.display = 'none';
        btnKamera.innerHTML = '<i class="bi bi-camera me-1"></i>Gunakan Kamera HP';
    }

    btnKamera.addEventListener('click', function() {
        if (scanner) { stopScanner(); return; }

        areaKamera.style.display = 'block';
        btnKamera.innerHTML = '<i class="bi bi-stop-circle me-1"></i>Hentikan Kamera';
        scanner = new Html5Qrcode("qr-reader");
        scanner.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 280, height: 280 } },
            (decoded) => {
                const match = decoded.match(/\/pinjam\/qr\/([a-f0-9]{64})/);
                qrInput.value = match ? match[1] : decoded;
                stopScanner();
                document.getElementById('formScan').submit();
            }
        ).catch(err => {
            areaKamera.style.display = 'none';
            alert('Tidak dapat mengakses kamera: ' + err);
        });
    });

    btnTutup.addEventListener('click', stopScanner);
</script>
@endsection
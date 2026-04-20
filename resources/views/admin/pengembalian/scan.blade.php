{{-- resources/views/admin/pengembalian/scan.blade.php --}}
@extends('layouts.app')
@section('title', 'Proses Pengembalian')

@section('content')

<div class="mb-8">
    <p class="font-sans text-[0.55rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">Transaksi</p>
    <h1 class="font-serif text-ink text-3xl font-normal leading-none">Proses Pengembalian</h1>
    <div class="mt-3 h-px w-10 bg-rule"></div>
</div>

<div class="max-w-xl mx-auto space-y-5">

    {{-- Scanner Form --}}
    <div class="bg-paper border border-rule">
        <div class="border-b border-rule px-6 py-4">
            <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label">Scanner</p>
            <h2 class="font-serif text-ink text-lg font-normal mt-0.5">Scan QR Code Alat</h2>
        </div>
        <div class="px-6 py-6">
            <form method="POST" action="{{ route('admin.pengembalian.validasi') }}" id="formScan">
                @csrf
                <div class="mb-5">
                    <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                        Hash QR Code
                    </label>
                    <div class="relative">
                        <input type="text" name="qr_hash" id="qrInput"
                               placeholder="Scan QR atau arahkan kamera..."
                               autocomplete="off" autofocus
                               class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1
                                      font-sans text-[0.88rem] font-mono tracking-wide text-ink outline-none
                                      placeholder-ghost transition-colors duration-200 focus:border-ink">
                        <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                    </div>
                    <p class="mt-1.5 font-sans text-[0.58rem] tracking-wide text-ghost">
                        Input otomatis saat menggunakan scanner USB/Bluetooth.
                    </p>
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-espresso text-paper py-3.5
                               font-sans text-[0.6rem] font-semibold tracking-[0.28em] uppercase
                               hover:bg-ink transition-colors active:scale-[0.99]">
                        <i class="fas fa-search text-[0.5rem]"></i> Cari Data Peminjaman
                    </button>
                    <button type="button" id="btnKamera"
                        class="w-full flex items-center justify-center gap-2 border border-rule text-label py-3
                               font-sans text-[0.6rem] font-semibold tracking-[0.28em] uppercase
                               hover:bg-sand transition-colors">
                        <i class="fas fa-camera text-[0.5rem]"></i> Gunakan Kamera HP
                    </button>
                </div>
            </form>

            {{-- Area kamera --}}
            <div id="area-kamera" class="mt-5" style="display:none;">
                <div id="qr-reader" class="w-full overflow-hidden border border-rule"></div>
                <button id="btnTutupKamera"
                        class="mt-3 w-full flex items-center justify-center gap-2 border border-rule text-label py-2.5
                               font-sans text-[0.58rem] font-semibold tracking-[0.2em] uppercase
                               hover:bg-red-50 hover:border-red-200 hover:text-red-800 transition-all">
                    <i class="fas fa-xmark text-[0.5rem]"></i> Tutup Kamera
                </button>
            </div>
        </div>
    </div>

    {{-- Petunjuk --}}
    <div class="bg-paper border border-rule relative overflow-hidden">
        <div class="pointer-events-none absolute bottom-4 right-4 h-8 w-8 border-b border-r border-rule"></div>
        <div class="px-6 py-5">
            <p class="font-sans text-[0.5rem] font-semibold tracking-[0.28em] uppercase text-label mb-4">Petunjuk</p>
            <ol class="space-y-3">
                @foreach(['Scan QR Code yang menempel di alat','Sistem menampilkan data peminjaman aktif','Pilih kondisi alat saat dikembalikan','Klik tombol proses pengembalian'] as $i => $step)
                <li class="flex items-start gap-3">
                    <span class="w-5 h-5 bg-espresso text-paper flex items-center justify-center flex-shrink-0 mt-0.5
                                 font-sans text-[0.5rem] font-semibold">{{ $i+1 }}</span>
                    <span class="font-sans text-[0.72rem] text-dim leading-relaxed">{{ $step }}</span>
                </li>
                @endforeach
            </ol>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let scanner = null;
    const btnKamera  = document.getElementById('btnKamera');
    const btnTutup   = document.getElementById('btnTutupKamera');
    const areaKamera = document.getElementById('area-kamera');
    const qrInput    = document.getElementById('qrInput');

    function stopScanner() {
        if (scanner) { scanner.stop().catch(() => {}); scanner = null; }
        areaKamera.style.display = 'none';
        btnKamera.innerHTML = '<i class="fas fa-camera text-[0.5rem]"></i> Gunakan Kamera HP';
    }

    btnKamera.addEventListener('click', function() {
        if (scanner) { stopScanner(); return; }
        areaKamera.style.display = 'block';
        btnKamera.innerHTML = '<i class="fas fa-stop text-[0.5rem]"></i> Hentikan Kamera';
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
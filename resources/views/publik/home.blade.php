{{-- resources/views/publik/home.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Peminjaman Alat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

{{-- PWA Meta Tags --}}
<meta name="theme-color" content="#3b82f6">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="PinjamAlat">
<meta name="msapplication-TileColor" content="#3b82f6">
<meta name="msapplication-TileImage" content="/icons/icon-192.png">

{{-- Web Manifest --}}
<link rel="manifest" href="/manifest.json">

{{-- Apple Touch Icon --}}
<link rel="apple-touch-icon" href="/icons/icon-192.png">

{{-- Favicon --}}
<link rel="icon" type="image/png" href="/icons/icon-192.png">

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: #f0f4ff;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ── Tombol Login Admin ── */
        .btn-admin-login {
            position: fixed;
            top: 16px; right: 16px;
            z-index: 9999;
            font-size: .82rem;
            padding: 8px 18px;
            border-radius: 20px;
            background: #1e293b;
            border: none;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 4px 14px rgba(0,0,0,.2);
            transition: all .2s;
        }
        .btn-admin-login:hover {
            background: #3b82f6;
            color: #fff;
            transform: translateY(-1px);
        }

        /* ── Hero ── */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px 40px;
            text-align: center;
        }

        .hero-icon {
            width: 88px; height: 88px;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
            box-shadow: 0 12px 32px rgba(99,102,241,.3);
        }

        .hero-title {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 10px;
        }

        .hero-subtitle {
            font-size: 1rem;
            color: #64748b;
            max-width: 400px;
            margin: 0 auto 36px;
            line-height: 1.6;
        }

        /* ── Tombol Scan Utama ── */
        .btn-scan-utama {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: #fff;
            font-size: 1.05rem;
            font-weight: 700;
            padding: 14px 36px;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(99,102,241,.35);
            transition: all .2s;
            margin-bottom: 12px;
            text-decoration: none;
        }
        .btn-scan-utama:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(99,102,241,.45);
            color: #fff;
        }
        .btn-scan-utama:active {
            transform: scale(.97);
        }

        .scan-note {
            font-size: .78rem;
            color: #94a3b8;
            margin-bottom: 48px;
        }

        /* ── Steps ── */
        .steps {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
            max-width: 680px;
            margin: 0 auto 40px;
        }

        .step-card {
            background: #fff;
            border-radius: 16px;
            padding: 22px 18px;
            width: 148px;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            border: 1.5px solid #e2e8f0;
            transition: transform .2s;
        }
        .step-card:hover { transform: translateY(-3px); }

        .step-number {
            width: 28px; height: 28px;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
            font-weight: 700;
            margin: 0 auto 10px;
        }

        .step-icon { font-size: 1.6rem; display: block; margin-bottom: 8px; }
        .step-title { font-size: .82rem; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
        .step-desc { font-size: .73rem; color: #94a3b8; line-height: 1.5; }

        /* ── Info Box ── */
        .info-box {
            background: #fff;
            border-radius: 14px;
            padding: 16px 24px;
            max-width: 460px;
            margin: 0 auto 32px;
            border: 1.5px solid #e0e7ff;
            font-size: .83rem;
            color: #64748b;
            line-height: 1.6;
        }

        /* ── QR Scanner Modal ── */
        .scanner-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.85);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        .scanner-overlay.show { display: flex; }

        .scanner-box {
            background: #fff;
            border-radius: 20px;
            padding: 28px 24px 20px;
            width: 340px;
            max-width: 92vw;
            text-align: center;
        }

        .scanner-box h5 {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
            font-size: 1rem;
        }

        .scanner-box p {
            font-size: .8rem;
            color: #94a3b8;
            margin-bottom: 16px;
        }

        #qr-video-container {
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            background: #000;
            margin-bottom: 14px;
            position: relative;
        }

        #qr-video-container video {
            width: 100%;
            display: block;
        }

        /* Garis scanner animasi */
        .scan-line {
            position: absolute;
            left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #3b82f6, transparent);
            animation: scanMove 2s linear infinite;
            top: 0;
        }
        @keyframes scanMove {
            0%   { top: 10%; }
            50%  { top: 85%; }
            100% { top: 10%; }
        }

        /* Sudut scanner */
        .scan-corners {
            position: absolute;
            inset: 10%;
            border: none;
            pointer-events: none;
        }
        .scan-corners::before,
        .scan-corners::after,
        .corner-br::before,
        .corner-br::after {
            content: '';
            position: absolute;
            width: 24px; height: 24px;
            border-color: #3b82f6;
            border-style: solid;
        }
        .scan-corners::before { top: 0; left: 0; border-width: 3px 0 0 3px; border-radius: 3px 0 0 0; }
        .scan-corners::after  { top: 0; right: 0; border-width: 3px 3px 0 0; border-radius: 0 3px 0 0; }
        .corner-br::before { bottom: 0; left: 0; border-width: 0 0 3px 3px; border-radius: 0 0 0 3px; }
        .corner-br::after  { bottom: 0; right: 0; border-width: 0 3px 3px 0; border-radius: 0 0 3px 0; }

        #scan-status {
            font-size: .8rem;
            min-height: 24px;
            margin-bottom: 8px;
        }

        .btn-tutup-scanner {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            background: #f1f5f9;
            border: none;
            color: #475569;
            font-weight: 600;
            font-size: .875rem;
            cursor: pointer;
            transition: background .2s;
        }
        .btn-tutup-scanner:hover { background: #e2e8f0; }

        .footer-note {
            font-size: .73rem;
            color: #94a3b8;
        }

        @media (max-width: 480px) {
            .hero-title { font-size: 1.6rem; }
            .step-card { width: 138px; padding: 18px 12px; }
            .btn-scan-utama { font-size: .95rem; padding: 13px 28px; }
        }
    </style>
</head>
<body>

    {{-- ── Tombol Login Admin ── --}}
    <a href="{{ route('login') }}" class="btn-admin-login">
        <i class="bi bi-shield-lock-fill"></i> Petugas / Admin
    </a>

    {{-- ── Hero ── --}}
    <div class="hero">

        <div class="hero-icon">
            <i class="bi bi-tools text-white" style="font-size:2.2rem;"></i>
        </div>

        <h1 class="hero-title">Sistem Peminjaman Alat</h1>
        <p class="hero-subtitle">
            Pinjam alat dengan mudah dan cepat.<br>
            Cukup scan QR Code yang ada pada alat.
        </p>

        {{-- ── TOMBOL SCAN UTAMA ── --}}
        <button class="btn-scan-utama" onclick="bukaScanner()">
            <i class="bi bi-qr-code-scan" style="font-size:1.3rem;"></i>
            Scan QR untuk Meminjam
        </button>
        <p class="scan-note">
            <i class="bi bi-info-circle me-1"></i>
            Jika kamera HP bisa scan langsung, arahkan ke QR pada alat
        </p>

        {{-- Steps --}}
        <div class="steps">
            <div class="step-card">
                <div class="step-number">1</div>
                <span class="step-icon">📱</span>
                <div class="step-title">Scan QR</div>
                <div class="step-desc">Tap tombol scan di atas atau kamera HP</div>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <span class="step-icon">📝</span>
                <div class="step-title">Isi Form</div>
                <div class="step-desc">Isi nama, kelas, dan mata pelajaran</div>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <span class="step-icon">✅</span>
                <div class="step-title">Selesai</div>
                <div class="step-desc">Peminjaman tercatat otomatis</div>
            </div>
            <div class="step-card">
                <div class="step-number">4</div>
                <span class="step-icon">🔁</span>
                <div class="step-title">Kembalikan</div>
                <div class="step-desc">Serahkan ke petugas untuk scan balik</div>
            </div>
        </div>

        <div class="info-box">
            <i class="bi bi-lightbulb-fill me-2 text-warning"></i>
            <strong>Tips:</strong> Kamera HP modern bisa scan QR langsung tanpa aplikasi tambahan.
            Jika tidak bisa, gunakan tombol <strong>"Scan QR"</strong> di atas.
        </div>

        <p class="footer-note">
            Sistem Peminjaman Alat &mdash;
            {{ \App\Models\Pengaturan::ambil('nama_sekolah', 'Sekolah') }}
        </p>

    </div>

    {{-- ══════════════════════════════════════
         QR SCANNER OVERLAY (Modal fullscreen)
    ══════════════════════════════════════ --}}
    <div class="scanner-overlay" id="scannerOverlay">
        <div class="scanner-box">
            <h5><i class="bi bi-qr-code-scan me-2 text-primary"></i>Scan QR Alat</h5>
            <p>Arahkan kamera ke QR Code yang ada pada alat</p>

            <div id="qr-video-container">
                <video id="qr-video" playsinline autoplay muted></video>
                <div class="scan-line"></div>
                <div class="scan-corners">
                    <div class="corner-br"></div>
                </div>
            </div>

            <div id="scan-status" class="text-muted"></div>

            <button class="btn-tutup-scanner" onclick="tutupScanner()">
                <i class="bi bi-x-circle me-1"></i> Tutup
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Library scan QR pakai jsQR (murni JS, no dependency) --}}
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

    <script>
        let stream       = null;
        let scanInterval = null;
        let sudahScan    = false;

        const overlay    = document.getElementById('scannerOverlay');
        const video      = document.getElementById('qr-video');
        const statusEl   = document.getElementById('scan-status');

        // ── Buka Scanner ──
        async function bukaScanner() {
            sudahScan = false;
            overlay.classList.add('show');
            statusEl.textContent = '';

            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'environment', // Kamera belakang
                        width:  { ideal: 1280 },
                        height: { ideal: 720 },
                    }
                });

                video.srcObject = stream;
                await video.play();
                mulaiScan();

            } catch (err) {
                statusEl.innerHTML = `
                    <span class="text-danger">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Kamera tidak dapat diakses.<br>
                        <small>Pastikan izin kamera sudah diberikan.</small>
                    </span>`;
            }
        }

        // ── Mulai proses scan frame demi frame ──
        function mulaiScan() {
            const canvas  = document.createElement('canvas');
            const context = canvas.getContext('2d');

            scanInterval = setInterval(() => {
                if (sudahScan || video.readyState !== video.HAVE_ENOUGH_DATA) return;

                canvas.width  = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                const result    = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: 'dontInvert',
                });

                if (result) {
                    sudahScan = true;
                    prosesHasilScan(result.data);
                }
            }, 200); // Scan tiap 200ms
        }

        // ── Proses hasil QR yang berhasil di-scan ──
        function prosesHasilScan(teks) {
            statusEl.innerHTML = `
                <span class="text-success">
                    <i class="bi bi-check-circle me-1"></i>QR berhasil dibaca...
                </span>`;

            // Ekstrak hash dari URL format: /pinjam/qr/{hash64}
            const pattern = /\/pinjam\/qr\/([a-f0-9]{64})/;
            const match   = teks.match(pattern);

            if (match) {
                // QR valid dari sistem kita
                const hash = match[1];
                statusEl.innerHTML = `
                    <span class="text-success">
                        <i class="bi bi-arrow-right-circle me-1"></i>Mengarahkan ke form...
                    </span>`;
                setTimeout(() => {
                    hentikanKamera();
                    window.location.href = `/pinjam/qr/${hash}`;
                }, 600);

            } else {
                // QR tidak dikenal
                statusEl.innerHTML = `
                    <span class="text-danger">
                        <i class="bi bi-x-circle me-1"></i>QR Code tidak dikenal.
                        Pastikan scan QR yang ada pada alat.
                    </span>`;
                // Coba scan lagi setelah 2 detik
                setTimeout(() => {
                    sudahScan = false;
                    statusEl.textContent = '';
                }, 2000);
            }
        }

        // ── Tutup Scanner ──
        function tutupScanner() {
            hentikanKamera();
            overlay.classList.remove('show');
            statusEl.textContent = '';
        }

        function hentikanKamera() {
            if (scanInterval) {
                clearInterval(scanInterval);
                scanInterval = null;
            }
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            video.srcObject = null;
        }

        // Tutup jika klik di luar box scanner
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) tutupScanner();
        });

        // Tutup dengan tombol Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') tutupScanner();
        });
    </script>


{{-- Tombol Install App --}}
<div id="installBanner" style="
    display:none;
    position:fixed;
    bottom:20px;
    left:50%;
    transform:translateX(-50%);
    background:#1e293b;
    color:#fff;
    padding:12px 20px;
    border-radius:50px;
    box-shadow:0 8px 24px rgba(0,0,0,.25);
    z-index:9999;
    align-items:center;
    gap:12px;
    font-size:.875rem;
    white-space:nowrap;
">
    <i class="bi bi-download"></i>
    <span>Install Aplikasi PinjamAlat</span>
    <button id="btnInstall" style="
        background:#3b82f6;
        border:none;
        color:#fff;
        padding:6px 16px;
        border-radius:20px;
        font-weight:600;
        cursor:pointer;
        font-size:.8rem;
    ">Install</button>
    <button id="btnTutupInstall" style="
        background:transparent;
        border:none;
        color:#94a3b8;
        cursor:pointer;
        font-size:1rem;
        padding:0 4px;
    ">✕</button>
</div>

<script>
    let deferredPrompt = null;
    const installBanner = document.getElementById('installBanner');
    const btnInstall    = document.getElementById('btnInstall');
    const btnTutup      = document.getElementById('btnTutupInstall');

    // Tangkap event sebelum browser tampilkan prompt default
    window.addEventListener('beforeinstallprompt', e => {
        e.preventDefault();
        deferredPrompt = e;
        installBanner.style.display = 'flex';
    });

    // Klik Install
    btnInstall.addEventListener('click', async () => {
        if (!deferredPrompt) return;
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        if (outcome === 'accepted') {
            installBanner.style.display = 'none';
        }
        deferredPrompt = null;
    });

    // Tutup banner
    btnTutup.addEventListener('click', () => {
        installBanner.style.display = 'none';
    });

    // Sudah terinstall
    window.addEventListener('appinstalled', () => {
        installBanner.style.display = 'none';
        deferredPrompt = null;
    });

    // Handle shortcut scan dari manifest
    const params = new URLSearchParams(window.location.search);
    if (params.get('action') === 'scan') {
        window.addEventListener('load', () => {
            setTimeout(() => bukaScanner(), 500);
        });
    }
</script>

</body>
</html>
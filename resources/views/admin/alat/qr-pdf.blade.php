{{-- resources/views/admin/alat/qr-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>QR Code - {{ $alat->nama_alat }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #fff;
        }
        .qr-card {
            text-align: center;
            border: 2px solid #1e293b;
            border-radius: 12px;
            padding: 24px 28px;
            width: 260px;
        }
        .qr-card .sekolah {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: 10px;
        }
        .qr-card img {
            width: 200px;
            height: 200px;
            display: block;
            margin: 0 auto 12px;
        }
        .qr-card .nama-alat {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .qr-card .kode-alat {
            font-size: 10px;
            color: #64748b;
            font-family: monospace;
            background: #f1f5f9;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 8px;
        }
        .qr-card .instruksi {
            font-size: 8px;
            color: #94a3b8;
            line-height: 1.5;
        }
        .qr-card .divider {
            border-top: 1px dashed #e2e8f0;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="qr-card">
        @php $logo = \App\Models\Pengaturan::ambil('logo_sekolah'); @endphp
        @if($logo)
        <div style="margin-bottom: 8px;">
            <img src="{{ public_path('storage/' . $logo) }}"
                style="width: 48px; height: 48px; object-fit: contain; margin: 0 auto; display: block;">
        </div>
        @endif
        <div class="sekolah">{{ \App\Models\Pengaturan::ambil('nama_sekolah', 'Sistem Peminjaman') }}</div>

        <img src="data:{{ $qrMime }};base64,{{ $qrBase64 }}" alt="QR Code">
        
        
        {{-- Tambahkan nomor urut di bawah nama alat --}}
        <div class="nama-alat">{{ $alat->nama_alat }}</div>

        @if($alat->nomor_urut)
        <div style="
            font-size: 16px; 
            font-weight: bold; 
            color: #64748b; /* Warna abu-abu agar terlihat transparan/pudar */
            margin-bottom: 8px;
        ">
            #{{ $alat->nomor_urut }}
        </div>
        @endif

        <div class="kode-alat">{{ $alat->kode_alat }}</div>


        <div class="divider"></div>
        <div class="instruksi">
            Scan QR ini untuk meminjam alat.<br>
            Jangan rusak atau hilangkan stiker ini.
        </div>
    </div>
</body>
</html>
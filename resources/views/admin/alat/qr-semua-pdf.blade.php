{{-- resources/views/admin/alat/qr-semua-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>QR Code Semua Alat</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; background: #fff; padding: 16px; }
        h1 {
            font-size: 13px;
            text-align: center;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .sub {
            font-size: 9px;
            text-align: center;
            color: #94a3b8;
            margin-bottom: 16px;
        }
        .grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .grid-row { display: table-row; }
        .grid-cell {
            display: table-cell;
            width: 25%;
            padding: 8px;
            vertical-align: top;
            text-align: center;
        }
        .qr-item {
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 8px;
        }
        .qr-item img {
            width: 120px;
            height: 120px;
            display: block;
            margin: 0 auto 6px;
        }
        .qr-item .nama {
            font-size: 9px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .qr-item .kode {
            font-size: 7.5px;
            color: #64748b;
            font-family: monospace;
            background: #f8fafc;
            padding: 2px 5px;
            border-radius: 3px;
        }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <h1>QR Code Semua Alat</h1>
    <p class="sub">
        {{ \App\Models\Pengaturan::ambil('nama_sekolah', 'Sistem Peminjaman') }}
        &mdash; Dicetak: {{ now()->format('d/m/Y H:i') }}
    </p>

    {{-- Grid 4 kolom, setiap 8 item ganti halaman --}}
    @php $items = collect($alatDenganQr); $chunks = $items->chunk(8); @endphp

    @foreach($chunks as $pageIndex => $page)
        <div class="grid">
            @foreach($page->chunk(4) as $row)
            <div class="grid-row">
                @foreach($row as $data)
                <div class="grid-cell">
                    <div class="qr-item">
                        <img src="data:{{ $data['qrMime'] }};base64,{{ $data['qrBase64'] }}" alt="QR">
                        <div class="nama">{{ $data['alat']->nama_alat }}</div>


                        {{-- ✅ Tambahkan nomor urut di sini juga --}}
                        @if($data['alat']->nomor_urut)
                        <div style="
                            font-size: 10px;
                            font-weight: bold;
                            color: #1a1a1a;
                            background: #f5f0e8;
                            padding: 2px 4px;
                            margin: 2px 0;
                            border-radius: 2px;
                        ">
                            #{{ $data['alat']->nomor_urut }}
                        </div>
                        @endif


                        <div class="kode">{{ $data['alat']->kode_alat }}</div>
                    </div>
                </div>
                @endforeach
                {{-- Isi sel kosong jika kurang dari 4 --}}
                @for($i = $row->count(); $i < 4; $i++)
                <div class="grid-cell"></div>
                @endfor
            </div>
            @endforeach
        </div>
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>
</html>
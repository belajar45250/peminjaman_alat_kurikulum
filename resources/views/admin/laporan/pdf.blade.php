{{-- resources/views/admin/laporan/pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1e293b; }
        .header {
            text-align: center;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #1e293b;
        }
        .header h2 { font-size: 14px; font-weight: bold; }
        .header p  { font-size: 9px; color: #64748b; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #1e293b; color: #fff; }
        thead th { padding: 6px 8px; font-size: 9px; text-align: left; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; font-size: 9px; }
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: bold; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef9c3; color: #854d0e; }
        .badge-danger  { background: #fee2e2; color: #991b1b; }
        .total-box { margin-top: 10px; text-align: right; font-size: 10px; }
        .total-box span { font-weight: bold; color: #dc2626; }
        .footer {
            margin-top: 14px;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ strtoupper($namaSekolah) }}</h2>
        <p>Laporan Peminjaman Alat &mdash; Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
        <p>Filter: {{ $filterInfo }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">#</th>
                <th width="14%">Kode Transaksi</th>
                <th width="15%">Alat</th>
                <th width="13%">Peminjam</th>
                <th width="8%">Kelas</th>
                <th width="10%">Mapel</th>
                <th width="9%">Jam Pinjam</th>
                <th width="9%">Tgl Pinjam</th>
                <th width="9%">Tgl Kembali</th>
                <th width="6%">Status</th>
                <th width="8%">Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->kode_transaksi }}</td>
                <td>{{ $item->nama_alat_snapshot }}</td>
                <td>{{ $item->nama_peminjam }}</td>
                <td>{{ $item->kelas }}</td>
                <td>{{ $item->mata_pelajaran }}</td>
                <td>
                    @if($item->waktu_mulai_pinjam)
                        Jam ke-{{ $item->jam_pelajaran_mulai }}
                        ({{ $item->waktu_mulai_pinjam }}-{{ $item->waktu_selesai_pinjam }})
                    @else
                        -
                    @endif
                </td>
                <td>{{ $item->waktu_pinjam->format('d/m/Y') }}</td>
                <td>{{ $item->pengembalian?->waktu_kembali->format('d/m/Y') ?? '-' }}</td>
                <td>
                    @if($item->status === 'dipinjam')
                        <span class="badge badge-warning">Dipinjam</span>
                    @else
                        <span class="badge badge-success">Kembali</span>
                    @endif
                </td>
                <td>
                    @if($item->pengembalian?->ada_denda)
                        <span style="color:#dc2626;font-weight:bold;">
                            Rp {{ number_format($item->pengembalian->jumlah_denda, 0, ',', '.') }}
                        </span>
                        @if(!$item->pengembalian->denda_lunas)
                            <br><span class="badge badge-danger">Blm Lunas</span>
                        @endif
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($totalDenda > 0)
    <div class="total-box">
        Total Denda: <span>Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
    </div>
    @endif

    <div class="footer">
        <span>Total: {{ $laporan->count() }} transaksi</span>
        <span>{{ $namaSekolah }} &copy; {{ date('Y') }}</span>
    </div>
</body>
</html>
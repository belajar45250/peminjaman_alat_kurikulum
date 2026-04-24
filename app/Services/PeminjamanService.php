<?php
// app/Services/PeminjamanService.php

namespace App\Services;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Pengaturan;
use App\Models\HistoryKerusakan;
use Illuminate\Support\Facades\DB;

class PeminjamanService
{
    public function __construct(
        private readonly QrValidasiService $qrValidasi
    ) {}

    /**
     * Proses peminjaman baru.
     */
   public function prosesPeminjaman(array $data, string $qrHash): Peminjaman
{
    return DB::transaction(function () use ($data, $qrHash) {
        $validasi = $this->qrValidasi->validasiUntukPeminjaman($qrHash);
        if (!$validasi['valid']) throw new \RuntimeException($validasi['pesan']);

        $alat = Alat::lockForUpdate()->find($validasi['alat']->id);
        if ($alat->status !== 'tersedia') {
            throw new \RuntimeException('Alat baru saja dipinjam oleh orang lain.');
        }

        // Ambil jam pelajaran dari Pengaturan, bukan config
        $jamPelajaran = Pengaturan::getJamTersedia();
        
        $jamMulai   = $jamPelajaran[$data['jam_pelajaran_mulai'] - 1] ?? null;
        $jamSelesai = $jamPelajaran[$data['jam_pelajaran_selesai'] - 1] ?? null;

        if (!$jamMulai || !$jamSelesai) {
            throw new \RuntimeException('Jam pelajaran tidak valid.');
        }

        // Estimasi kembali = jam selesai pelajaran hari ini
        $estimasiKembali = now()->setTimeFromTimeString($jamSelesai['selesai']);

        // Jika waktu selesai sudah lewat hari ini, set ke besok
        if ($estimasiKembali->isPast()) {
            $estimasiKembali->addDay();
        }

        $peminjaman = Peminjaman::create([
            'alat_id'               => $alat->id,
            'kode_alat_snapshot'    => $alat->kode_alat,
            'nama_alat_snapshot'    => $alat->nama_alat,
            'qr_hash_snapshot'      => $alat->qr_hash,
            'nama_peminjam'         => $data['nama_peminjam'],
            'kelas'                 => $data['kelas'],
            'mata_pelajaran'        => $data['mata_pelajaran'],
            'guru_pengampu'         => $data['guru_pengampu'] ?? null,
            'jam_pelajaran_mulai'   => $data['jam_pelajaran_mulai'],
            'jam_pelajaran_selesai' => $data['jam_pelajaran_selesai'],
            'waktu_mulai_pinjam'    => $jamMulai['mulai'],
            'waktu_selesai_pinjam'  => $jamSelesai['selesai'],
            'keperluan'             => $data['keperluan'] ?? null,
            'waktu_pinjam'          => now(),
            'estimasi_kembali'      => $estimasiKembali,
            'status'                => 'dipinjam',
            'ip_peminjam'           => request()->ip(),
            'user_agent'            => request()->userAgent(),
        ]);

        $alat->update(['status' => 'dipinjam']);

        return $peminjaman;
    });
}

    /**
     * Proses pengembalian.
     */
    public function prosespengembalian(array $data, string $qrHash): Pengembalian
    {
        return DB::transaction(function () use ($data, $qrHash) {
            $validasi = $this->qrValidasi->validasiUntukPengembalian($qrHash);

            if (!$validasi['valid']) {
                throw new \RuntimeException($validasi['pesan']);
            }

            $alat = $validasi['alat'];
            $peminjaman = $validasi['peminjaman'];

            // Lock rows untuk mencegah double return
            $peminjaman = Peminjaman::lockForUpdate()->find($peminjaman->id);
            $alat = Alat::lockForUpdate()->find($alat->id);

            // Re-check setelah lock
            if ($peminjaman->status !== 'dipinjam') {
                throw new \RuntimeException('Transaksi ini sudah tidak aktif.');
            }

            if ($peminjaman->pengembalian()->exists()) {
                throw new \RuntimeException('Pengembalian sudah diproses sebelumnya.');
            }

            // Hitung denda
            $kondisiKembali = $data['kondisi_kembali'];
            $dendaData = $this->hitungDenda($alat, $kondisiKembali);

            // Buat record pengembalian
            $pengembalian = Pengembalian::create([
                'peminjaman_id'       => $peminjaman->id,
                'alat_id'             => $alat->id,
                'qr_hash_dikembalikan' => $qrHash, // Audit trail
                'waktu_kembali'       => now(),
                'kondisi_kembali'     => $kondisiKembali,
                'catatan'             => $data['catatan'] ?? null,
                'ada_denda'           => $dendaData['ada_denda'],
                'jenis_denda'         => $dendaData['jenis_denda'],
                'persentase_denda'    => $dendaData['persentase'],
                'harga_alat_snapshot' => $alat->harga,
                'jumlah_denda'        => $dendaData['jumlah'],
                'denda_lunas'         => !$dendaData['ada_denda'],
                'diproses_oleh'       => auth()->id(),
            ]);

            if (in_array($kondisiKembali, ['rusak_berat', 'hilang'])) {
                $this->catatKerusakan($pengembalian, $alat, $peminjaman, $kondisiKembali);
            }

            // Update status peminjaman
            $peminjaman->update(['status' => 'dikembalikan']);

            // Update status & kondisi alat
            $alat->update([
                'status'  => 'tersedia',
                'kondisi' => $this->mapKondisi($kondisiKembali),
            ]);

            return $pengembalian;
        });
    }

    // ── Tambah method baru ──
private function catatKerusakan(
    Pengembalian $pengembalian,
    Alat $alat,
    Peminjaman $peminjaman,
    string $kondisi
): void {
    HistoryKerusakan::create([
        'alat_id'             => $alat->id,
        'pengembalian_id'     => $pengembalian->id,
        'nama_alat_snapshot'  => $alat->nama_alat,
        'kode_alat_snapshot'  => $alat->kode_alat,
        'nama_peminjam'       => $peminjaman->nama_peminjam,
        'kelas'               => $peminjaman->kelas,
        'jenis_kerusakan'     => $kondisi === 'hilang' ? 'hilang' : 'rusak_berat',
        'kondisi_sebelum'     => 'baik',
        'deskripsi_kerusakan' => $kondisi === 'hilang'
                                    ? 'Alat hilang saat dipinjam.'
                                    : 'Alat dikembalikan dalam kondisi rusak berat.',
        'foto_kerusakan'      => null,
        'harga_alat'          => $alat->harga,
        'jumlah_denda'        => $pengembalian->jumlah_denda,
        'status_denda'        => $pengembalian->jumlah_denda > 0 ? 'belum_lunas' : 'tidak_ada',
        'status_tindak_lanjut' => 'menunggu',
        'dicatat_oleh'        => auth()->id(),
        'tanggal_rusak'       => now(),
    ]);
}

    private function hitungDenda(Alat $alat, string $kondisi): array
    {
        if ($kondisi === 'baik' || $kondisi === 'rusak_ringan') {
            return ['ada_denda' => false, 'jenis_denda' => 'tidak_ada', 'persentase' => 0, 'jumlah' => 0];
        }

        if ($kondisi === 'hilang') {
            $persen = (float) Pengaturan::ambil('persentase_denda_hilang', 100);
            return [
                'ada_denda'  => true,
                'jenis_denda' => 'hilang',
                'persentase' => $persen,
                'jumlah'     => $alat->harga * ($persen / 100),
            ];
        }

        if ($kondisi === 'rusak_berat') {
            $persen = (float) Pengaturan::ambil('persentase_denda_rusak', 30);
            return [
                'ada_denda'  => true,
                'jenis_denda' => 'rusak',
                'persentase' => $persen,
                'jumlah'     => $alat->harga * ($persen / 100),
            ];
        }

        return ['ada_denda' => false, 'jenis_denda' => 'tidak_ada', 'persentase' => 0, 'jumlah' => 0];
    }

    private function mapKondisi(string $kondisiKembali): string
    {
        return match($kondisiKembali) {
            'hilang'      => 'tidak_tersedia',
            'rusak_berat' => 'rusak_berat',
            'rusak_ringan' => 'rusak_ringan',
            default       => 'baik',
        };
    }
}
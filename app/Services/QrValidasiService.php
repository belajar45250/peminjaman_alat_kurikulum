<?php
// app/Services/QrValidasiService.php

namespace App\Services;

use App\Models\Alat;
use App\Models\Peminjaman;

class QrValidasiService
{
    /**
     * Validasi QR untuk PEMINJAMAN.
     * Return: ['valid' => bool, 'alat' => Alat|null, 'pesan' => string]
     */
    public function validasiUntukPeminjaman(string $qrHash): array
    {
        // 1. Cari alat berdasarkan qr_hash
        $alat = Alat::where('qr_hash', $qrHash)
                    ->whereNull('deleted_at')
                    ->first();

        if (!$alat) {
            return [
                'valid' => false,
                'alat' => null,
                'pesan' => 'QR Code tidak valid atau alat tidak ditemukan.',
            ];
        }

        // 2. Verifikasi hash integritas
        if (!$alat->verifikasiQrHash($qrHash)) {
            return [
                'valid' => false,
                'alat' => null,
                'pesan' => 'QR Code tidak valid. Integritas gagal.',
            ];
        }

        // 3. Cek apakah alat sedang dipinjam (CEK GANDA: status + tabel peminjaman)
        if ($alat->status === 'dipinjam') {
            return [
                'valid' => false,
                'alat' => $alat,
                'pesan' => 'Alat ini sedang dipinjam dan tidak dapat dipinjam lagi.',
            ];
        }

        // Validasi silang: pastikan tidak ada transaksi aktif di DB
        $transaksiAktif = Peminjaman::where('alat_id', $alat->id)
                                    ->where('status', 'dipinjam')
                                    ->exists();

        if ($transaksiAktif) {
            // Inkonsistensi data! Perbaiki status alat
            $alat->update(['status' => 'dipinjam']);
            return [
                'valid' => false,
                'alat' => $alat,
                'pesan' => 'Alat ini sedang dalam proses peminjaman aktif.',
            ];
        }

        // 4. Cek kondisi alat
        if ($alat->kondisi === 'tidak_tersedia') {
            return [
                'valid' => false,
                'alat' => $alat,
                'pesan' => 'Alat ini tidak tersedia untuk dipinjam.',
            ];
        }

        return [
            'valid' => true,
            'alat' => $alat,
            'pesan' => 'Alat tersedia.',
        ];
    }

    /**
     * Validasi QR untuk PENGEMBALIAN.
     */
    public function validasiUntukPengembalian(string $qrHash): array
    {
        // 1. Cari alat
        $alat = Alat::where('qr_hash', $qrHash)
                    ->whereNull('deleted_at')
                    ->first();

        if (!$alat) {
            return [
                'valid' => false,
                'alat' => null,
                'peminjaman' => null,
                'pesan' => 'QR Code tidak valid.',
            ];
        }

        // 2. Verifikasi hash
        if (!$alat->verifikasiQrHash($qrHash)) {
            return [
                'valid' => false,
                'alat' => null,
                'peminjaman' => null,
                'pesan' => 'QR Code gagal verifikasi.',
            ];
        }

        // 3. Cek apakah alat sedang dipinjam
        if ($alat->status !== 'dipinjam') {
            return [
                'valid' => false,
                'alat' => $alat,
                'peminjaman' => null,
                'pesan' => 'Alat ini tidak sedang dipinjam.',
            ];
        }

        // 4. Cari transaksi aktif - HARUS berdasarkan alat_id DAN qr_hash_snapshot
        // Ini mencegah bug: scan QR A tapi mengembalikan alat B
        $peminjaman = Peminjaman::where('alat_id', $alat->id)
                                ->where('qr_hash_snapshot', $qrHash) // KRITIS
                                ->where('status', 'dipinjam')
                                ->with('alat')
                                ->first();

        if (!$peminjaman) {
            return [
                'valid' => false,
                'alat' => $alat,
                'peminjaman' => null,
                'pesan' => 'Tidak ada transaksi aktif yang cocok untuk QR ini.',
            ];
        }

        // 5. Cek apakah sudah ada pengembalian (double return prevention)
        if ($peminjaman->pengembalian()->exists()) {
            return [
                'valid' => false,
                'alat' => $alat,
                'peminjaman' => $peminjaman,
                'pesan' => 'Alat ini sudah tercatat dikembalikan sebelumnya.',
            ];
        }

        return [
            'valid' => true,
            'alat' => $alat,
            'peminjaman' => $peminjaman,
            'pesan' => 'Siap diproses pengembalian.',
        ];
    }
}
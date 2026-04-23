<?php
// app/Console/Commands/CekKeterlambatan.php

namespace App\Console\Commands;

use App\Models\Notifikasi;
use App\Models\Peminjaman;
use Illuminate\Console\Command;

class CekKeterlambatan extends Command
{
    protected $signature   = 'pinjam:cek-terlambat';
    protected $description = 'Cek peminjaman yang melewati jam selesai dan buat notifikasi';

    public function handle(): void
    {
        $sekarang = now();

        // Ambil semua peminjaman aktif yang sudah lewat estimasi kembali
        $terlambat = Peminjaman::where('status', 'dipinjam')
            ->where('estimasi_kembali', '<', $sekarang)
            ->whereDoesntHave('notifikasiTerlambat') // Belum dinotifikasi
            ->with('alat')
            ->get();

        foreach ($terlambat as $peminjaman) {
            $selisihMenit = (int) $sekarang->diffInMinutes($peminjaman->estimasi_kembali);
            $jam          = floor($selisihMenit / 60);
            $menit        = $selisihMenit % 60;
            $durasi       = $jam > 0 ? "{$jam} jam {$menit} menit" : "{$menit} menit";

            Notifikasi::create([
                'peminjaman_id' => $peminjaman->id,
                'judul'         => "Keterlambatan: {$peminjaman->nama_alat_snapshot}",
                'pesan'         => "{$peminjaman->nama_peminjam} ({$peminjaman->kelas}) terlambat {$durasi}. "
                                 . "Seharusnya dikembalikan pukul {$peminjaman->estimasi_kembali->format('H:i')}.",
                'tipe'          => 'terlambat',
                'sudah_dibaca'  => false,
            ]);
        }

        $this->info("Selesai. {$terlambat->count()} notifikasi dibuat.");
    }
}
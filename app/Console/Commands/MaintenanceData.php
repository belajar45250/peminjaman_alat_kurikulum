<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Peminjaman;
use App\Models\HistoryKerusakan;
use App\Models\Notifikasi;
use App\Models\Pengembalian;
use Carbon\Carbon;

class MaintenanceData extends Command
{
    // Nama perintah untuk dijalankan di terminal
    protected $signature = 'maintenance:clean';

    // Deskripsi perintah
    protected $description = 'Backup riwayat peminjaman dan kerusakan lebih dari 1 tahun ke JSON lalu hapus dari database';

   public function handle()
    {
        // Gunakan addDay() karena kamu sedang testing, 
        // Jangan lupa ganti kembali ke subYear() kalau sudah selesai tes!
        $batasWaktu = Carbon::now()->subYear(); 
        
        $peminjamanLama = Peminjaman::where('created_at', '<', $batasWaktu)->get();
        $kerusakanLama = HistoryKerusakan::where('created_at', '<', $batasWaktu)->get();

        if ($peminjamanLama->isEmpty() && $kerusakanLama->isEmpty()) {
            $this->info('Tidak ada data lama yang perlu di-backup.');
            return;
        }

        $backupData = [
            'tanggal_backup'    => now()->toDateTimeString(),
            'total_peminjaman'  => $peminjamanLama->count(),
            'total_kerusakan'   => $kerusakanLama->count(),
            'data_peminjaman'   => $peminjamanLama,
            'data_kerusakan'    => $kerusakanLama,
        ];

        // Simpan ke storage/app/backups/
        $fileName = 'backup_riwayat_' . now()->format('Y_m_d_His') . '.json';
        Storage::disk('local')->put('backups/' . $fileName, json_encode($backupData, JSON_PRETTY_PRINT));

        // ========================================================
        // PROSES HAPUS DATA (DARI ANAK KE INDUK AGAR TIDAK ERROR)
        // ========================================================
        
        $peminjamanIds = $peminjamanLama->pluck('id');
        $kerusakanIds = $kerusakanLama->pluck('id');

        // 1. Hapus Notifikasi yang terkait peminjaman (jika ada)
        if ($peminjamanIds->isNotEmpty()) {
            \App\Models\Notifikasi::whereIn('peminjaman_id', $peminjamanIds)->delete();
        }

        // 2. Hapus History Kerusakan (karena bisa terkait dengan pengembalian/alat)
        if ($kerusakanIds->isNotEmpty()) {
            HistoryKerusakan::whereIn('id', $kerusakanIds)->delete();
        }

        // 3. Hapus Pengembalian yang terkait peminjaman
        if ($peminjamanIds->isNotEmpty()) {
            \App\Models\Pengembalian::whereIn('peminjaman_id', $peminjamanIds)->delete();
        }

        // 4. Terakhir, Hapus Peminjaman (Induk Utama)
        if ($peminjamanIds->isNotEmpty()) {
            Peminjaman::whereIn('id', $peminjamanIds)->delete();
        }

        $this->info("Berhasil! Data di-backup ke storage/app/backups/{$fileName} dan dibersihkan dari database.");
    }
}
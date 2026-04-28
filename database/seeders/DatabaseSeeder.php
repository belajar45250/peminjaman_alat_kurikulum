<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengaturan; // Pastikan ini di-import

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =================================================================
        // 1. DATA KELAS (Dikelompokkan berdasarkan Tingkat)
        // =================================================================
        $daftarKelas = [
            0 => [ // Tingkat 1 (Kelas X) - Mengambil dari datamu sebelumnya
                'X TK', 'X TM 1', 'X TM 2', 'X TM 3', 'X TO 1', 'X TO 2', 'X TO 3', 'X TO 4', 
                'X TJKT 1', 'X TJKT 2', 'X TJKT 3', 'X PPLG 1', 'X PPLG 2', 'X MPLB 1', 'X MPLB 2'
            ],
            1 => [ // Tingkat 2 (Kelas XI) - Mengambil dari datamu sebelumnya
                'XI TITL', 'XI TP 1', 'XI TP 2', 'XI TKR', 'XI TSM', 
                'XI TKJ 1', 'XI TKJ 2', 'XI TKJ 3', 'XI RPL 1', 'XI RPL 2', 'XI MP 1', 'XI MP 2'
            ],
            2 => [ // Tingkat 3 (Kelas XII) - Berdasarkan Screenshot Terbaru
                'XII TITL', 'XII TP 1', 'XII TP 2', 'XII TKR', 'XII TSM 1', 'XII TSM 2', 
                'XII TKJ 1', 'XII TKJ 2', 'XII TKJ 3', 'XII RPL 1', 'XII RPL 2', 'XII MP 1', 'XII MP 2'
            ]
        ];

        Pengaturan::simpan('daftar_kelas', json_encode($daftarKelas));


        // =================================================================
        // 2. DATA JAM PELAJARAN (Berdasarkan Screenshot Terbaru)
        // =================================================================
        $jamPelajaran = [
            ['ke' => 1,  'mulai' => '06:45', 'selesai' => '07:25'],
            ['ke' => 2,  'mulai' => '07:25', 'selesai' => '08:05'],
            ['ke' => 3,  'mulai' => '08:05', 'selesai' => '08:45'],
            ['ke' => 4,  'mulai' => '08:45', 'selesai' => '09:25'],
            // 09:25 - 09:45 Istirahat 1
            ['ke' => 5,  'mulai' => '09:45', 'selesai' => '10:25'],
            ['ke' => 6,  'mulai' => '10:25', 'selesai' => '11:05'],
            ['ke' => 7,  'mulai' => '11:05', 'selesai' => '11:45'],
            // 11:45 - 12:45 Istirahat 2
            ['ke' => 8,  'mulai' => '12:45', 'selesai' => '13:25'],
            ['ke' => 9,  'mulai' => '13:25', 'selesai' => '14:05'],
            ['ke' => 10, 'mulai' => '14:05', 'selesai' => '14:45'],
            ['ke' => 11, 'mulai' => '14:45', 'selesai' => '15:25'],
        ];

        Pengaturan::simpan('jam_pelajaran', json_encode($jamPelajaran));

        $this->command->info('Data Pengaturan (Kelas & Jam Pelajaran) berhasil di-seed!');
    }
}
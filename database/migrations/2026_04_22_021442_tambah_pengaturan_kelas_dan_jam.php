<?php
// database/migrations/xxxx_tambah_pengaturan_kelas_dan_jam.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Seed data awal kelas & jam dari config ke database
        $kelasDefault = [
            ['X RPL 1','X RPL 2','X TKJ 1','X TKJ 2','X AKL 1','X AKL 2'],
            ['XI RPL 1','XI RPL 2','XI TKJ 1','XI TKJ 2','XI AKL 1','XI AKL 2'],
            ['XII RPL 1','XII RPL 2','XII TKJ 1','XII TKJ 2','XII AKL 1','XII AKL 2'],
        ];

        $jamDefault = [
            ['ke' => 1, 'mulai' => '06:45', 'selesai' => '07:30'],
            ['ke' => 2, 'mulai' => '07:30', 'selesai' => '08:15'],
            ['ke' => 3, 'mulai' => '08:15', 'selesai' => '09:00'],
            ['ke' => 4, 'mulai' => '09:00', 'selesai' => '09:45'],
            ['ke' => 5, 'mulai' => '10:00', 'selesai' => '10:45'],
            ['ke' => 6, 'mulai' => '10:45', 'selesai' => '11:30'],
            ['ke' => 7, 'mulai' => '12:30', 'selesai' => '13:15'],
            ['ke' => 8, 'mulai' => '13:15', 'selesai' => '14:00'],
        ];

        DB::table('pengaturan')->insert([
            [
                'kunci'       => 'daftar_kelas',
                'nilai'       => json_encode($kelasDefault),
                'deskripsi'   => 'Daftar kelas untuk dropdown peminjaman (JSON array)',
                'tipe'        => 'json',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'kunci'       => 'jam_pelajaran',
                'nilai'       => json_encode($jamDefault),
                'deskripsi'   => 'Jadwal jam pelajaran (JSON array)',
                'tipe'        => 'json',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('pengaturan')->whereIn('kunci', ['daftar_kelas', 'jam_pelajaran'])->delete();
    }
};
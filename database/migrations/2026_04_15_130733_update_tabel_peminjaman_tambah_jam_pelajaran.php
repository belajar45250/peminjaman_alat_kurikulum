<?php
// database/migrations/xxxx_update_tabel_peminjaman_tambah_jam_pelajaran.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Hapus nomor_hp
            $table->dropColumn('nomor_hp');

            // Tambah jam pelajaran
            $table->tinyInteger('jam_pelajaran_mulai')->unsigned()
                  ->after('mata_pelajaran')
                  ->comment('Jam pelajaran ke berapa mulai pinjam (1-8)');

            $table->tinyInteger('jam_pelajaran_selesai')->unsigned()
                  ->after('jam_pelajaran_mulai')
                  ->comment('Jam pelajaran ke berapa selesai pakai');

            $table->string('waktu_mulai_pinjam', 5)
                  ->after('jam_pelajaran_selesai')
                  ->comment('HH:MM jam mulai, misal 06:45');

            $table->string('waktu_selesai_pinjam', 5)
                  ->after('waktu_mulai_pinjam')
                  ->comment('HH:MM jam selesai, misal 09:00');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->string('nomor_hp', 20)->nullable();
            $table->dropColumn([
                'jam_pelajaran_mulai',
                'jam_pelajaran_selesai',
                'waktu_mulai_pinjam',
                'waktu_selesai_pinjam',
            ]);
        });
    }
};
<?php
// database/migrations/2026_04_15_130733_update_tabel_peminjaman_tambah_jam_pelajaran.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Hapus nomor_hp
            if (Schema::hasColumn('peminjaman', 'nomor_hp')) {
                $table->dropColumn('nomor_hp');
            }

            // Tambah semua kolom baru sebagai NULLABLE dulu
            $table->tinyInteger('jam_pelajaran_mulai')->unsigned()->nullable()
                  ->after('mata_pelajaran');
            $table->tinyInteger('jam_pelajaran_selesai')->unsigned()->nullable()
                  ->after('jam_pelajaran_mulai');
            $table->string('waktu_mulai_pinjam', 5)->nullable()
                  ->after('jam_pelajaran_selesai');
            $table->string('waktu_selesai_pinjam', 5)->nullable()
                  ->after('waktu_mulai_pinjam');
        });

        // Isi data lama dengan nilai default agar tidak null
        DB::table('peminjaman')->whereNull('jam_pelajaran_mulai')->update([
            'jam_pelajaran_mulai'   => 1,
            'jam_pelajaran_selesai' => 1,
            'waktu_mulai_pinjam'    => '06:45',
            'waktu_selesai_pinjam'  => '07:30',
        ]);

        // Sekarang baru ubah jadi NOT NULL
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->tinyInteger('jam_pelajaran_mulai')->unsigned()->nullable(false)->change();
            $table->tinyInteger('jam_pelajaran_selesai')->unsigned()->nullable(false)->change();
            $table->string('waktu_mulai_pinjam', 5)->nullable(false)->change();
            $table->string('waktu_selesai_pinjam', 5)->nullable(false)->change();
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
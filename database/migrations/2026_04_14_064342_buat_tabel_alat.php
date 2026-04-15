<?php
// database/migrations/2024_01_01_000001_buat_tabel_alat.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alat', function (Blueprint $table) {
            $table->id();
            $table->string('kode_alat', 20)->unique()->comment('Kode unik alat, digunakan untuk QR Code');
            $table->string('nama_alat', 150);
            $table->text('deskripsi')->nullable();
            $table->string('kategori', 100)->nullable();
            $table->decimal('harga', 15, 2)->default(0)->comment('Harga alat untuk kalkulasi denda');
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'tidak_tersedia'])->default('baik');
            $table->enum('status', ['tersedia', 'dipinjam'])->default('tersedia');
            $table->string('lokasi_penyimpanan', 100)->nullable();
            $table->string('gambar', 255)->nullable();
            // Hash untuk verifikasi integritas QR - mencegah QR tertukar
            $table->string('qr_hash', 64)->unique()->comment('SHA256 hash dari kode_alat untuk validasi QR');
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('kondisi');
            $table->index('kode_alat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
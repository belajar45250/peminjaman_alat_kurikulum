<?php
// database/migrations/2024_01_01_000004_buat_tabel_pengembalian.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengembalian', 30)->unique();
            
            $table->foreignId('peminjaman_id')
                  ->unique() // CRITICAL: 1 peminjaman hanya boleh 1 pengembalian
                  ->constrained('peminjaman')
                  ->restrictOnDelete();
            
            $table->foreignId('alat_id')
                  ->constrained('alat')
                  ->restrictOnDelete();
            
            // Validasi silang: pastikan QR yang di-scan cocok dengan peminjaman
            $table->string('qr_hash_dikembalikan', 64)->comment('QR Hash alat yang di-scan saat pengembalian');
            
            $table->timestamp('waktu_kembali')->useCurrent();
            $table->enum('kondisi_kembali', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->default('baik');
            $table->text('catatan')->nullable();
            
            // Denda
            $table->boolean('ada_denda')->default(false);
            $table->enum('jenis_denda', ['tidak_ada', 'rusak', 'hilang'])->default('tidak_ada');
            $table->decimal('persentase_denda', 5, 2)->default(0)->comment('% denda saat transaksi');
            $table->decimal('harga_alat_snapshot', 15, 2)->default(0)->comment('Harga alat saat pengembalian');
            $table->decimal('jumlah_denda', 15, 2)->default(0);
            $table->boolean('denda_lunas')->default(false);
            
            // Admin yang memproses
            $table->foreignId('diproses_oleh')
                  ->constrained('users')
                  ->comment('Admin yang memproses pengembalian');
            
            $table->timestamps();
            
            $table->index('waktu_kembali');
            $table->index(['alat_id', 'waktu_kembali']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
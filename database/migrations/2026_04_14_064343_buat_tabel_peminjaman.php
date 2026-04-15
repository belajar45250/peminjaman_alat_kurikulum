<?php
// database/migrations/2024_01_01_000003_buat_tabel_peminjaman.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 30)->unique()->comment('Kode unik transaksi peminjaman');
            
            // Foreign key ke tabel alat
            $table->foreignId('alat_id')
                  ->constrained('alat')
                  ->restrictOnDelete()
                  ->comment('Relasi ke tabel alat');
            
            // Snapshot data alat saat peminjaman (anti bug jika alat diedit)
            $table->string('kode_alat_snapshot', 20)->comment('Kode alat saat transaksi dibuat');
            $table->string('nama_alat_snapshot', 150)->comment('Nama alat saat transaksi dibuat');
            $table->string('qr_hash_snapshot', 64)->comment('QR Hash saat transaksi - untuk validasi pengembalian');
            
            // Data peminjam
            $table->string('nama_peminjam', 100);
            $table->string('kelas', 20);
            $table->string('mata_pelajaran', 100);
            $table->string('guru_pengampu', 100)->nullable();
            $table->string('nomor_hp', 20)->nullable();
            $table->text('keperluan')->nullable();
            
            // Waktu
            $table->timestamp('waktu_pinjam')->useCurrent();
            $table->timestamp('estimasi_kembali')->nullable()->comment('Estimasi waktu pengembalian');
            
            // Status transaksi
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat', 'hilang'])
                  ->default('dipinjam');
            
            // IP dan user agent untuk audit trail
            $table->string('ip_peminjam', 45)->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
            
            // Index untuk performa query
            $table->index('status');
            $table->index('waktu_pinjam');
            $table->index(['alat_id', 'status']);
            $table->index('kode_alat_snapshot');
            $table->index('qr_hash_snapshot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
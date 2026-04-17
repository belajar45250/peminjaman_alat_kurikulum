<?php
// database/migrations/xxxx_buat_tabel_history_kerusakan.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('history_kerusakan', function (Blueprint $table) {
            $table->id();

            // Relasi ke alat
            $table->foreignId('alat_id')
                  ->constrained('alat')
                  ->restrictOnDelete();

            // Relasi ke pengembalian (sumber kerusakan)
            // Nullable: bisa diisi manual oleh admin tanpa pengembalian
            $table->foreignId('pengembalian_id')
                  ->nullable()
                  ->constrained('pengembalian')
                  ->nullOnDelete();

            // Snapshot data alat saat kejadian
            $table->string('nama_alat_snapshot', 150);
            $table->string('kode_alat_snapshot', 20);

            // Data penanggung jawab
            $table->string('nama_peminjam', 100)->nullable()
                  ->comment('Siapa yang menyebabkan kerusakan');
            $table->string('kelas', 20)->nullable();

            // Detail kerusakan
            $table->enum('jenis_kerusakan', ['rusak_ringan', 'rusak_berat', 'hilang'])
                  ->default('rusak_ringan');
            $table->string('kondisi_sebelum', 50)
                  ->comment('Kondisi alat sebelum rusak');
            $table->text('deskripsi_kerusakan')
                  ->comment('Deskripsi detail kerusakan');
            $table->json('foto_kerusakan')->nullable()
                  ->comment('Array path foto kerusakan');

            // Denda
            $table->decimal('harga_alat', 15, 2)->default(0);
            $table->decimal('jumlah_denda', 15, 2)->default(0);
            $table->enum('status_denda', ['tidak_ada', 'belum_lunas', 'lunas'])
                  ->default('tidak_ada');
            $table->timestamp('tanggal_lunas')->nullable();

            // Tindak lanjut perbaikan
            $table->enum('status_tindak_lanjut', [
                'menunggu',       // Belum ditindaklanjuti
                'diperbaiki',     // Sedang dalam perbaikan
                'sudah_diperbaiki', // Sudah selesai diperbaiki
                'diganti_baru',   // Diganti alat baru
                'dihapuskan',     // Alat dihapus dari inventaris
            ])->default('menunggu');

            $table->text('catatan_tindak_lanjut')->nullable();
            $table->decimal('biaya_perbaikan', 15, 2)->default(0)
                  ->comment('Biaya yang dikeluarkan untuk perbaikan');
            $table->timestamp('tanggal_selesai_perbaikan')->nullable();

            // Dicatat oleh
            $table->foreignId('dicatat_oleh')
                  ->constrained('users')
                  ->comment('Admin yang mencatat kerusakan');

            $table->timestamp('tanggal_rusak')->useCurrent();
            $table->timestamps();

            $table->index('alat_id');
            $table->index('status_tindak_lanjut');
            $table->index('status_denda');
            $table->index('tanggal_rusak');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('history_kerusakan');
    }
};
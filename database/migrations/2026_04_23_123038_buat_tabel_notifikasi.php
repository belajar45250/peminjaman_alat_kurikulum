<?php
// database/migrations/xxxx_buat_tabel_notifikasi.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->cascadeOnDelete();
            $table->string('judul', 150);
            $table->text('pesan');
            $table->enum('tipe', ['terlambat', 'info'])->default('terlambat');
            $table->boolean('sudah_dibaca')->default(false);
            $table->timestamps();

            $table->index(['sudah_dibaca', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
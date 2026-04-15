<?php
// database/migrations/2024_01_01_000002_buat_tabel_pengaturan.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('kunci', 100)->unique()->comment('Key pengaturan');
            $table->text('nilai')->nullable()->comment('Value pengaturan');
            $table->string('deskripsi', 255)->nullable();
            $table->string('tipe', 50)->default('string')->comment('string|integer|decimal|boolean');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};
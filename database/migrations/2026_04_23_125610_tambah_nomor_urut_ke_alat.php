<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->string('nomor_urut', 20)->nullable()
                  ->after('nama_alat')
                  ->comment('Nomor urut alat, misal: 01, 02, LAP-01');
        });
    }

    public function down(): void
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->dropColumn('nomor_urut');
        });
    }
};
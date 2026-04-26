<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pengaturan')->insert([
            [
                'kunci'      => 'logo_sekolah',
                'nilai'      => null,
                'deskripsi'  => 'Path logo sekolah untuk header aplikasi',
                'tipe'       => 'string',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('pengaturan')->where('kunci', 'logo_sekolah')->delete();
    }
};
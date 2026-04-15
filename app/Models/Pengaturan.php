<?php
// app/Models/Pengaturan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';

    protected $fillable = ['kunci', 'nilai', 'deskripsi', 'tipe'];

    /**
     * Ambil nilai pengaturan dengan cache.
     */
    public static function ambil(string $kunci, mixed $default = null): mixed
    {
        return Cache::remember("pengaturan_{$kunci}", 3600, function () use ($kunci, $default) {
            $pengaturan = self::where('kunci', $kunci)->first();
            return $pengaturan ? $pengaturan->nilai : $default;
        });
    }

    /**
     * Simpan dan bersihkan cache.
     */
    public static function simpan(string $kunci, mixed $nilai): void
    {
        self::updateOrCreate(
            ['kunci' => $kunci],
            ['nilai' => $nilai]
        );
        Cache::forget("pengaturan_{$kunci}");
    }
}
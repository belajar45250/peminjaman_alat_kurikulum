<?php
// app/Models/Pengaturan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';
    protected $fillable = ['kunci', 'nilai', 'deskripsi', 'tipe'];

    public static function ambil(string $kunci, mixed $default = null): mixed
    {
        return Cache::remember("pengaturan_{$kunci}", 3600, function () use ($kunci, $default) {
            $p = self::where('kunci', $kunci)->first();
            return $p ? $p->nilai : $default;
        });
    }

    public static function ambilJson(string $kunci, array $default = []): array
    {
        $nilai = self::ambil($kunci);
        if (!$nilai) return $default;
        $decoded = json_decode($nilai, true);
        return is_array($decoded) ? $decoded : $default;
    }

    public static function simpan(string $kunci, mixed $nilai): void
    {
        self::updateOrCreate(
            ['kunci' => $kunci],
            ['nilai' => $nilai]
        );
        Cache::forget("pengaturan_{$kunci}");
    }

    // ── Helper khusus kelas ──
    public static function getDaftarKelas(): array
    {
        // Format: array of arrays — tiap sub-array adalah satu tingkat
        return self::ambilJson('daftar_kelas', []);
    }

    // ── Helper khusus jam pelajaran ──
    public static function getJamPelajaran(): array
    {
        // Format: [['ke'=>1,'mulai'=>'06:45','selesai'=>'07:30'], ...]
        $raw = self::ambilJson('jam_pelajaran', []);
        // Konversi ke format config lama: [1 => ['mulai'=>..,'selesai'=>..], ...]
        $result = [];
        foreach ($raw as $jam) {
            $result[$jam['ke']] = [
                'label'   => "Jam ke-{$jam['ke']}",
                'mulai'   => $jam['mulai'],
                'selesai' => $jam['selesai'],
            ];
        }
        return $result;
    }

    // ── Flat list kelas untuk dropdown ──
    public static function getFlatKelas(): array
    {
        $tingkatan = self::getDaftarKelas();
        $flat = [];
        foreach ($tingkatan as $group) {
            if (is_array($group)) {
                foreach ($group as $kelas) {
                    $flat[] = $kelas;
                }
            }
        }
        return $flat;
    }
}
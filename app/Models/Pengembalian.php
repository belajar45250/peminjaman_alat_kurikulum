<?php
// app/Models/Pengembalian.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';

    protected $fillable = [
        'kode_pengembalian',
        'peminjaman_id',
        'alat_id',
        'qr_hash_dikembalikan',
        'waktu_kembali',
        'kondisi_kembali',
        'catatan',
        'ada_denda',
        'jenis_denda',
        'persentase_denda',
        'harga_alat_snapshot',
        'jumlah_denda',
        'denda_lunas',
        'diproses_oleh',
    ];

    protected $casts = [
        'waktu_kembali' => 'datetime',
        'ada_denda' => 'boolean',
        'denda_lunas' => 'boolean',
        'persentase_denda' => 'decimal:2',
        'harga_alat_snapshot' => 'decimal:2',
        'jumlah_denda' => 'decimal:2',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Pengembalian $pengembalian) {
            if (empty($pengembalian->kode_pengembalian)) {
                $pengembalian->kode_pengembalian = 'KBL-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            }
        });
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }

    public function diprosesoleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }
}
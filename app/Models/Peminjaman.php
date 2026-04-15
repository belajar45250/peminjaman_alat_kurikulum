<?php
// app/Models/Peminjaman.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'kode_transaksi',
        'alat_id',
        'kode_alat_snapshot',
        'nama_alat_snapshot',
        'qr_hash_snapshot',
        'nama_peminjam',
        'kelas',
        'mata_pelajaran',
        'guru_pengampu',
        'jam_pelajaran_mulai',
        'jam_pelajaran_selesai',
        'waktu_mulai_pinjam',
        'waktu_selesai_pinjam',
        'keperluan',
        'waktu_pinjam',
        'estimasi_kembali',
        'status',
        'ip_peminjam',
        'user_agent',
    ];

    protected $casts = [
        'waktu_pinjam'     => 'datetime',
        'estimasi_kembali' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Peminjaman $p) {
            if (empty($p->kode_transaksi)) {
                $p->kode_transaksi = self::generateKodeTransaksi();
            }
        });
    }

    public static function generateKodeTransaksi(): string
    {
        do {
            $kode = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        } while (self::where('kode_transaksi', $kode)->exists());
        return $kode;
    }

    // ── Relasi ──
    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }

    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class, 'peminjaman_id');
    }

    // ── Helper ──
    public function sudahDikembalikan(): bool
    {
        return $this->status === 'dikembalikan';
    }

    public function masihAktif(): bool
    {
        return $this->status === 'dipinjam';
    }

    public function getLabelJamPinjamAttribute(): string
    {
        $mulai   = config("sekolah.jam_pelajaran.{$this->jam_pelajaran_mulai}");
        $selesai = config("sekolah.jam_pelajaran.{$this->jam_pelajaran_selesai}");

        if (!$mulai || !$selesai) return '-';

        return "Jam ke-{$this->jam_pelajaran_mulai} s/d ke-{$this->jam_pelajaran_selesai} "
             . "({$mulai['mulai']} - {$selesai['selesai']})";
    }

    public function getTerlambatAttribute(): bool
    {
        if (!$this->masihAktif() || !$this->estimasi_kembali) return false;
        return now()->isAfter($this->estimasi_kembali);
    }

    // ── Scopes ──
    public function scopeAktif($query)
    {
        return $query->where('status', 'dipinjam');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'dikembalikan');
    }
}
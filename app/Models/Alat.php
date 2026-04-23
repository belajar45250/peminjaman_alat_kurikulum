<?php
// app/Models/Alat.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Alat extends Model
{
    use SoftDeletes;

    protected $table = 'alat';

    protected $fillable = [
        'kode_alat',
        'nama_alat',
        'nomor_urut',
        'deskripsi',
        'kategori',
        'harga',
        'kondisi',
        'status',
        'lokasi_penyimpanan',
        'gambar',
        'qr_hash',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    // ===== BOOT: Auto-generate kode dan qr_hash =====
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Alat $alat) {
            // Auto generate kode jika tidak diisi
            if (empty($alat->kode_alat)) {
                $alat->kode_alat = self::generateKodeAlat();
            }
            // QR hash HARUS di-generate dari kode_alat - tidak boleh manual
            $alat->qr_hash = self::generateQrHash($alat->kode_alat);
        });

        // Jika kode_alat diubah, qr_hash ikut diperbarui
        // CATATAN: Sebaiknya kode_alat tidak boleh diubah setelah dibuat
        static::updating(function (Alat $alat) {
            if ($alat->isDirty('kode_alat')) {
                // Cek apakah alat sedang dipinjam
                if ($alat->sedangDipinjam()) {
                    throw new \RuntimeException('Tidak dapat mengubah kode alat yang sedang dipinjam.');
                }
                $alat->qr_hash = self::generateQrHash($alat->kode_alat);
            }
        });
    }

    // ===== HELPERS =====

    public static function generateKodeAlat(): string
    {
        do {
            $kode = 'ALT-' . strtoupper(Str::random(6));
        } while (self::where('kode_alat', $kode)->exists());

        return $kode;
    }

    /**
     * Generate QR hash yang unik dan deterministic dari kode_alat.
     * Tambahkan APP_KEY sebagai salt untuk keamanan.
     */
    public static function generateQrHash(string $kodeAlat): string
    {
        return hash('sha256', $kodeAlat . config('app.key'));
    }

    /**
     * Verifikasi apakah QR hash cocok dengan alat ini.
     * INI ADALAH VALIDASI UTAMA anti-QR-tertukar.
     */
    public function verifikasiQrHash(string $qrHash): bool
    {
        return hash_equals($this->qr_hash, $qrHash);
    }

    public function sedangDipinjam(): bool
    {
        return $this->status === 'dipinjam';
    }

    public function tersedia(): bool
    {
        return $this->status === 'tersedia' && $this->kondisi !== 'tidak_tersedia';
    }

    // ===== RELASI =====

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'alat_id');
    }

    public function peminjamanAktif(): HasOne
    {
        return $this->hasOne(Peminjaman::class, 'alat_id')
                    ->where('status', 'dipinjam');
    }

    public function pengembalian(): HasMany
    {
        return $this->hasMany(Pengembalian::class, 'alat_id');
    }

    // ===== SCOPES =====

    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia')
                     ->where('kondisi', '!=', 'tidak_tersedia');
    }

    public function scopeDipinjam($query)
    {
        return $query->where('status', 'dipinjam');
    }

    // ===== ACCESSORS =====

    public function getUrlQrCodeAttribute(): string
    {
        return route('publik.qr', ['hash' => $this->qr_hash]);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'tersedia' => 'success',
            'dipinjam' => 'warning',
            default => 'secondary',
        };
    }

    public function getKondisiBadgeAttribute(): string
    {
        return match($this->kondisi) {
            'baik' => 'success',
            'rusak_ringan' => 'warning',
            'rusak_berat' => 'danger',
            'tidak_tersedia' => 'secondary',
            default => 'secondary',
        };
    }
}
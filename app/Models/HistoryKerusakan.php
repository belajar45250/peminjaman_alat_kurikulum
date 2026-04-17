<?php
// app/Models/HistoryKerusakan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoryKerusakan extends Model
{
    protected $table = 'history_kerusakan';

    protected $fillable = [
        'alat_id',
        'pengembalian_id',
        'nama_alat_snapshot',
        'kode_alat_snapshot',
        'nama_peminjam',
        'kelas',
        'jenis_kerusakan',
        'kondisi_sebelum',
        'deskripsi_kerusakan',
        'foto_kerusakan',
        'harga_alat',
        'jumlah_denda',
        'status_denda',
        'tanggal_lunas',
        'status_tindak_lanjut',
        'catatan_tindak_lanjut',
        'biaya_perbaikan',
        'tanggal_selesai_perbaikan',
        'dicatat_oleh',
        'tanggal_rusak',
    ];

    protected $casts = [
        'foto_kerusakan'            => 'array',
        'tanggal_rusak'             => 'datetime',
        'tanggal_lunas'             => 'datetime',
        'tanggal_selesai_perbaikan' => 'datetime',
        'harga_alat'                => 'decimal:2',
        'jumlah_denda'              => 'decimal:2',
        'biaya_perbaikan'           => 'decimal:2',
    ];

    // ── Relasi ──
    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }

    public function pengembalian(): BelongsTo
    {
        return $this->belongsTo(Pengembalian::class, 'pengembalian_id');
    }

    public function dicatatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    // ── Accessor Label ──
    public function getLabelJenisKerusakanAttribute(): string
    {
        return match($this->jenis_kerusakan) {
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat'  => 'Rusak Berat',
            'hilang'       => 'Hilang',
            default        => '-',
        };
    }

    public function getLabelStatusTindakLanjutAttribute(): string
    {
        return match($this->status_tindak_lanjut) {
            'menunggu'           => 'Menunggu Tindakan',
            'diperbaiki'         => 'Sedang Diperbaiki',
            'sudah_diperbaiki'   => 'Sudah Diperbaiki',
            'diganti_baru'       => 'Diganti Baru',
            'dihapuskan'         => 'Dihapuskan',
            default              => '-',
        };
    }

    public function getBadgeStatusAttribute(): string
    {
        return match($this->status_tindak_lanjut) {
            'menunggu'           => 'danger',
            'diperbaiki'         => 'warning',
            'sudah_diperbaiki'   => 'success',
            'diganti_baru'       => 'info',
            'dihapuskan'         => 'secondary',
            default              => 'secondary',
        };
    }

    public function getBadgeDendaAttribute(): string
    {
        return match($this->status_denda) {
            'tidak_ada'   => 'secondary',
            'belum_lunas' => 'danger',
            'lunas'       => 'success',
            default       => 'secondary',
        };
    }

    public function getLabelStatusDendaAttribute(): string
    {
        return match($this->status_denda) {
            'tidak_ada'   => 'Tidak Ada',
            'belum_lunas' => 'Belum Lunas',
            'lunas'       => 'Lunas',
            default       => '-',
        };
    }

    // ── Scopes ──
    public function scopeMenunggu($query)
    {
        return $query->where('status_tindak_lanjut', 'menunggu');
    }

    public function scopeBelumLunasDenda($query)
    {
        return $query->where('status_denda', 'belum_lunas');
    }
}
<?php
// app/Http/Controllers/Admin/NotifikasiController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    // Ambil notifikasi belum dibaca (untuk polling)
    public function index()
    {
        $notifikasi = Notifikasi::with('peminjaman')
            ->belumDibaca()
            ->latest()
            ->take(20)
            ->get();

        $jumlah = Notifikasi::belumDibaca()->count();

        return response()->json([
            'jumlah'     => $jumlah,
            'notifikasi' => $notifikasi->map(fn($n) => [
                'id'            => $n->id,
                'judul'         => $n->judul,
                'pesan'         => $n->pesan,
                'tipe'          => $n->tipe,
                'waktu'         => $n->created_at->diffForHumans(),
                'peminjaman_id' => $n->peminjaman_id,
            ]),
        ]);
    }

    // Tandai satu notifikasi sudah dibaca
    public function baca(Notifikasi $notifikasi)
    {
        $notifikasi->update(['sudah_dibaca' => true]);
        return response()->json(['ok' => true]);
    }

    // Tandai semua sudah dibaca
    public function bacaSemua()
    {
        Notifikasi::belumDibaca()->update(['sudah_dibaca' => true]);
        return response()->json(['ok' => true]);
    }
}
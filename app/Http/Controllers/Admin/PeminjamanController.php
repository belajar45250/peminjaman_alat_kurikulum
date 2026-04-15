<?php
// app/Http/Controllers/Admin/PeminjamanController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    /**
     * Dashboard utama
     */
    public function dashboard()
    {
        $totalAlat      = Alat::count();
        $sedangDipinjam = Alat::where('status', 'dipinjam')->count();
        $tersedia       = Alat::where('status', 'tersedia')->count();
        $adaDenda       = \App\Models\Pengembalian::where('ada_denda', true)
                            ->where('denda_lunas', false)
                            ->count();

        $peminjamanAktif = Peminjaman::with('alat')
            ->where('status', 'dipinjam')
            ->latest('waktu_pinjam')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalAlat',
            'sedangDipinjam',
            'tersedia',
            'adaDenda',
            'peminjamanAktif'
        ));
    }

    /**
     * Daftar semua riwayat peminjaman
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with('alat')->latest('waktu_pinjam');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->whereDate('waktu_pinjam', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('waktu_pinjam', '<=', $request->sampai);
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_peminjam', 'ilike', "%{$search}%")
                  ->orWhere('kode_transaksi', 'ilike', "%{$search}%")
                  ->orWhere('kelas', 'ilike', "%{$search}%")
                  ->orWhere('nama_alat_snapshot', 'ilike', "%{$search}%");
            });
        }

        $peminjaman = $query->paginate(15)->withQueryString();

        // Hitung ringkasan untuk filter saat ini
        $totalDipinjam    = Peminjaman::where('status', 'dipinjam')->count();
        $totalDikembalikan = Peminjaman::where('status', 'dikembalikan')->count();

        return view('admin.peminjaman.index', compact(
            'peminjaman',
            'totalDipinjam',
            'totalDikembalikan'
        ));
    }

    /**
     * Detail satu transaksi peminjaman
     */
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['alat', 'pengembalian.diprosesoleh']);

        return view('admin.peminjaman.show', compact('peminjaman'));
    }
}
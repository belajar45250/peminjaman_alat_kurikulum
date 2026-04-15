<?php
// app/Http/Controllers/Admin/LaporanController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['alat', 'pengembalian'])
                           ->latest('waktu_pinjam');

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->whereDate('waktu_pinjam', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('waktu_pinjam', '<=', $request->sampai);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter nama peminjam
        if ($request->filled('search')) {
            $query->where('nama_peminjam', 'ilike', '%' . $request->search . '%');
        }

        $laporan = $query->paginate(20)->withQueryString();

        // Statistik ringkasan
        $totalTransaksi  = $query->toBase()->getCountForPagination();
        $totalDenda      = Pengembalian::where('ada_denda', true)->sum('jumlah_denda');
        $dendaBelumLunas = Pengembalian::where('ada_denda', true)
                            ->where('denda_lunas', false)
                            ->sum('jumlah_denda');

        return view('admin.laporan.index', compact(
            'laporan',
            'totalTransaksi',
            'totalDenda',
            'dendaBelumLunas'
        ));
    }

    public function exportPdf(Request $request)
    {
        $query = Peminjaman::with(['alat', 'pengembalian'])
                           ->latest('waktu_pinjam');

        if ($request->filled('dari')) {
            $query->whereDate('waktu_pinjam', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('waktu_pinjam', '<=', $request->sampai);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $laporan      = $query->get();
        $namaSekolah  = \App\Models\Pengaturan::ambil('nama_sekolah', 'Nama Sekolah');
        $totalDenda   = $laporan->sum(fn($p) => $p->pengembalian?->jumlah_denda ?? 0);
        $filterInfo   = $this->buildFilterInfo($request);

        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'laporan',
            'namaSekolah',
            'totalDenda',
            'filterInfo'
        ))->setPaper('a4', 'landscape');

        $namaFile = 'Laporan-Peminjaman-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($namaFile);
    }

    private function buildFilterInfo(Request $request): string
    {
        $parts = [];
        if ($request->filled('dari'))   $parts[] = 'Dari: ' . $request->dari;
        if ($request->filled('sampai')) $parts[] = 'Sampai: ' . $request->sampai;
        if ($request->filled('status')) $parts[] = 'Status: ' . ucfirst($request->status);
        return implode(' | ', $parts) ?: 'Semua data';
    }
}
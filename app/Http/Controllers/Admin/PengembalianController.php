<?php
// app/Http/Controllers/Admin/PengembalianController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PeminjamanService;
use App\Services\QrValidasiService;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function __construct(
        private readonly QrValidasiService $qrValidasi,
        private readonly PeminjamanService $peminjamanService
    ) {}

    public function index()
    {
        return view('admin.pengembalian.scan');
    }

    /**
     * Validasi QR sebelum tampilkan form pengembalian
     */
    public function validasiQr(Request $request)
    {
        $request->validate(['qr_hash' => 'required|string|size:64']);

        $validasi = $this->qrValidasi->validasiUntukPengembalian($request->qr_hash);

        if (!$validasi['valid']) {
            return back()->with('error', $validasi['pesan']);
        }

        return view('admin.pengembalian.form', [
            'alat'       => $validasi['alat'],
            'peminjaman' => $validasi['peminjaman'],
            'qrHash'     => $request->qr_hash,
        ]);
    }

    /**
     * Proses pengembalian
     */
    public function proses(Request $request)
    {
        $request->validate([
            'qr_hash'         => 'required|string|size:64',
            'peminjaman_id'   => 'required|integer|exists:peminjaman,id',
            'kondisi_kembali' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'catatan'         => 'nullable|string|max:500',
        ]);

        try {
            $pengembalian = $this->peminjamanService->prosespengembalian(
                $request->only(['kondisi_kembali', 'catatan']),
                $request->qr_hash
            );

            return redirect()
                ->route('admin.pengembalian.sukses', $pengembalian->id)
                ->with('success', 'Pengembalian berhasil diproses.');

        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function sukses(int $id)
    {
        $pengembalian = \App\Models\Pengembalian::with(['peminjaman', 'alat'])->findOrFail($id);
        return view('admin.pengembalian.sukses', compact('pengembalian'));
    }
}
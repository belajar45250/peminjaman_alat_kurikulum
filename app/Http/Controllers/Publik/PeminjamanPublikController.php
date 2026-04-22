<?php
// app/Http/Controllers/Publik/PeminjamanPublikController.php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePeminjamanRequest;
use App\Services\PeminjamanService;
use App\Services\QrValidasiService;
use Illuminate\Http\Request;

class PeminjamanPublikController extends Controller
{
    public function __construct(
        private readonly QrValidasiService $qrValidasi,
        private readonly PeminjamanService $peminjamanService
    ) {}

    /**
     * Halaman utama publik — landing page / panduan scan
     */
    public function home()
    {
        // Jika admin sudah login, langsung ke dashboard
        if (auth()->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('publik.home');
    }

    /**
     * Halaman setelah scan QR
     */
    public function scanQr(string $hash)
{
    $validasi = $this->qrValidasi->validasiUntukPeminjaman($hash);

    if (!$validasi['valid']) {
        return view('publik.qr-tidak-valid', [
            'pesan' => $validasi['pesan'],
            'alat'  => $validasi['alat'],
        ]);
    }

    return view('publik.form-pinjam', [
        'alat'         => $validasi['alat'],
        'qrHash'       => $hash,
        'kelasList'    => \App\Models\Pengaturan::getDaftarKelas(),   // ← dari DB
        'jamPelajaran' => \App\Models\Pengaturan::getJamPelajaran(),  // ← dari DB
    ]);
}

    /**
     * Submit form peminjaman
     */
    public function submitPeminjaman(StorePeminjamanRequest $request)
    {
        try {
            $peminjaman = $this->peminjamanService->prosesPeminjaman(
                $request->validated(),
                $request->input('qr_hash')
            );

            return view('publik.pinjam-berhasil', compact('peminjaman'));

        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
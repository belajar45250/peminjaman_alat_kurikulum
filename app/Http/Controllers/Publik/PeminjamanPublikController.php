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
        'kelasList'    => \App\Models\Pengaturan::getDaftarKelas(),
        'jamPelajaran' => \App\Models\Pengaturan::getJamTersedia(), // ← pakai yang tersedia
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

    // Tambah method katalog

public function katalog()
{
    $alat = \App\Models\Alat::whereNull('deleted_at')
        ->where('kondisi', '!=', 'tidak_tersedia')
        ->orderBy('nama_alat')
        ->get()
        ->groupBy('kategori');

    return response()->json([
        'alat' => $alat->map(fn($group) => $group->map(fn($a) => [
            'id'       => $a->id,
            'nama'     => $a->nama_alat,
            'kode'     => $a->kode_alat,
            'kategori' => $a->kategori ?? 'Umum',
            'kondisi'  => $a->kondisi,
            'status'   => $a->status,
            'nomor'    => $a->nomor_urut,
        ])),
    ]);
}

// app/Http/Controllers/Publik/PeminjamanPublikController.php — tambah methods

// Validasi QR untuk JavaScript (JSON response)
public function validasiQrJson(string $hash)
{
    $validasi = $this->qrValidasi->validasiUntukPeminjaman($hash);

    if (!$validasi['valid']) {
        return response()->json([
            'valid' => false,
            'pesan' => $validasi['pesan'],
        ]);
    }

    $alat = $validasi['alat'];

    return response()->json([
        'valid'      => true,
        'nama_alat'  => $alat->nama_alat,
        'kode_alat'  => $alat->kode_alat,
        'nomor_urut' => $alat->nomor_urut,
        'pesan'      => 'Tersedia',
    ]);
}

// Form untuk multiple alat
public function formMulti(string $hashes)
{
    $hashList = explode(',', $hashes);

    // Validasi semua hash
    $alatList = [];
    foreach ($hashList as $hash) {
        if (!preg_match('/^[a-f0-9]{64}$/', $hash)) {
            return redirect()->route('home')->with('error', 'QR tidak valid.');
        }
        $validasi = $this->qrValidasi->validasiUntukPeminjaman($hash);
        if (!$validasi['valid']) {
            return view('publik.qr-tidak-valid', [
                'pesan' => "Alat '{$validasi['alat']?->nama_alat}': {$validasi['pesan']}",
                'alat'  => $validasi['alat'],
            ]);
        }
        $alatList[] = ['hash' => $hash, 'alat' => $validasi['alat']];
    }

    $kelasList    = \App\Models\Pengaturan::getDaftarKelas();
    $jamPelajaran = \App\Models\Pengaturan::getJamTersedia();

    return view('publik.form-multi', compact('alatList', 'kelasList', 'jamPelajaran', 'hashes'));
}

// Submit multiple
public function submitMulti(Request $request)
{
    $request->validate([
        'qr_hashes'            => ['required', 'string'],
        'nama_peminjam'        => ['required', 'string', 'min:3', 'max:100'],
        'kelas'                => ['required', 'string', 'max:20'],
        'mata_pelajaran'       => ['required', 'string', 'max:100'],
        'guru_pengampu'        => ['nullable', 'string', 'max:100'],
        'jam_pelajaran_mulai'  => ['required', 'integer'],
        'jam_pelajaran_selesai'=> ['required', 'integer', 'gte:jam_pelajaran_mulai'],
        'keperluan'            => ['nullable', 'string', 'max:500'],
    ]);

    $hashes    = explode(',', $request->qr_hashes);
    $peminjamanList = [];

    foreach ($hashes as $hash) {
        try {
            $peminjaman = $this->peminjamanService->prosesPeminjaman(
                $request->only([
                    'nama_peminjam', 'kelas', 'mata_pelajaran',
                    'guru_pengampu', 'jam_pelajaran_mulai',
                    'jam_pelajaran_selesai', 'keperluan',
                ]),
                trim($hash)
            );
            $peminjamanList[] = $peminjaman;
        } catch (\RuntimeException $e) {
            // Rollback yang sudah berhasil jika salah satu gagal
            foreach ($peminjamanList as $p) {
                $p->alat->update(['status' => 'tersedia']);
                $p->update(['status' => 'dibatalkan']);
            }
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    return view('publik.pinjam-berhasil-multi', compact('peminjamanList'));
}
}
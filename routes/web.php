<?php
// routes/web.php

use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\HistoryKerusakanController;
use App\Http\Controllers\Publik\PeminjamanPublikController;
use Illuminate\Support\Facades\Route;

// ============================================================
// ROOT → Halaman publik (scan QR)
// ============================================================
Route::get('/', [PeminjamanPublikController::class, 'home'])->name('home');

// ============================================================
// RUTE PUBLIK — Peminjam (tanpa login)
// ============================================================
Route::prefix('pinjam')->name('publik.')->group(function () {
    Route::get('/qr/{hash}', [PeminjamanPublikController::class, 'scanQr'])
         ->name('qr')
         ->where('hash', '[a-f0-9]{64}');

    Route::post('/submit', [PeminjamanPublikController::class, 'submitPeminjaman'])
         ->name('submit')
         ->middleware('throttle:10,1');
});

// ============================================================
// RUTE AUTH
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])
     ->name('logout')
     ->middleware('auth');

// ============================================================
// RUTE ADMIN — Memerlukan login
// ============================================================
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

    Route::get('/', [PeminjamanController::class, 'dashboard'])->name('dashboard');

    Route::resource('alat', AlatController::class);
    Route::get('alat/{alat}/qr-pdf', [AlatController::class, 'downloadQrPdf'])->name('alat.qr-pdf');
    Route::post('alat/qr-semua', [AlatController::class, 'downloadSemuaQrPdf'])->name('alat.qr-semua');

    Route::get('peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

    Route::get('pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::post('pengembalian/validasi-qr', [PengembalianController::class, 'validasiQr'])->name('pengembalian.validasi');
    Route::post('pengembalian/proses', [PengembalianController::class, 'proses'])->name('pengembalian.proses');
    Route::get('pengembalian/sukses/{id}', [PengembalianController::class, 'sukses'])->name('pengembalian.sukses');

    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');

    Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    Route::post('pengaturan/tambah-user', [PengaturanController::class, 'tambahUser'])->name('pengaturan.tambah-user');
    Route::delete('pengaturan/hapus-user/{user}', [PengaturanController::class, 'hapusUser'])->name('pengaturan.hapus-user');
    Route::post('pengaturan/ganti-password', [PengaturanController::class, 'gantiPassword'])->name('pengaturan.ganti-password');

    Route::prefix('history-kerusakan')->name('history-kerusakan.')->group(function () {
    Route::get('/',                             [HistoryKerusakanController::class, 'index'])              ->name('index');
    Route::get('/tambah',                       [HistoryKerusakanController::class, 'create'])             ->name('create');
    Route::post('/',                            [HistoryKerusakanController::class, 'store'])              ->name('store');
    Route::get('/{historyKerusakan}',           [HistoryKerusakanController::class, 'show'])               ->name('show');
    Route::post('/{historyKerusakan}/tindak-lanjut', [HistoryKerusakanController::class, 'updateTindakLanjut']) ->name('tindak-lanjut');
    Route::post('/{historyKerusakan}/denda',    [HistoryKerusakanController::class, 'updateDenda'])        ->name('denda');

    // Kelas
    Route::post('pengaturan/tambah-kelas',  [PengaturanController::class, 'tambahKelas']) ->name('pengaturan.tambah-kelas');
    Route::post('pengaturan/hapus-kelas',   [PengaturanController::class, 'hapusKelas'])  ->name('pengaturan.hapus-kelas');
    Route::post('pengaturan/tambah-tingkat',[PengaturanController::class, 'tambahTingkat'])->name('pengaturan.tambah-tingkat');

    // Jam Pelajaran
    Route::post('pengaturan/tambah-jam',    [PengaturanController::class, 'tambahJam'])   ->name('pengaturan.tambah-jam');
    Route::post('pengaturan/hapus-jam',     [PengaturanController::class, 'hapusJam'])    ->name('pengaturan.hapus-jam');
    Route::post('pengaturan/update-jam',    [PengaturanController::class, 'updateJam'])   ->name('pengaturan.update-jam');
});
    Route::get('/offline', [OfflineController::class, 'index'])->name('offline');
});
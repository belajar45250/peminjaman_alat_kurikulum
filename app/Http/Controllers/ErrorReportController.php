<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ErrorReportController extends Controller
{
    public function report(Request $request)
    {
        $request->validate([
            'deskripsi' => 'required|string|max:1000',
            'url'       => 'nullable|string',
            'code'      => 'nullable|string',
        ]);

        // Simpan laporan pengguna ke dalam file storage/logs/laravel.log
        Log::channel('single')->error('Laporan Error dari Pengguna: ', [
            'waktu'     => now()->toDateTimeString(),
            'kode'      => $request->code,
            'url'       => $request->url,
            'user_id'   => auth()->id() ?? 'Guest',
            'deskripsi' => $request->deskripsi,
        ]);

        // Kembalikan pengguna ke halaman utama dengan pesan sukses
        return redirect('/')->with('success', 'Terima kasih! Laporan error kamu sudah dikirim ke developer.');
    }
}
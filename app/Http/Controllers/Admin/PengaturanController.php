<?php
// app/Http/Controllers/Admin/PengaturanController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = [
            'nama_sekolah'            => Pengaturan::ambil('nama_sekolah'),
            'batas_jam_pinjam'        => Pengaturan::ambil('batas_jam_pinjam', 8),
            'persentase_denda_rusak'  => Pengaturan::ambil('persentase_denda_rusak', 30),
            'persentase_denda_hilang' => Pengaturan::ambil('persentase_denda_hilang', 100),
        ];

        $users = User::all();

        return view('admin.pengaturan.index', compact('pengaturan', 'users'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_sekolah'            => ['required', 'string', 'max:150'],
            'batas_jam_pinjam'        => ['required', 'integer', 'min:1', 'max:72'],
            'persentase_denda_rusak'  => ['required', 'numeric', 'min:0', 'max:100'],
            'persentase_denda_hilang' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        Pengaturan::simpan('nama_sekolah',            $request->nama_sekolah);
        Pengaturan::simpan('batas_jam_pinjam',        $request->batas_jam_pinjam);
        Pengaturan::simpan('persentase_denda_rusak',  $request->persentase_denda_rusak);
        Pengaturan::simpan('persentase_denda_hilang', $request->persentase_denda_hilang);

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Tambah akun admin baru (tidak perlu register publik)
     */
    public function tambahUser(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'password' => ['required', Password::min(6)->letters()->numbers()],
        ], [
            'username.unique'   => 'Username sudah digunakan.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Akun admin baru berhasil ditambahkan.');
    }

    /**
     * Hapus akun admin
     */
    public function hapusUser(User $user)
    {
        // Tidak boleh hapus akun sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
        }

        $user->delete();

        return back()->with('success', 'Akun berhasil dihapus.');
    }

    /**
     * Ganti password sendiri
     */
    public function gantiPassword(Request $request)
    {
        $request->validate([
            'password_lama' => ['required', 'string'],
            'password_baru' => ['required', Password::min(6)->letters()->numbers(), 'confirmed'],
        ], [
            'password_baru.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if (!Hash::check($request->password_lama, auth()->user()->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        auth()->user()->update([
            'password' => Hash::make($request->password_baru),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
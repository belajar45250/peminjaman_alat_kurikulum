<?php
// app/Http/Controllers/Admin/PengaturanController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

        $users       = User::all();
        $daftarKelas = Pengaturan::getDaftarKelas();
        $jamPelajaran = Pengaturan::ambilJson('jam_pelajaran', []);

        return view('admin.pengaturan.index', compact(
            'pengaturan', 'users', 'daftarKelas', 'jamPelajaran'
        ));
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

    // ══ KELAS ══

    public function tambahKelas(Request $request)
    {
        $request->validate([
            'nama_kelas' => ['required', 'string', 'max:30'],
            'tingkat'    => ['required', 'integer', 'min:0'],
        ]);

        $daftar = Pengaturan::getDaftarKelas();
        $tingkat = (int) $request->tingkat;

        // Pastikan index ada
        while (count($daftar) <= $tingkat) {
            $daftar[] = [];
        }

        // Cek duplikat
        foreach ($daftar as $group) {
            if (in_array($request->nama_kelas, $group)) {
                return back()->with('error', "Kelas '{$request->nama_kelas}' sudah ada.");
            }
        }

        $daftar[$tingkat][] = $request->nama_kelas;

        Pengaturan::simpan('daftar_kelas', json_encode(array_values($daftar)));

        return back()->with('success', "Kelas '{$request->nama_kelas}' berhasil ditambahkan.");
    }

    public function hapusKelas(Request $request)
    {
        $request->validate([
            'nama_kelas' => ['required', 'string'],
        ]);

        $daftar = Pengaturan::getDaftarKelas();

        foreach ($daftar as $i => $group) {
            $daftar[$i] = array_values(array_filter($group, fn($k) => $k !== $request->nama_kelas));
        }

        // Hapus tingkat kosong
        $daftar = array_values(array_filter($daftar, fn($g) => count($g) > 0));

        Pengaturan::simpan('daftar_kelas', json_encode($daftar));

        return back()->with('success', "Kelas '{$request->nama_kelas}' berhasil dihapus.");
    }

    public function tambahTingkat(Request $request)
    {
        $request->validate([
            'label_tingkat' => ['required', 'string', 'max:20'],
        ]);

        $daftar = Pengaturan::getDaftarKelas();
        $daftar[] = []; // Tingkat baru kosong — kelas ditambah satu per satu

        Pengaturan::simpan('daftar_kelas', json_encode($daftar));

        return back()->with('success', "Tingkat baru berhasil ditambahkan.");
    }

    // ══ JAM PELAJARAN ══

    public function tambahJam(Request $request)
    {
        $request->validate([
            'mulai'   => ['required', 'date_format:H:i'],
            'selesai' => ['required', 'date_format:H:i', 'after:mulai'],
        ], [
            'selesai.after' => 'Jam selesai harus setelah jam mulai.',
        ]);

        $jamList = Pengaturan::ambilJson('jam_pelajaran', []);

        // Cek duplikat
        foreach ($jamList as $jam) {
            if ($jam['mulai'] === $request->mulai) {
                return back()->with('error', "Jam mulai {$request->mulai} sudah ada.");
            }
        }

        $jamList[] = [
            'ke'      => count($jamList) + 1,
            'mulai'   => $request->mulai,
            'selesai' => $request->selesai,
        ];

        // Urutkan berdasarkan jam mulai
        usort($jamList, fn($a, $b) => $a['mulai'] <=> $b['mulai']);

        // Re-numbering
        foreach ($jamList as $i => &$jam) {
            $jam['ke'] = $i + 1;
        }

        Pengaturan::simpan('jam_pelajaran', json_encode(array_values($jamList)));

        return back()->with('success', "Jam ke-{$request->mulai} berhasil ditambahkan.");
    }

    public function hapusJam(Request $request)
    {
        $request->validate([
            'ke' => ['required', 'integer'],
        ]);

        $jamList = Pengaturan::ambilJson('jam_pelajaran', []);
        $jamList = array_values(array_filter($jamList, fn($j) => $j['ke'] !== (int) $request->ke));

        // Re-numbering
        foreach ($jamList as $i => &$jam) {
            $jam['ke'] = $i + 1;
        }

        Pengaturan::simpan('jam_pelajaran', json_encode(array_values($jamList)));

        return back()->with('success', 'Jam pelajaran berhasil dihapus.');
    }

    public function updateJam(Request $request)
    {
        $request->validate([
            'ke'      => ['required', 'integer'],
            'mulai'   => ['required', 'date_format:H:i'],
            'selesai' => ['required', 'date_format:H:i', 'after:mulai'],
        ]);

        $jamList = Pengaturan::ambilJson('jam_pelajaran', []);

        foreach ($jamList as &$jam) {
            if ($jam['ke'] === (int) $request->ke) {
                $jam['mulai']   = $request->mulai;
                $jam['selesai'] = $request->selesai;
                break;
            }
        }

        Pengaturan::simpan('jam_pelajaran', json_encode($jamList));

        return back()->with('success', "Jam ke-{$request->ke} berhasil diperbarui.");
    }

    // ══ USER ══

    public function tambahUser(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'password' => ['required', Password::min(6)->letters()->numbers()],
        ], [
            'username.unique' => 'Username sudah digunakan.',
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Akun admin baru berhasil ditambahkan.');
    }

    public function hapusUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
        }
        $user->delete();
        return back()->with('success', 'Akun berhasil dihapus.');
    }

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

        auth()->user()->update(['password' => Hash::make($request->password_baru)]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    // Tambahkan di PengaturanController.php

public function uploadLogo(Request $request)
{
    $request->validate([
        'logo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
    ], [
        'logo.required' => 'File logo wajib dipilih.',
        'logo.image'    => 'File harus berupa gambar.',
        'logo.max'      => 'Ukuran logo maksimal 2MB.',
    ]);

    // Hapus logo lama jika ada
    $logoLama = Pengaturan::ambil('logo_sekolah');
    if ($logoLama && Storage::disk('public')->exists($logoLama)) {
        Storage::disk('public')->delete($logoLama);
    }

    // Simpan logo baru
    $path = $request->file('logo')->store('logo', 'public');
    Pengaturan::simpan('logo_sekolah', $path);

    return back()->with('success', 'Logo berhasil diupload.');
}

    public function hapusLogo()
    {
        $logoLama = Pengaturan::ambil('logo_sekolah');
        if ($logoLama && Storage::disk('public')->exists($logoLama)) {
            Storage::disk('public')->delete($logoLama);
        }

        Pengaturan::simpan('logo_sekolah', null);

        return back()->with('success', 'Logo berhasil dihapus.');
    }
}
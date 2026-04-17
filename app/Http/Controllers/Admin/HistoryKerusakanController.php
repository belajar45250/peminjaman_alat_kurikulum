<?php
// app/Http/Controllers/Admin/HistoryKerusakanController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\HistoryKerusakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HistoryKerusakanController extends Controller
{
    // ── Daftar semua history kerusakan ──
    public function index(Request $request)
    {
        $query = HistoryKerusakan::with(['alat', 'dicatatOleh'])
                                 ->latest('tanggal_rusak');

        if ($request->filled('status')) {
            $query->where('status_tindak_lanjut', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_kerusakan', $request->jenis);
        }

        if ($request->filled('denda')) {
            $query->where('status_denda', $request->denda);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_peminjam', 'ilike', "%{$s}%")
                  ->orWhere('nama_alat_snapshot', 'ilike', "%{$s}%")
                  ->orWhere('kode_alat_snapshot', 'ilike', "%{$s}%");
            });
        }

        $history = $query->paginate(15)->withQueryString();

        // Statistik ringkasan
        $stats = [
            'menunggu'     => HistoryKerusakan::where('status_tindak_lanjut', 'menunggu')->count(),
            'diperbaiki'   => HistoryKerusakan::where('status_tindak_lanjut', 'diperbaiki')->count(),
            'belum_lunas'  => HistoryKerusakan::where('status_denda', 'belum_lunas')->count(),
            'total_denda'  => HistoryKerusakan::where('status_denda', 'belum_lunas')->sum('jumlah_denda'),
        ];

        return view('admin.history-kerusakan.index', compact('history', 'stats'));
    }

    // ── Detail satu record ──
    public function show(HistoryKerusakan $historyKerusakan)
    {
        $historyKerusakan->load(['alat', 'pengembalian.peminjaman', 'dicatatOleh']);
        return view('admin.history-kerusakan.show', compact('historyKerusakan'));
    }

    // ── Form tambah manual (tanpa pengembalian) ──
    public function create()
    {
        $alat = Alat::whereNull('deleted_at')->orderBy('nama_alat')->get();
        return view('admin.history-kerusakan.create', compact('alat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alat_id'             => ['required', 'exists:alat,id'],
            'nama_peminjam'       => ['nullable', 'string', 'max:100'],
            'kelas'               => ['nullable', 'string', 'max:20'],
            'jenis_kerusakan'     => ['required', 'in:rusak_ringan,rusak_berat,hilang'],
            'kondisi_sebelum'     => ['required', 'string', 'max:50'],
            'deskripsi_kerusakan' => ['required', 'string', 'max:1000'],
            'foto_kerusakan.*'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'jumlah_denda'        => ['nullable', 'numeric', 'min:0'],
        ]);

        $alat = Alat::findOrFail($request->alat_id);

        // Upload foto jika ada
        $fotoPaths = [];
        if ($request->hasFile('foto_kerusakan')) {
            foreach ($request->file('foto_kerusakan') as $foto) {
                $fotoPaths[] = $foto->store('kerusakan', 'public');
            }
        }

        $jumlahDenda = (float) ($request->jumlah_denda ?? 0);

        HistoryKerusakan::create([
            'alat_id'             => $alat->id,
            'pengembalian_id'     => null,
            'nama_alat_snapshot'  => $alat->nama_alat,
            'kode_alat_snapshot'  => $alat->kode_alat,
            'nama_peminjam'       => $request->nama_peminjam,
            'kelas'               => $request->kelas,
            'jenis_kerusakan'     => $request->jenis_kerusakan,
            'kondisi_sebelum'     => $request->kondisi_sebelum,
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'foto_kerusakan'      => $fotoPaths ?: null,
            'harga_alat'          => $alat->harga,
            'jumlah_denda'        => $jumlahDenda,
            'status_denda'        => $jumlahDenda > 0 ? 'belum_lunas' : 'tidak_ada',
            'status_tindak_lanjut' => 'menunggu',
            'dicatat_oleh'        => auth()->id(),
            'tanggal_rusak'       => now(),
        ]);

        return redirect()
            ->route('admin.history-kerusakan.index')
            ->with('success', 'Kerusakan berhasil dicatat.');
    }

    // ── Update tindak lanjut ──
    public function updateTindakLanjut(Request $request, HistoryKerusakan $historyKerusakan)
    {
        $request->validate([
            'status_tindak_lanjut'      => ['required', 'in:menunggu,diperbaiki,sudah_diperbaiki,diganti_baru,dihapuskan'],
            'catatan_tindak_lanjut'     => ['nullable', 'string', 'max:1000'],
            'biaya_perbaikan'           => ['nullable', 'numeric', 'min:0'],
            'tanggal_selesai_perbaikan' => ['nullable', 'date'],
        ]);

        $data = [
            'status_tindak_lanjut'  => $request->status_tindak_lanjut,
            'catatan_tindak_lanjut' => $request->catatan_tindak_lanjut,
            'biaya_perbaikan'       => $request->biaya_perbaikan ?? 0,
        ];

        if (in_array($request->status_tindak_lanjut, ['sudah_diperbaiki', 'diganti_baru'])) {
            $data['tanggal_selesai_perbaikan'] = $request->tanggal_selesai_perbaikan ?? now();

            // Update kondisi alat kembali ke baik
            $historyKerusakan->alat->update(['kondisi' => 'baik', 'status' => 'tersedia']);
        }

        if ($request->status_tindak_lanjut === 'dihapuskan') {
            // Soft delete alat dari inventaris
            $historyKerusakan->alat->delete();
        }

        $historyKerusakan->update($data);

        return back()->with('success', 'Tindak lanjut berhasil diperbarui.');
    }

    // ── Update status denda ──
    public function updateDenda(Request $request, HistoryKerusakan $historyKerusakan)
    {
        $request->validate([
            'status_denda' => ['required', 'in:belum_lunas,lunas'],
        ]);

        $historyKerusakan->update([
            'status_denda'  => $request->status_denda,
            'tanggal_lunas' => $request->status_denda === 'lunas' ? now() : null,
        ]);

        // Sync ke tabel pengembalian juga
        if ($historyKerusakan->pengembalian) {
            $historyKerusakan->pengembalian->update([
                'denda_lunas' => $request->status_denda === 'lunas',
            ]);
        }

        return back()->with('success', 'Status denda berhasil diperbarui.');
    }
}
<?php
// app/Http/Controllers/Admin/AlatController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlatRequest;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\Image\EpsImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use Illuminate\Support\Str;
use BaconQrCode\Writer;
use Barryvdh\DomPDF\Facade\Pdf;


class AlatController extends Controller
{
    public function index(Request $request)
    {
        $alat = Alat::query()
            ->when($request->search, fn($q, $s) => $q->where('nama_alat', 'ilike', "%{$s}%")
                                                       ->orWhere('kode_alat', 'ilike', "%{$s}%"))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->kondisi, fn($q, $k) => $q->where('kondisi', $k))
            ->withCount(['peminjaman as total_dipinjam' => fn($q) => $q->where('status', 'dikembalikan')])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.alat.index', compact('alat'));
    }

    public function create()
    {
        return view('admin.alat.create');
    }

    public function store(StoreAlatRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            Storage::disk('public')->makeDirectory('alat', 0755, true);
            $data['gambar'] = $request->file('gambar')->store('alat', 'public');
        }

        Alat::create($data);

        return redirect()
            ->route('admin.alat.index')
            ->with('success', 'Alat berhasil ditambahkan.');
    }

    public function edit(Alat $alat)
    {
        return view('admin.alat.edit', compact('alat'));
    }

    public function update(StoreAlatRequest $request, Alat $alat)
    {
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            Storage::disk('public')->makeDirectory('alat', 0755, true);

            if ($alat->gambar) {
                Storage::disk('public')->delete($alat->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('alat', 'public');
        }

        // Tidak boleh mengubah kode_alat jika sedang dipinjam
        if (isset($data['kode_alat']) && $alat->sedangDipinjam()) {
            return back()->with('error', 'Tidak dapat mengubah kode alat yang sedang dipinjam.');
        }

        $alat->update($data);

        return redirect()
            ->route('admin.alat.index')
            ->with('success', 'Data alat berhasil diperbarui.');
    }

    public function destroy(Alat $alat)
    {
        if ($alat->sedangDipinjam()) {
            return back()->with('error', 'Alat yang sedang dipinjam tidak dapat dihapus.');
        }

        $alat->delete();

        return redirect()
            ->route('admin.alat.index')
            ->with('success', 'Alat berhasil dihapus.');
    }


     /**
     * Helper: generate QR sebagai string SVG
     */
    private function generateQrSvg(string $url, int $size = 300): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);
        return $writer->writeString($url);
    }


    /**
     * Helper: generate QR sebagai base64 PNG
     * Bacon tidak support PNG murni, kita pakai SVG → base64 untuk PDF juga
     */
    private function generateQrBase64(string $url, int $size = 250): string
    {
        $svg = $this->generateQrSvg($url, $size);
        // Untuk DomPDF, SVG bisa langsung diembed sebagai base64
        return base64_encode($svg);
    }

    /**
     * Download QR Code dalam bentuk PDF
     */
    public function downloadQrPdf(Alat $alat)
    {
        $url      = route('publik.qr', ['hash' => $alat->qr_hash]);
        $qrSvg    = $this->generateQrSvg($url, 300);
        $qrBase64 = base64_encode($qrSvg);

        $pdf = Pdf::loadView('admin.alat.qr-pdf', [
            'alat'     => $alat,
            'qrBase64' => $qrBase64,
            'qrMime'   => 'image/svg+xml',
            'url'      => $url,
        ])->setPaper('a6', 'portrait');

        return $pdf->download("QR-{$alat->kode_alat}.pdf");
    }

    /**
     * Download QR semua alat sekaligus
     */
    public function downloadSemuaQrPdf(Request $request)
    {
        $alatList = Alat::whereNull('deleted_at')
            ->when($request->ids, fn($q, $ids) => $q->whereIn('id', explode(',', $ids)))
            ->get();

        $alatDenganQr = $alatList->map(function ($alat) {
            $url   = route('publik.qr', ['hash' => $alat->qr_hash]);
            $svg   = $this->generateQrSvg($url, 200);
            return [
                'alat'     => $alat,
                'qrBase64' => base64_encode($svg),
                'qrMime'   => 'image/svg+xml',
                'url'      => $url,
            ];
        });

        $pdf = Pdf::loadView('admin.alat.qr-semua-pdf', compact('alatDenganQr'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('QR-Semua-Alat.pdf');
    }




    public function exportCsv()
    {
        $alat = Alat::all();
        $fileName = 'data_alat_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Header Kolom di Excel
        $columns = ['Nama Alat', 'Nomor Urut', 'Kode Alat', 'Kategori', 'Harga', 'Kondisi', 'Lokasi Penyimpanan', 'Deskripsi'];

        $callback = function() use($alat, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); // Tulis Header

            foreach ($alat as $item) {
                fputcsv($file, [
                    $item->nama_alat,
                    $item->nomor_urut,
                    $item->kode_alat,
                    $item->kategori,
                    $item->harga,
                    $item->kondisi,
                    $item->lokasi_penyimpanan,
                    $item->deskripsi
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * IMPORT CSV
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), "r");

        $header = true;
        $count = 0;

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($header) {
                $header = false;
                continue; // Lewati baris pertama karena itu adalah judul kolom
            }

            // Generate QR Hash (Berjaga-jaga jika di modelmu belum ada auto-generate)
            $qrHash = hash('sha256', uniqid('', true) . Str::random(10));

            // Simpan ke database
            Alat::create([
                'nama_alat'          => $data[0] ?? 'Alat Tanpa Nama',
                'nomor_urut'         => $data[1] ?? null,
                'kode_alat'          => $data[2] ?? strtoupper(Str::random(6)),
                'kategori'           => $data[3] ?? null,
                'harga'              => $data[4] ?? 0,
                'kondisi'            => $data[5] ?? 'baik',
                'lokasi_penyimpanan' => $data[6] ?? null,
                'deskripsi'          => $data[7] ?? null,
                'qr_hash'            => $qrHash, 
                'status'             => 'tersedia'
            ]);
            $count++;
        }
        fclose($handle);

        return back()->with('success', "$count data alat berhasil di-import!");
    }
}
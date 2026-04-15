<?php
// app/Http/Requests/StorePeminjamanRequest.php

namespace App\Http\Requests;

use App\Models\Alat;
use Illuminate\Foundation\Http\FormRequest;

class StorePeminjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Publik, tanpa auth
    }

    public function rules(): array
    {
        return [
            'qr_hash'        => ['required', 'string', 'size:64', 'regex:/^[a-f0-9]+$/'],
            'nama_peminjam'  => ['required', 'string', 'min:3', 'max:100'],
            'kelas'          => ['required', 'string', 'max:20'],
            'mata_pelajaran' => ['required', 'string', 'max:100'],
            'guru_pengampu'  => ['nullable', 'string', 'max:100'],
            'nomor_hp'       => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'keperluan'      => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'qr_hash.required'       => 'QR Code tidak valid.',
            'qr_hash.size'           => 'Format QR Code tidak valid.',
            'nama_peminjam.required' => 'Nama lengkap wajib diisi.',
            'nama_peminjam.min'      => 'Nama minimal 3 karakter.',
            'kelas.required'         => 'Kelas wajib diisi.',
            'mata_pelajaran.required' => 'Mata pelajaran wajib diisi.',
        ];
    }

    /**
     * Validasi tambahan: cek apakah QR valid dan alat tersedia
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $qrHash = $this->input('qr_hash');
            if ($qrHash && strlen($qrHash) === 64) {
                $alat = Alat::where('qr_hash', $qrHash)->whereNull('deleted_at')->first();
                if (!$alat || $alat->status !== 'tersedia') {
                    $validator->errors()->add('qr_hash', 'Alat tidak tersedia untuk dipinjam.');
                }
            }
        });
    }
}
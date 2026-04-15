<?php
// app/Http/Requests/StoreAlatRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $alatId = $this->route('alat')?->id;

        return [
            'nama_alat'          => ['required', 'string', 'max:150'],
            'kode_alat'          => [
                'nullable',
                'string',
                'max:20',
                'unique:alat,kode_alat,' . ($alatId ?? 'NULL'),
            ],
            'deskripsi'          => ['nullable', 'string'],
            'kategori'           => ['nullable', 'string', 'max:100'],
            'harga'              => ['required', 'numeric', 'min:0'],
            'kondisi'            => ['required', 'in:baik,rusak_ringan,rusak_berat,tidak_tersedia'],
            'lokasi_penyimpanan' => ['nullable', 'string', 'max:100'],
            'gambar'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
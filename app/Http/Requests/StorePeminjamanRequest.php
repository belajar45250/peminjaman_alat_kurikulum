<?php
// app/Http/Requests/StorePeminjamanRequest.php

namespace App\Http\Requests;

use App\Models\Alat;
use Illuminate\Foundation\Http\FormRequest;

class StorePeminjamanRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $maxJam = count(config('sekolah.jam_pelajaran'));

        return [
            'qr_hash'               => ['required', 'string', 'size:64', 'regex:/^[a-f0-9]+$/'],
            'nama_peminjam'         => ['required', 'string', 'min:3', 'max:100'],
            'kelas'                 => ['required', 'string', 'max:20'],
            'mata_pelajaran'        => ['required', 'string', 'max:100'],
            'guru_pengampu'         => ['nullable', 'string', 'max:100'],
            'jam_pelajaran_mulai'   => ['required', 'integer', 'min:1', "max:{$maxJam}"],
            'jam_pelajaran_selesai' => ['required', 'integer', 'min:1', "max:{$maxJam}",
                                        'gte:jam_pelajaran_mulai'],
            'keperluan'             => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_peminjam.required'         => 'Nama lengkap wajib diisi.',
            'nama_peminjam.min'              => 'Nama minimal 3 karakter.',
            'kelas.required'                 => 'Kelas wajib dipilih.',
            'mata_pelajaran.required'        => 'Mata pelajaran wajib diisi.',
            'jam_pelajaran_mulai.required'   => 'Jam mulai wajib dipilih.',
            'jam_pelajaran_selesai.required' => 'Jam selesai wajib dipilih.',
            'jam_pelajaran_selesai.gte'      => 'Jam selesai tidak boleh sebelum jam mulai.',
        ];
    }

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
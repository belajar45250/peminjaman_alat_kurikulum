<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah user sudah ada, kalau ada jangan insert lagi
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('admin123'), // Ganti password ini!
            ]
        );

        User::firstOrCreate(
            ['username' => 'petugas'],
            [
                'name' => 'Petugas',
                'password' => bcrypt('petugas123'), // Ganti password ini!
            ]
        );
    }
}
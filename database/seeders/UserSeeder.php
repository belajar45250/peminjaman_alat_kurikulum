<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name'       => 'Administrator',
                'username'   => 'admin',
                'password'   => Hash::make('admin123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Petugas',
                'username'   => 'petugas',
                'password'   => Hash::make('petugas123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
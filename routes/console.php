<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Contoh: Jalankan otomatis setiap bulan sekali di tengah malam
Schedule::command('maintenance:clean')->monthly();

// Atau kalau mau setahun sekali setiap tanggal 1 Januari
// Schedule::command('maintenance:clean')->yearly();

Schedule::command('pinjam:cek-terlambat')->everyMinute();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

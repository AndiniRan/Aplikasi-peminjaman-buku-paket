<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| File ini digunakan untuk mendaftarkan perintah Artisan
| dan menjadwalkan proses otomatis Laravel.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Penolakan Otomatis Pengajuan Peminjaman
|--------------------------------------------------------------------------
|
| Memeriksa pengajuan berstatus siap yang sudah melewati
| batas pengambilan setiap satu menit.
|
*/

Schedule::command('pengajuan:tolak-terlambat')
    ->everyMinute()
    ->withoutOverlapping();

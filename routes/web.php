<?php

use Illuminate\Support\Facades\Route;
use App\Models\Buku;
use App\Models\Kategori;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\MemberGuruController;
use App\Http\Controllers\Admin\MemberSiswaController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ProfileController;

Route::get('/', function () {
    $books = Buku::with('kategori')
        ->orderBy('judul')
        ->take(4)
        ->get();

    return view('landing.landingPage', compact('books'));
})->name('landing.landingPage');

Route::get('/katalog', function () {
    $buku = Buku::with('kategori')
        ->orderBy('judul', 'asc')
        ->get();

    $kategori = Kategori::orderBy(
        'nama_kategori',
        'asc'
    )->get();

    return view(
        'landing.katalog',
        compact(
            'buku',
            'kategori'
        )
    );
})->name('landing.katalog');

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Data statistik dashboard
        Route::get('/dashboard/statistik', [DashboardController::class, 'statistik'])
            ->name('dashboard.statistik');

        // Data kategori
        Route::resource('kategori', KategoriController::class);

        // Data buku / Katalog buku
        Route::get('/buku/katalog', [BukuController::class, 'katalog'])->name('buku.katalog');
        Route::post('/buku/import', [BukuController::class, 'import'])->name('buku.import');
        Route::resource('buku', BukuController::class);

        // Data member (siswa & guru)
        Route::resource('member/guru', MemberGuruController::class)
            ->except('show')
            ->names('member.guru');

        Route::post('/member/guru/import', [MemberGuruController::class, 'import'])
            ->name('member.guru.import');

        Route::resource('member/siswa', MemberSiswaController::class)
            ->except('show')
            ->names('member.siswa');
        
        Route::post('/member/siswa/import', [MemberSiswaController::class, 'import'])
            ->name('member.siswa.import');

        // Pengajuan Peminjaman //
        Route::get('/pengajuan', [PengajuanController::class, 'index'])
            ->name('pengajuan.index');

        Route::patch('/pengajuan/{pengajuan}/siap', [PengajuanController::class, 'siap'])
            ->name('pengajuan.siap');

        Route::patch('/pengajuan/{pengajuan}/tolak', [PengajuanController::class, 'tolak'])
            ->name('pengajuan.tolak');

        Route::patch('/pengajuan/{pengajuan}/diambil', [PengajuanController::class, 'diambil'])
            ->name('pengajuan.diambil');

        // Transaksi //
        Route::get('/transaksi/peminjaman', [PeminjamanController::class, 'index'])
            ->name('transaksi.peminjaman.index');

        Route::get('/transaksi/peminjaman/tambah', [PeminjamanController::class, 'create'])
            ->name('transaksi.peminjaman.create');

        Route::post('/transaksi/peminjaman', [PeminjamanController::class, 'store'])
            ->name('transaksi.peminjaman.store');

        Route::get('/transaksi/peminjaman/{transaksi}/edit', [PeminjamanController::class, 'edit'])
            ->name('transaksi.peminjaman.edit');

        Route::put('/transaksi/peminjaman/{transaksi}', [PeminjamanController::class, 'update'])
            ->name('transaksi.peminjaman.update');

        Route::get('/transaksi/pengembalian/create', [PengembalianController::class, 'create'])
            ->name('transaksi.pengembalian.create');

        Route::post('/transaksi/pengembalian', [PengembalianController::class, 'store'])
            ->name('transaksi.pengembalian.store');

        // Laporan //
        Route::prefix('laporan')
            ->name('laporan.')
            ->group(function () {

                Route::get('/peminjaman', [LaporanController::class, 'peminjaman']) 
                    ->name('peminjaman');

                Route::get('/peminjaman/export/excel', [LaporanController::class, 'exportPeminjamanExcel'])
                    ->name('peminjaman.export.excel');

                Route::get('/peminjaman/export/pdf', [LaporanController::class, 'exportPeminjamanPdf'])
                    ->name('peminjaman.export.pdf');

                Route::get('/pengembalian', [LaporanController::class, 'pengembalian'])
                    ->name('pengembalian');

                Route::get('/pengembalian/export/excel', [LaporanController::class, 'exportPengembalianExcel'])
                    ->name('pengembalian.export.excel');

                Route::get('/pengembalian/export/pdf', [LaporanController::class, 'exportPengembalianPdf'])
                    ->name('pengembalian.export.pdf');

                Route::get('/buku', [LaporanController::class, 'buku'])
                    ->name('buku');

                Route::get('/buku/export/excel', [LaporanController::class, 'exportBukuExcel'])
                    ->name('buku.export.excel');

                Route::get('/buku/export/pdf', [LaporanController::class, 'exportBukuPdf'])
                    ->name('buku.export.pdf');

                Route::get('/member', [LaporanController::class, 'member'])
                    ->name('member');

                Route::get('/member/export/excel', [LaporanController::class, 'exportMemberExcel'])
                    ->name('member.export.excel');

                Route::get('/member/export/pdf', [LaporanController::class, 'exportMemberPdf'])
                    ->name('member.export.pdf');
            });
        
        // Profile //
        Route::get('/profile', [ProfileController::class, 'index'])
            ->name('profile.index');

        Route::put('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');
    });

require __DIR__.'/auth.php';
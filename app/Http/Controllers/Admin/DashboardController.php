<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku = 0;
        $totalMember = 0;
        $bukuDipinjam = 0;
        $bukuTerlambat = 0;

        if (Schema::hasTable('users')) {
            $totalMember = User::whereIn('role', ['guru', 'siswa'])->count();
        }

        if (Schema::hasTable('buku')) {
            $totalBuku = DB::table('buku')->count();
        }

        if (Schema::hasTable('transaksi')) {
            $bukuDipinjam = DB::table('transaksi')
                ->where('status', 'dipinjam')
                ->count();

            if (Schema::hasColumn('transaksi', 'tanggal_kembali')) {
                $bukuTerlambat = DB::table('transaksi')
                    ->where('status', 'dipinjam')
                    ->whereDate('tanggal_kembali', '<', now())
                    ->count();
            }
        }

        return view('admin.dashboard', compact(
            'totalBuku',
            'totalMember',
            'bukuDipinjam',
            'bukuTerlambat'
        ));
    }
}
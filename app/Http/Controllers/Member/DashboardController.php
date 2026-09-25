<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $transaksiAktif = Transaksi::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->get();

        $bukuDipinjam = $transaksiAktif->count();

        $jatuhTempoTerdekat = $transaksiAktif
            ->whereNotNull('tanggal_jatuh_tempo')
            ->sortBy('tanggal_jatuh_tempo')
            ->first();

        $totalRiwayat = Transaksi::where('user_id', $user->id)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI
        |--------------------------------------------------------------------------
        | Untuk sementara disiapkan sebagai array.
        | Nanti setelah struktur Pengajuan member kita sambungkan,
        | isinya diambil langsung dari database.
        */
        $notifikasi = [];

        return view('member.dashboard', compact(
            'user',
            'bukuDipinjam',
            'jatuhTempoTerdekat',
            'totalRiwayat',
            'notifikasi'
        ));
    }
}
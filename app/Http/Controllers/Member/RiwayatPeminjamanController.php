<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $riwayat = Transaksi::with([
            'user',
            'buku.kategori',
            'pengajuan',
        ])
            ->where('user_id', Auth::id())
            ->orderByDesc('tanggal_pinjam')
            ->get();

        return view(
            'member.riwayat.index',
            compact('riwayat')
        );
    }
}
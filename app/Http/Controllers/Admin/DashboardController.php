<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku = Schema::hasTable('buku')
            ? DB::table('buku')->count()
            : 0;

        $totalMember = Schema::hasTable('users')
            ? User::whereIn('role', ['guru', 'siswa'])->count()
            : 0;

        $bukuDipinjam = 0;
        $bukuTerlambat = 0;

        if (Schema::hasTable('transaksi')) {
            $bukuDipinjam = DB::table('transaksi')
                ->where('status', 'dipinjam')
                ->count();

            if (Schema::hasColumn('transaksi', 'tanggal_jatuh_tempo')) {
                $bukuTerlambat = DB::table('transaksi')
                    ->where('status', 'dipinjam')
                    ->whereDate('tanggal_jatuh_tempo', '<', today())
                    ->count();
            }
        }

        $kategoriList = Schema::hasTable('kategori')
            ? DB::table('kategori')
                ->orderBy('nama_kategori')
                ->get()
            : collect();

        return view('admin.dashboard', compact(
            'totalBuku',
            'totalMember',
            'bukuDipinjam',
            'bukuTerlambat',
            'kategoriList'
        ));
    }

    public function statistik(Request $request)
    {
        if (
            !Schema::hasTable('transaksi') ||
            !Schema::hasTable('buku')
        ) {
            return response()->json([
                'labels' => [],
                'values' => [],
                'total' => 0,
            ]);
        }

        $request->validate([
            'periode' => 'nullable|in:hari,bulan,tahun',
            'kategori' => 'nullable|integer',
        ]);

        $periode = $request->query('periode', 'bulan');
        $kategoriId = $request->query('kategori');

        $query = DB::table('transaksi as t')
            ->join('buku as b', 'b.id', '=', 't.buku_id');

        if ($kategoriId !== null && $kategoriId !== '') {
            $query->where('b.kategori_id', $kategoriId);
        }

        if ($periode === 'hari') {
            $query->whereDate('t.tanggal_pinjam', today());
        } elseif ($periode === 'tahun') {
            $query->whereYear('t.tanggal_pinjam', now()->year);
        } else {
            $query->whereYear('t.tanggal_pinjam', now()->year)
                ->whereMonth('t.tanggal_pinjam', now()->month);
        }

        $data = $query
            ->select(
                'b.id',
                'b.judul',
                DB::raw('COUNT(t.transaksi_id) as total')
            )
            ->groupBy('b.id', 'b.judul')
            ->orderBy('b.judul')
            ->get();

        return response()->json([
            'labels' => $data->pluck('judul'),
            'values' => $data->pluck('total'),
            'total' => $data->sum('total'),
        ]);
    }
}
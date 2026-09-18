<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    public function create()
    {
        $transaksiAktif = Transaksi::with([
            'user',
            'buku.kategori',
        ])
            ->whereIn('status', [
                'dipinjam',
                'terlambat',
            ])
            ->orderBy('tanggal_jatuh_tempo')
            ->get();

        return view(
            'admin.transaksi.pengembalian.create',
            compact('transaksiAktif')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaksi_id' => [
                'required',
                'exists:transaksi,transaksi_id',
            ],
            'tanggal_kembali' => [
                'required',
                'date',
            ],
        ]);

        try {
            DB::transaction(function () use ($request) {
                $transaksi = Transaksi::where(
                    'transaksi_id',
                    $request->transaksi_id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($transaksi->status === 'dikembalikan') {
                    throw new Exception(
                        'Buku ini sudah dikembalikan.'
                    );
                }

                if (
                    !in_array(
                        $transaksi->status,
                        ['dipinjam', 'terlambat']
                    )
                ) {
                    throw new Exception(
                        'Transaksi ini tidak dapat diproses sebagai pengembalian.'
                    );
                }

                $tanggalKembali = Carbon::parse(
                    $request->tanggal_kembali
                )->startOfDay();

                $tanggalPinjam = Carbon::parse(
                    $transaksi->tanggal_pinjam
                )->startOfDay();

                if ($tanggalKembali->lt($tanggalPinjam)) {
                    throw new Exception(
                        'Tanggal kembali tidak boleh sebelum tanggal pinjam.'
                    );
                }

                $buku = $transaksi->buku()
                    ->lockForUpdate()
                    ->firstOrFail();

                $transaksi->update([
                    'tanggal_kembali' => $tanggalKembali,
                    'status' => 'dikembalikan',
                ]);

                if ($buku->stok_tersedia < $buku->total_stok) {
                    $buku->increment('stok_tersedia');
                }
            });

            return redirect()
                ->route('admin.transaksi.peminjaman.index')
                ->with(
                    'success',
                    'Pengembalian buku berhasil diproses.'
                );
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}
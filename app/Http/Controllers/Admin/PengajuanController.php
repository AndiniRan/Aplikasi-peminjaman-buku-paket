<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Pengajuan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajuan::with([
            'user',
            'buku.kategori',
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_awal')) {
            $query->whereDate(
                'tanggal_pengajuan',
                '>=',
                $request->tanggal_awal
            );
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate(
                'tanggal_pengajuan',
                '<=',
                $request->tanggal_akhir
            );
        }

        $pengajuan = $query
            ->orderByDesc('tanggal_pengajuan')
            ->get();

        return view(
            'admin.pengajuan.index',
            compact('pengajuan')
        );
    }

    public function siap(Pengajuan $pengajuan)
    {
        try {
            DB::transaction(function () use ($pengajuan) {
                $pengajuan = Pengajuan::where(
                    'pengajuan_id',
                    $pengajuan->pengajuan_id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($pengajuan->status !== 'menunggu') {
                    throw new Exception(
                        'Pengajuan ini sudah diproses sebelumnya.'
                    );
                }

                $buku = Buku::where(
                    'id',
                    $pengajuan->buku_id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($buku->stok_tersedia <= 0) {
                    throw new Exception(
                        'Stok buku sudah tidak tersedia.'
                    );
                }

                $tanggalDisiapkan = now();

                $pengajuan->update([
                    'status' => 'siap',
                    'tanggal_disiapkan' => $tanggalDisiapkan,
                    'batas_pengambilan' => $tanggalDisiapkan
                        ->copy()
                        ->addDays(7),
                    'tanggal_diambil' => null,
                    'alasan_ditolak' => null,
                ]);

                $buku->decrement('stok_tersedia');
            });

            return back()->with(
                'success',
                'Pengajuan berhasil dikonfirmasi dan buku siap diambil.'
            );
        } catch (Exception $e) {
            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function tolak(
        Request $request,
        Pengajuan $pengajuan
    ) {
        $request->validate([
            'alasan_ditolak' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        try {
            DB::transaction(function () use (
                $request,
                $pengajuan
            ) {
                $pengajuan = Pengajuan::where(
                    'pengajuan_id',
                    $pengajuan->pengajuan_id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($pengajuan->status !== 'menunggu') {
                    throw new Exception(
                        'Pengajuan ini sudah diproses sebelumnya.'
                    );
                }

                $pengajuan->update([
                    'status' => 'ditolak',
                    'alasan_ditolak' => $request->alasan_ditolak
                        ?: 'Pengajuan ditolak oleh admin.',
                ]);
            });

            return back()->with(
                'success',
                'Pengajuan berhasil ditolak.'
            );
        } catch (Exception $e) {
            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function diambil(Pengajuan $pengajuan)
    {
        try {
            DB::transaction(function () use ($pengajuan) {
                $pengajuan = Pengajuan::where(
                    'pengajuan_id',
                    $pengajuan->pengajuan_id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($pengajuan->status !== 'siap') {
                    throw new Exception(
                        'Pengajuan ini belum berstatus siap diambil.'
                    );
                }

                if (
                    $pengajuan->batas_pengambilan &&
                    now()->greaterThan(
                        $pengajuan->batas_pengambilan
                    )
                ) {
                    throw new Exception(
                        'Batas waktu pengambilan buku sudah berakhir.'
                    );
                }

                $transaksiSudahAda = Transaksi::where(
                    'pengajuan_id',
                    $pengajuan->pengajuan_id
                )
                    ->lockForUpdate()
                    ->exists();

                if ($transaksiSudahAda) {
                    throw new Exception(
                        'Transaksi untuk pengajuan ini sudah dibuat.'
                    );
                }

                $tanggalDiambil = now();

                $pengajuan->update([
                    'status' => 'disetujui',
                    'tanggal_diambil' => $tanggalDiambil,
                ]);

                Transaksi::create([
                    'user_id' => $pengajuan->user_id,
                    'buku_id' => $pengajuan->buku_id,
                    'pengajuan_id' => $pengajuan->pengajuan_id,
                    'tanggal_pinjam' => $tanggalDiambil,
                    'tanggal_jatuh_tempo' => $tanggalDiambil
                        ->copy()
                        ->addDays(7),
                    'tanggal_kembali' => null,
                    'status' => 'dipinjam',
                ]);
            });

            return back()->with(
                'success',
                'Buku berhasil diambil dan transaksi peminjaman otomatis dibuat.'
            );
        } catch (Exception $e) {
            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }
}
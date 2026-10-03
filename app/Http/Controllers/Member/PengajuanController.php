<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Pengajuan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajuan::with([
            'user',
            'buku.kategori',
        ])
            ->where('user_id', Auth::id());

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
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

        $statusFilter = $request->status;

        return view(
            'member.pengajuan.index',
            compact(
                'pengajuan',
                'statusFilter'
            )
        );
    }

    public function create(Buku $buku)
    {
        if ($buku->stok_tersedia <= 0) {
            return redirect()
                ->route('member.katalog.show', $buku)
                ->with(
                    'error',
                    'Stok buku sedang tidak tersedia.'
                );
        }

        $user = Auth::user();

        return view(
            'member.pengajuan.create',
            compact(
                'buku',
                'user'
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'buku_id' => [
                'required',
                'exists:buku,id',
            ],
        ], [
            'buku_id.required' => 'Buku wajib dipilih.',
            'buku_id.exists' => 'Buku tidak ditemukan.',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $userId = Auth::id();

                $buku = Buku::where(
                    'id',
                    $validated['buku_id']
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($buku->stok_tersedia <= 0) {
                    throw new Exception(
                        'Stok buku sedang tidak tersedia.'
                    );
                }

                $pengajuanAktif = Pengajuan::where(
                    'user_id',
                    $userId
                )
                    ->where(
                        'buku_id',
                        $buku->id
                    )
                    ->whereIn(
                        'status',
                        [
                            'menunggu',
                            'siap',
                        ]
                    )
                    ->exists();

                if ($pengajuanAktif) {
                    throw new Exception(
                        'Kamu masih memiliki pengajuan aktif untuk buku ini.'
                    );
                }

                $sedangDipinjam = Transaksi::where(
                    'user_id',
                    $userId
                )
                    ->where(
                        'buku_id',
                        $buku->id
                    )
                    ->where(
                        'status',
                        'dipinjam'
                    )
                    ->exists();

                if ($sedangDipinjam) {
                    throw new Exception(
                        'Buku ini masih sedang kamu pinjam.'
                    );
                }

                Pengajuan::create([
                    'user_id' => $userId,
                    'buku_id' => $buku->id,
                    'tanggal_pengajuan' => now(),
                    'tanggal_disiapkan' => null,
                    'batas_pengambilan' => null,
                    'tanggal_diambil' => null,
                    'status' => 'menunggu',
                    'alasan_ditolak' => null,
                ]);
            });

            return redirect()
                ->route('member.pengajuan.index')
                ->with(
                    'success',
                    'Pengajuan peminjaman berhasil dikirim.'
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
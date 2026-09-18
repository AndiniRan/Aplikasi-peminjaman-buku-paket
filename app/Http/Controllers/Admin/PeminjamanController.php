<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with([
            'user',
            'buku.kategori',
            'pengajuan',
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_awal')) {
            $query->whereDate(
                'tanggal_pinjam',
                '>=',
                $request->tanggal_awal
            );
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate(
                'tanggal_pinjam',
                '<=',
                $request->tanggal_akhir
            );
        }

        $peminjaman = $query
            ->orderByDesc('tanggal_pinjam')
            ->get();

        return view(
            'admin.transaksi.peminjaman.index',
            compact('peminjaman')
        );
    }

    public function create()
    {
        $member = User::whereIn('role', [
            'guru',
            'siswa',
        ])
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();

        $buku = Buku::with('kategori')
            ->where('stok_tersedia', '>', 0)
            ->orderBy('judul')
            ->get();

        return view(
            'admin.transaksi.peminjaman.create',
            compact('member', 'buku')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
            ],
            'buku_id' => [
                'required',
                'exists:buku,id',
            ],
            'tanggal_pinjam' => [
                'required',
                'date',
            ],
        ]);

        try {
            DB::transaction(function () use ($request) {
                $member = User::where(
                    'id',
                    $request->user_id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    !in_array($member->role, ['guru', 'siswa']) ||
                    $member->status !== 'aktif'
                ) {
                    throw new Exception(
                        'Member tidak aktif atau tidak dapat melakukan peminjaman.'
                    );
                }

                $buku = Buku::where(
                    'id',
                    $request->buku_id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($buku->stok_tersedia <= 0) {
                    throw new Exception(
                        'Stok buku sudah tidak tersedia.'
                    );
                }

                $tanggalPinjam = Carbon::parse(
                    $request->tanggal_pinjam
                )->startOfDay();

                Transaksi::create([
                    'user_id' => $member->id,
                    'buku_id' => $buku->id,
                    'pengajuan_id' => null,
                    'tanggal_pinjam' => $tanggalPinjam,
                    'tanggal_jatuh_tempo' => $tanggalPinjam
                        ->copy()
                        ->addDays(7),
                    'tanggal_kembali' => null,
                    'status' => 'dipinjam',
                ]);

                $buku->decrement('stok_tersedia');
            });

            return redirect()
                ->route('admin.transaksi.peminjaman.index')
                ->with(
                    'success',
                    'Peminjaman berhasil ditambahkan.'
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

    public function edit(Transaksi $transaksi)
    {
        if ($transaksi->status === 'dikembalikan') {
            return redirect()
                ->route('admin.transaksi.peminjaman.index')
                ->with(
                    'error',
                    'Transaksi yang sudah selesai tidak dapat diedit.'
                );
        }

        if ($transaksi->pengajuan_id !== null) {
            return redirect()
                ->route('admin.transaksi.peminjaman.index')
                ->with(
                    'error',
                    'Transaksi dari pengajuan tidak dapat diedit secara manual.'
                );
        }

        $transaksi->load([
            'user',
            'buku.kategori',
        ]);

        $member = User::whereIn('role', [
            'guru',
            'siswa',
        ])
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();

        $buku = Buku::with('kategori')
            ->orderBy('judul')
            ->get();

        return view(
            'admin.transaksi.peminjaman.edit',
            compact(
                'transaksi',
                'member',
                'buku'
            )
        );
    }

    public function update(
        Request $request,
        Transaksi $transaksi
    ) {
        $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
            ],
            'buku_id' => [
                'required',
                'exists:buku,id',
            ],
            'tanggal_pinjam' => [
                'required',
                'date',
            ],
        ]);

        try {
            DB::transaction(function () use (
                $request,
                $transaksi
            ) {
                $transaksi = Transaksi::where(
                    'transaksi_id',
                    $transaksi->transaksi_id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($transaksi->status === 'dikembalikan') {
                    throw new Exception(
                        'Transaksi yang sudah selesai tidak dapat diedit.'
                    );
                }

                if ($transaksi->pengajuan_id !== null) {
                    throw new Exception(
                        'Transaksi dari pengajuan tidak dapat diedit secara manual.'
                    );
                }

                $member = User::where(
                    'id',
                    $request->user_id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    !in_array($member->role, ['guru', 'siswa']) ||
                    $member->status !== 'aktif'
                ) {
                    throw new Exception(
                        'Member tidak aktif atau tidak dapat melakukan peminjaman.'
                    );
                }

                $bukuLamaId = (int) $transaksi->buku_id;
                $bukuBaruId = (int) $request->buku_id;

                if ($bukuLamaId !== $bukuBaruId) {
                    $bukuLama = Buku::where(
                        'id',
                        $bukuLamaId
                    )
                        ->lockForUpdate()
                        ->firstOrFail();

                    $bukuBaru = Buku::where(
                        'id',
                        $bukuBaruId
                    )
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($bukuBaru->stok_tersedia <= 0) {
                        throw new Exception(
                            'Stok buku yang dipilih sudah tidak tersedia.'
                        );
                    }

                    $bukuLama->increment('stok_tersedia');
                    $bukuBaru->decrement('stok_tersedia');
                }

                $tanggalPinjam = Carbon::parse(
                    $request->tanggal_pinjam
                )->startOfDay();

                $transaksi->update([
                    'user_id' => $member->id,
                    'buku_id' => $bukuBaruId,
                    'tanggal_pinjam' => $tanggalPinjam,
                    'tanggal_jatuh_tempo' => $tanggalPinjam
                        ->copy()
                        ->addDays(7),
                ]);
            });

            return redirect()
                ->route('admin.transaksi.peminjaman.index')
                ->with(
                    'success',
                    'Data peminjaman berhasil diperbarui.'
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
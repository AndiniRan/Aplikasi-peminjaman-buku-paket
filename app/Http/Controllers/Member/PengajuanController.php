<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Pengajuan;
use App\Models\Transaksi;
use App\Models\User;
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
            'detailPengajuan.buku.kategori',
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
        // Bisa menerima form satu buku lama,
        // atau form multi-buku baru.
        if ($request->has('buku_ids')) {
            $validated = $request->validate([
                'buku_ids' => [
                    'required',
                    'array',
                    'min:1',
                    'max:50',
                ],
                'buku_ids.*' => [
                    'required',
                    'integer',
                    'distinct',
                    'exists:buku,id',
                ],
                'jumlah' => [
                    'nullable',
                    'array',
                ],
                'jumlah.*' => [
                    'integer',
                    'min:1',
                    'max:1000',
                ],
            ]);

            $bukuIds = array_map(
                'intval',
                $validated['buku_ids']
            );

            $jumlahInput = $validated['jumlah'] ?? [];
        } else {
            $validated = $request->validate([
                'buku_id' => [
                    'required',
                    'integer',
                    'exists:buku,id',
                ],
            ], [
                'buku_id.required' => 'Buku wajib dipilih.',
                'buku_id.exists' => 'Buku tidak ditemukan.',
            ]);

            $bukuIds = [
                (int) $validated['buku_id']
            ];

            $jumlahInput = [];
        }

        $daftarBuku = [];

        foreach ($bukuIds as $bukuId) {
            $jumlah = (int) (
                $jumlahInput[$bukuId] ?? 1
            );

            $daftarBuku[$bukuId] = $jumlah;
        }

        ksort($daftarBuku);

        try {
            DB::transaction(function () use ($daftarBuku) {
                $userId = Auth::id();

                // Kunci user agar pengajuan bersamaan
                // milik member yang sama tidak bentrok.
                User::whereKey($userId)
                    ->lockForUpdate()
                    ->firstOrFail();

                $bukuIds = array_keys($daftarBuku);

                // Kunci seluruh buku secara berurutan.
                $bukuTerkunci = Buku::whereIn(
                    'id',
                    $bukuIds
                )
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($daftarBuku as $bukuId => $jumlah) {
                    $buku = $bukuTerkunci->get($bukuId);

                    if (!$buku) {
                        throw new Exception(
                            'Buku tidak ditemukan.'
                        );
                    }

                    if ($jumlah < 1 || $jumlah > 1000) {
                        throw new Exception(
                            'Jumlah buku tidak valid.'
                        );
                    }

                    if ($buku->stok_tersedia < $jumlah) {
                        throw new Exception(
                            'Stok buku "' .
                            $buku->judul .
                            '" tidak mencukupi.'
                        );
                    }

                    $pengajuanAktif = Pengajuan::where(
                        'user_id',
                        $userId
                    )
                        ->whereIn('status', [
                            'menunggu',
                            'siap',
                        ])
                        ->where(function ($query) use ($bukuId) {
                            $query->where(
                                'buku_id',
                                $bukuId
                            )
                            ->orWhereHas(
                                'detailPengajuan',
                                function ($detail) use ($bukuId) {
                                    $detail->where(
                                        'buku_id',
                                        $bukuId
                                    );
                                }
                            );
                        })
                        ->exists();

                    if ($pengajuanAktif) {
                        throw new Exception(
                            'Kamu masih memiliki pengajuan aktif untuk buku "' .
                            $buku->judul . '".'
                        );
                    }

                    // Validasi transaksi lama.
                    $sedangDipinjam = Transaksi::where(
                        'user_id',
                        $userId
                    )
                        ->where('buku_id', $bukuId)
                        ->whereIn('status', [
                            'dipinjam',
                            'terlambat',
                        ])
                        ->exists();

                    if ($sedangDipinjam) {
                        throw new Exception(
                            'Buku "' . $buku->judul .
                            '" masih sedang kamu pinjam.'
                        );
                    }
                }

                $pengajuan = Pengajuan::create([
                    'user_id' => $userId,

                    // Kolom lama sementara diisi
                    // agar halaman lama tetap kompatibel.
                    'buku_id' => $bukuIds[0],

                    'tanggal_pengajuan' => now(),
                    'tanggal_disiapkan' => null,
                    'batas_pengambilan' => null,
                    'tanggal_diambil' => null,
                    'status' => 'menunggu',
                    'alasan_ditolak' => null,
                ]);

                foreach ($daftarBuku as $bukuId => $jumlah) {
                    // Simpan detail buku pengajuan.
                    $pengajuan->detailPengajuan()->create([
                        'buku_id' => $bukuId,
                        'jumlah' => $jumlah,
                    ]);

                    // Stok berkurang sejak diajukan.
                    $bukuTerkunci
                        ->get($bukuId)
                        ->decrement(
                            'stok_tersedia',
                            $jumlah
                        );
                }
            }, 3);

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

    // Member membatalkan pengajuannya sendiri.
    public function batalkan(Pengajuan $pengajuan)
    {
        try {
            DB::transaction(function () use ($pengajuan) {
                $userId = Auth::id();

                User::whereKey($userId)
                    ->lockForUpdate()
                    ->firstOrFail();

                $pengajuan = Pengajuan::where(
                    'pengajuan_id',
                    $pengajuan->pengajuan_id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if ((int) $pengajuan->user_id !== (int) $userId) {
                    throw new Exception(
                        'Kamu tidak memiliki akses ke pengajuan ini.'
                    );
                }

                if ($pengajuan->status !== 'menunggu') {
                    throw new Exception(
                        'Hanya pengajuan berstatus menunggu yang dapat dibatalkan.'
                    );
                }

                $detail = $pengajuan->detailPengajuan()
                    ->orderBy('buku_id')
                    ->get();

                if ($detail->isEmpty()) {
                    throw new Exception(
                        'Detail pengajuan tidak ditemukan.'
                    );
                }

                $bukuTerkunci = Buku::whereIn(
                    'id',
                    $detail->pluck('buku_id')->all()
                )
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($detail as $item) {
                    $buku = $bukuTerkunci->get($item->buku_id);

                    if (!$buku) {
                        throw new Exception(
                            'Buku pada detail pengajuan tidak ditemukan.'
                        );
                    }

                    if ($item->jumlah < 1) {
                        throw new Exception(
                            'Jumlah buku tidak valid.'
                        );
                    }

                    if (
                        $buku->stok_tersedia + $item->jumlah >
                        $buku->total_stok
                    ) {
                        throw new Exception(
                            'Pengembalian stok melebihi total stok buku: ' .
                            $buku->judul
                        );
                    }

                    $buku->increment(
                        'stok_tersedia',
                        $item->jumlah
                    );
                }

                $pengajuan->update([
                    'status' => 'dibatalkan',
                ]);
            }, 3);

            return back()->with(
                'success',
                'Pengajuan berhasil dibatalkan dan stok buku dikembalikan.'
            );
        } catch (Exception $e) {
            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }
}

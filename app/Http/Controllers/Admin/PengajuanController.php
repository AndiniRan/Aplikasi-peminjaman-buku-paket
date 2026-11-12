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
            'detailPengajuan.buku.kategori',
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

        $statusFilter = $request->status;

        return view(
            'admin.pengajuan.index',
            compact('pengajuan', 'statusFilter')
        );
    }

    // Mengembalikan stok seluruh buku dalam pengajuan.
    // Dipanggil hanya saat status masih menunggu/siap.
    private function kembalikanStok(Pengajuan $pengajuan)
    {
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
                    'Jumlah buku pada detail pengajuan tidak valid.'
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
    }

    // Admin mengubah pengajuan menunggu menjadi siap.
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

                if (
                    !$pengajuan->detailPengajuan()->exists()
                ) {
                    throw new Exception(
                        'Detail buku pengajuan tidak ditemukan.'
                    );
                }

                $tanggalDisiapkan = now();

                $pengajuan->update([
                    'status' => 'siap',
                    'tanggal_disiapkan' => $tanggalDisiapkan,
                    'batas_pengambilan' => $tanggalDisiapkan
                        ->copy()
                        ->addDays(5),
                    'tanggal_diambil' => null,
                    'alasan_ditolak' => null,
                ]);

                // Tidak mengurangi stok di sini.
                // Stok sudah dikurangi saat member mengajukan.
            }, 3);

            return back()->with(
                'success',
                'Pengajuan berhasil dikonfirmasi. Buku siap diambil dalam 5 hari.'
            );
        } catch (Exception $e) {
            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    // Admin menolak pengajuan.
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

                if (!in_array(
                    $pengajuan->status,
                    ['menunggu', 'siap'],
                    true
                )) {
                    throw new Exception(
                        'Pengajuan ini tidak dapat ditolak.'
                    );
                }

                // Kembalikan stok sebelum mengubah status.
                $this->kembalikanStok($pengajuan);

                $pengajuan->update([
                    'status' => 'ditolak',
                    'alasan_ditolak' =>
                        $request->input('alasan_ditolak')
                        ?: 'Pengajuan ditolak oleh admin.',
                ]);
            }, 3);

            return back()->with(
                'success',
                'Pengajuan berhasil ditolak dan stok buku dikembalikan.'
            );
        } catch (Exception $e) {
            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    // Admin mengonfirmasi buku benar-benar diambil.
    public function diambil(Pengajuan $pengajuan)
    {
        try {
            $hasil = DB::transaction(function () use ($pengajuan) {
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

                // Jika melewati batas pengambilan,
                // langsung tolak dan kembalikan stok.
                if (
                    $pengajuan->batas_pengambilan &&
                    now()->greaterThan(
                        $pengajuan->batas_pengambilan
                    )
                ) {
                    $this->kembalikanStok($pengajuan);

                    $pengajuan->update([
                        'status' => 'ditolak',
                        'alasan_ditolak' =>
                            'Batas waktu pengambilan buku telah berakhir.',
                    ]);

                    return 'kedaluwarsa';
                }

                $detail = $pengajuan->detailPengajuan()
                    ->orderBy('buku_id')
                    ->get();

                if ($detail->isEmpty()) {
                    throw new Exception(
                        'Detail pengajuan tidak ditemukan.'
                    );
                }

                // Sementara tabel transaksi masih
                // mendukung satu jenis dan satu
                // eksemplar buku per transaksi.
                if (
                    $detail->count() !== 1 ||
                    (int) $detail->first()->jumlah !== 1
                ) {
                    throw new Exception(
                        'Pengajuan berisi beberapa buku atau eksemplar. ' .
                        'Konfirmasi pengambilan belum dapat dilakukan ' .
                        'sampai modul Peminjaman multi-buku selesai.'
                    );
                }

                $transaksiSudahAda = Transaksi::where(
                    'pengajuan_id',
                    $pengajuan->pengajuan_id
                )->exists();

                if ($transaksiSudahAda) {
                    throw new Exception(
                        'Transaksi untuk pengajuan ini sudah dibuat.'
                    );
                }

                $tanggalDiambil = now();

                // Satu buku tetap dibuatkan
                // transaksi lama secara normal.
                Transaksi::create([
                    'user_id' => $pengajuan->user_id,
                    'buku_id' => $detail->first()->buku_id,
                    'pengajuan_id' => $pengajuan->pengajuan_id,
                    'tanggal_pinjam' => $tanggalDiambil,
                    'tanggal_jatuh_tempo' => $tanggalDiambil
                        ->copy()
                        ->addDays(7),
                    'tanggal_kembali' => null,
                    'status' => 'dipinjam',
                ]);

                $pengajuan->update([
                    'status' => 'disetujui',
                    'tanggal_diambil' => $tanggalDiambil,
                ]);

                // Stok tidak dikurangi lagi.
                return 'berhasil';
            }, 3);

            if ($hasil === 'kedaluwarsa') {
                return back()->with(
                    'error',
                    'Batas pengambilan telah berakhir. ' .
                    'Pengajuan otomatis ditolak dan stok dikembalikan.'
                );
            }

            return back()->with(
                'success',
                'Buku berhasil diambil dan transaksi peminjaman dibuat.'
            );
        } catch (Exception $e) {
            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Buku;
use App\Models\Pengajuan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use RuntimeException;

class TolakPengajuanTerlambat extends Command
{
    /**
     * Nama perintah Artisan.
     */
    protected $signature = 'pengajuan:tolak-terlambat';

    /**
     * Deskripsi perintah.
     */
    protected $description =
        'Menolak pengajuan yang melewati batas pengambilan dan mengembalikan stok buku.';

    /**
     * Menjalankan pemeriksaan pengajuan terlambat.
     */
    public function handle(): int
    {
        $jumlahDitolak = 0;
        $jumlahGagal = 0;

        $waktuPemeriksaan = now();

        Pengajuan::where('status', 'siap')
            ->whereNotNull('batas_pengambilan')
            ->where(
                'batas_pengambilan',
                '<=',
                $waktuPemeriksaan
            )
            ->orderBy('pengajuan_id')
            ->chunkById(
                100,
                function ($daftarPengajuan) use (
                    &$jumlahDitolak,
                    &$jumlahGagal
                ) {
                    foreach ($daftarPengajuan as $item) {
                        try {
                            $berhasil = DB::transaction(
                                function () use ($item) {

                                    // Kunci pengajuan agar tidak
                                    // diproses dua kali bersamaan.
                                    $pengajuan = Pengajuan::where(
                                        'pengajuan_id',
                                        $item->pengajuan_id
                                    )
                                        ->lockForUpdate()
                                        ->first();

                                    if (!$pengajuan) {
                                        return false;
                                    }

                                    // Pastikan pengajuan masih siap
                                    // dan batas pengambilan terlewati.
                                    if (
                                        $pengajuan->status !== 'siap' ||
                                        !$pengajuan->batas_pengambilan ||
                                        $pengajuan->batas_pengambilan
                                            ->greaterThan(now())
                                    ) {
                                        return false;
                                    }

                                    // Ambil semua detail buku
                                    // dari pengajuan tersebut.
                                    $detailPengajuan = $pengajuan
                                        ->detailPengajuan()
                                        ->orderBy('buku_id')
                                        ->get();

                                    if ($detailPengajuan->isEmpty()) {
                                        throw new RuntimeException(
                                            'Detail pengajuan tidak ditemukan.'
                                        );
                                    }

                                    // Kunci semua buku yang akan
                                    // dikembalikan stoknya.
                                    $daftarBuku = Buku::whereIn(
                                        'id',
                                        $detailPengajuan
                                            ->pluck('buku_id')
                                            ->all()
                                    )
                                        ->orderBy('id')
                                        ->lockForUpdate()
                                        ->get()
                                        ->keyBy('id');

                                    // Validasi semua data sebelum
                                    // stok dikembalikan.
                                    foreach ($detailPengajuan as $detail) {
                                        $buku = $daftarBuku->get(
                                            $detail->buku_id
                                        );

                                        if (!$buku) {
                                            throw new RuntimeException(
                                                'Buku pada detail pengajuan tidak ditemukan.'
                                            );
                                        }

                                        if ((int) $detail->jumlah <= 0) {
                                            throw new RuntimeException(
                                                'Jumlah buku tidak valid.'
                                            );
                                        }

                                        if (
                                            $buku->stok_tersedia +
                                            $detail->jumlah >
                                            $buku->total_stok
                                        ) {
                                            throw new RuntimeException(
                                                'Pengembalian stok melebihi total stok buku: ' .
                                                $buku->judul
                                            );
                                        }
                                    }

                                    // Kembalikan stok seluruh buku.
                                    foreach ($detailPengajuan as $detail) {
                                        $buku = $daftarBuku->get(
                                            $detail->buku_id
                                        );

                                        $buku->increment(
                                            'stok_tersedia',
                                            $detail->jumlah
                                        );
                                    }

                                    // Ubah status menjadi ditolak.
                                    $pengajuan->update([
                                        'status' => 'ditolak',
                                        'alasan_ditolak' =>
                                            'Batas waktu pengambilan buku telah berakhir.',
                                    ]);

                                    return true;
                                },
                                3
                            );

                            if ($berhasil) {
                                $jumlahDitolak++;
                            }

                        } catch (Throwable $e) {
                            $jumlahGagal++;

                            Log::error(
                                'Gagal menolak pengajuan otomatis.',
                                [
                                    'pengajuan_id' =>
                                        $item->pengajuan_id,
                                    'error' => $e->getMessage(),
                                ]
                            );

                            $this->error(
                                'Gagal memproses pengajuan ID ' .
                                $item->pengajuan_id .
                                ': ' .
                                $e->getMessage()
                            );
                        }
                    }
                },
                'pengajuan_id'
            );

        $this->info(
            'Jumlah pengajuan otomatis ditolak: ' .
            $jumlahDitolak
        );

        if ($jumlahGagal > 0) {
            $this->error(
                'Jumlah pengajuan gagal diproses: ' .
                $jumlahGagal
            );

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}

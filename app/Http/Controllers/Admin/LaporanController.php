<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BukuExport;
use App\Exports\MemberExport;
use App\Exports\PeminjamanExport;
use App\Exports\PengembalianExport;
use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function peminjaman(Request $request)
    {
        $peminjaman = $this->queryPeminjaman($request)
            ->orderByDesc('tanggal_pinjam')
            ->get();

        return view(
            'admin.laporan.peminjaman',
            compact('peminjaman')
        );
    }

    public function pengembalian(Request $request)
    {
        $pengembalian = $this->queryPengembalian($request)
            ->orderByDesc('tanggal_kembali')
            ->get();

        return view(
            'admin.laporan.pengembalian',
            compact('pengembalian')
        );
    }

    public function buku(Request $request)
    {
        $buku = $this->queryBuku($request)
            ->orderBy('judul')
            ->get();

        $kategori = Kategori::orderBy('nama_kategori')
            ->get();

        return view(
            'admin.laporan.buku',
            compact(
                'buku',
                'kategori'
            )
        );
    }

    public function member(Request $request)
    {
        $member = $this->queryMember($request)
            ->orderBy('name')
            ->get();

        $kelas = User::query()
            ->whereIn('role', [
                'guru',
                'siswa',
            ])
            ->whereNotNull('kelas')
            ->where('kelas', '!=', '')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        return view(
            'admin.laporan.member',
            compact(
                'member',
                'kelas'
            )
        );
    }

    public function exportPeminjamanExcel(Request $request)
    {
        $data = $this->queryPeminjaman($request)
            ->orderByDesc('tanggal_pinjam')
            ->get();

        return Excel::download(
            new PeminjamanExport($data),
            'laporan-peminjaman-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportPengembalianExcel(Request $request)
    {
        $data = $this->queryPengembalian($request)
            ->orderByDesc('tanggal_kembali')
            ->get();

        return Excel::download(
            new PengembalianExport($data),
            'laporan-pengembalian-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportBukuExcel(Request $request)
    {
        $data = $this->queryBuku($request)
            ->orderBy('judul')
            ->get();

        return Excel::download(
            new BukuExport($data),
            'laporan-buku-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportMemberExcel(Request $request)
    {
        $data = $this->queryMember($request)
            ->orderBy('name')
            ->get();

        return Excel::download(
            new MemberExport($data),
            'laporan-member-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportPeminjamanPdf(Request $request)
    {
        $peminjaman = $this->queryPeminjaman($request)
            ->orderByDesc('tanggal_pinjam')
            ->get();

        $pdf = Pdf::loadView(
            'admin.laporan.pdf.peminjaman',
            compact('peminjaman')
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'laporan-peminjaman-' . now()->format('Y-m-d') . '.pdf'
        );
    }

    public function exportPengembalianPdf(Request $request)
    {
        $pengembalian = $this->queryPengembalian($request)
            ->orderByDesc('tanggal_kembali')
            ->get();

        $pdf = Pdf::loadView(
            'admin.laporan.pdf.pengembalian',
            compact('pengembalian')
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'laporan-pengembalian-' . now()->format('Y-m-d') . '.pdf'
        );
    }

    public function exportBukuPdf(Request $request)
    {
        $buku = $this->queryBuku($request)
            ->orderBy('judul')
            ->get();

        $kategoriDipilih = null;

        if ($request->filled('kategori_id')) {
            $kategoriDipilih = Kategori::find(
                $request->kategori_id
            );
        }

        $pdf = Pdf::loadView(
            'admin.laporan.pdf.buku',
            compact(
                'buku',
                'kategoriDipilih'
            )
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'laporan-buku-' . now()->format('Y-m-d') . '.pdf'
        );
    }

    public function exportMemberPdf(Request $request)
    {
        $member = $this->queryMember($request)
            ->orderBy('name')
            ->get();

        $pdf = Pdf::loadView(
            'admin.laporan.pdf.member',
            compact('member')
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'laporan-member-' . now()->format('Y-m-d') . '.pdf'
        );
    }

    private function queryPeminjaman(Request $request)
    {
        $query = Transaksi::with([
            'user',
            'buku.kategori',
            'pengajuan',
        ]);

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

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas(
                    'user',
                    function ($userQuery) use ($search) {
                        $userQuery
                            ->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'nis',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                );

                $q->orWhereHas(
                    'buku',
                    function ($bukuQuery) use ($search) {
                        $bukuQuery
                            ->where(
                                'judul',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'pengarang',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                );
            });
        }

        return $query;
    }

    private function queryPengembalian(Request $request)
    {
        $query = Transaksi::with([
            'user',
            'buku.kategori',
            'pengajuan',
        ])
            ->whereNotNull('tanggal_kembali');

        if ($request->filled('tanggal_awal')) {
            $query->whereDate(
                'tanggal_kembali',
                '>=',
                $request->tanggal_awal
            );
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate(
                'tanggal_kembali',
                '<=',
                $request->tanggal_akhir
            );
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas(
                    'user',
                    function ($userQuery) use ($search) {
                        $userQuery
                            ->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'nis',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                );

                $q->orWhereHas(
                    'buku',
                    function ($bukuQuery) use ($search) {
                        $bukuQuery
                            ->where(
                                'judul',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'pengarang',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                );
            });
        }

        return $query;
    }

    private function queryBuku(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->filled('kategori_id')) {
            $query->where(
                'kategori_id',
                $request->kategori_id
            );
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'judul',
                    'like',
                    '%' . $search . '%'
                )
                    ->orWhere(
                        'penerbit',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'pengarang',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'tahun_terbit',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }

        return $query;
    }

    private function queryMember(Request $request)
    {
        $query = User::query()
            ->whereIn('role', [
                'guru',
                'siswa',
            ]);

        if ($request->filled('kelas')) {
            $query->where(
                'kelas',
                $request->kelas
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'name',
                    'like',
                    '%' . $search . '%'
                )
                    ->orWhere(
                        'nis',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'kelas',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'email',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }

        return $query;
    }
}
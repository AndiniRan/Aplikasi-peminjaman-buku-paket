<?php

namespace App\Imports;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BukuImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (empty($row['judul'])) {
                continue;
            }

            $namaKategori = trim($row['kategori'] ?? '');

            if ($namaKategori === '') {
                continue;
            }

            $kategori = Kategori::where('nama_kategori', $namaKategori)->first();

            if (!$kategori) {
                continue;
            }

            $totalStok = max(0, (int) ($row['total_stok'] ?? 0));

            Buku::create([
                'kategori_id' => $kategori->id,
                'judul' => trim($row['judul']),
                'penerbit' => trim($row['penerbit'] ?? '-'),
                'pengarang' => trim($row['pengarang'] ?? '-'),
                'tahun_terbit' => (int) ($row['tahun_terbit'] ?? date('Y')),
                'total_stok' => $totalStok,
                'stok_tersedia' => $totalStok
            ]);
        }
    }
}
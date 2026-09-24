<?php

namespace App\Imports;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class BukuImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            $data = [
                'kategori' => trim((string) ($row['kategori'] ?? '')),
                'judul' => trim((string) ($row['judul'] ?? '')),
                'penerbit' => trim((string) ($row['penerbit'] ?? '')),
                'pengarang' => trim((string) ($row['pengarang'] ?? '')),
                'tahun_terbit' => $row['tahun_terbit'] ?? null,
                'total_stok' => $row['total_stok'] ?? null,
            ];

            Validator::make(
                $data,
                [
                    'kategori' => 'required|string|max:255',
                    'judul' => 'required|string|max:255',
                    'penerbit' => 'required|string|max:150',
                    'pengarang' => 'required|string|max:150',
                    'tahun_terbit' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                    'total_stok' => 'required|integer|min:0|max:100000',
                ],
                [],
                [
                    'kategori' => 'kategori pada baris ' . ($index + 2),
                    'judul' => 'judul pada baris ' . ($index + 2),
                    'penerbit' => 'penerbit pada baris ' . ($index + 2),
                    'pengarang' => 'pengarang pada baris ' . ($index + 2),
                    'tahun_terbit' => 'tahun terbit pada baris ' . ($index + 2),
                    'total_stok' => 'total stok pada baris ' . ($index + 2),
                ]
            )->validate();

            $kategori = Kategori::where(
                'nama_kategori',
                $data['kategori']
            )->first();

            if (!$kategori) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'file' => [
                        'Kategori "' . $data['kategori'] .
                        '" pada baris ' . ($index + 2) .
                        ' tidak ditemukan.'
                    ],
                ]);
            }

            Buku::create([
                'kategori_id' => $kategori->id,
                'judul' => $data['judul'],
                'penerbit' => $data['penerbit'],
                'pengarang' => $data['pengarang'],
                'tahun_terbit' => $data['tahun_terbit'],
                'total_stok' => $data['total_stok'],
                'stok_tersedia' => $data['total_stok'],
                'sampul' => null,
            ]);
        }
    }
}
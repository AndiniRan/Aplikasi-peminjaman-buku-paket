<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BukuExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    protected Collection $data;

    private int $nomor = 0;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection(): Enumerable
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Judul Buku',
            'Kategori',
            'Penerbit',
            'Pengarang',
            'Tahun Terbit',
            'Total Stok',
            'Stok Tersedia',
        ];
    }

    public function map($item): array
    {
        $this->nomor++;

        return [
            $this->nomor,
            $item->judul,
            $item->kategori?->nama_kategori ?? '-',
            $item->penerbit ?? '-',
            $item->pengarang ?? '-',
            $item->tahun_terbit ?? '-',
            $item->total_stok,
            $item->stok_tersedia,
        ];
    }
}
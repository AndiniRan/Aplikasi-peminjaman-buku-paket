<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PengembalianExport implements
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
            'NIS',
            'Nama Member',
            'Judul Buku',
            'Tanggal Pengajuan',
            'Tanggal Pinjam',
            'Jatuh Tempo',
            'Tanggal Kembali',
            'Status',
        ];
    }

    public function map($item): array
    {
        $this->nomor++;

        $status = 'Selesai';

        if (
            $item->tanggal_kembali &&
            $item->tanggal_jatuh_tempo &&
            $item->tanggal_kembali->gt(
                $item->tanggal_jatuh_tempo
            )
        ) {
            $status = 'Terlambat';
        }

        return [
            $this->nomor,
            $item->user?->nis ?? '-',
            $item->user?->name ?? '-',
            $item->buku?->judul ?? '-',
            $item->pengajuan?->tanggal_pengajuan?->format('d/m/Y') ?? '-',
            $item->tanggal_pinjam?->format('d/m/Y') ?? '-',
            $item->tanggal_jatuh_tempo?->format('d/m/Y') ?? '-',
            $item->tanggal_kembali?->format('d/m/Y') ?? '-',
            $status,
        ];
    }
}
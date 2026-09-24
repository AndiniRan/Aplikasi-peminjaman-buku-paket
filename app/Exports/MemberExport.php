<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MemberExport implements
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
            'Nama',
            'Role',
            'Kelas',
            'Email',
            'Status',
        ];
    }

    public function map($item): array
    {
        $this->nomor++;

        return [
            $this->nomor,
            $item->nis ?? '-',
            $item->name,
            ucfirst($item->role),
            $item->kelas ?? '-',
            $item->email ?? '-',
            ucfirst($item->status),
        ];
    }
}
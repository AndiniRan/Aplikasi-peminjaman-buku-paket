<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class SiswaImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $data = [
                'nis' => trim((string) ($row['nis'] ?? '')),
                'name' => trim((string) ($row['name'] ?? '')),
                'kelas' => trim((string) ($row['kelas'] ?? '')),
                'password' => trim((string) ($row['password'] ?? '')),
            ];

            Validator::make(
                $data,
                [
                    'nis' => 'required|string|max:50|unique:users,nis',
                    'name' => 'required|string|max:255',
                    'kelas' => 'required|string|max:50',
                    'password' => 'required|string|min:6',
                ],
                [],
                [
                    'nis' => 'NIS pada baris ' . ($index + 2),
                    'name' => 'nama pada baris ' . ($index + 2),
                    'kelas' => 'kelas pada baris ' . ($index + 2),
                    'password' => 'password pada baris ' . ($index + 2),
                ]
            )->validate();

            User::create([
                'nis' => $data['nis'],
                'name' => $data['name'],
                'kelas' => $data['kelas'],
                'email' => null,
                'password' => Hash::make($data['password']),
                'role' => 'siswa',
                'status' => 'aktif',
                'foto' => null,
            ]);
        }
    }
}
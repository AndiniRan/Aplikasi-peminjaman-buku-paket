<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class GuruImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $data = [
                'name' => trim((string) ($row['name'] ?? '')),
                'email' => trim((string) ($row['email'] ?? '')),
                'password' => trim((string) ($row['password'] ?? '')),
            ];

            Validator::make(
                $data,
                [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|string|min:6',
                ],
                [],
                [
                    'name' => 'nama pada baris ' . ($index + 2),
                    'email' => 'email pada baris ' . ($index + 2),
                    'password' => 'password pada baris ' . ($index + 2),
                ]
            )->validate();

            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'nis' => null,
                'kelas' => null,
                'password' => Hash::make($data['password']),
                'role' => 'guru',
                'status' => 'aktif',
                'foto' => null,
            ]);
        }
    }
}
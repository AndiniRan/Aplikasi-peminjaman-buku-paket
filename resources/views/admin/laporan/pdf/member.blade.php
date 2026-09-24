<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Laporan Member</title>
        <style>
            @page {
                margin: 25px;
            }

            body {
                margin: 0;
                color: #222222;
                font-family: DejaVu Sans, sans-serif;
                font-size: 9px;
            }

            .header {
                margin-bottom: 18px;
                text-align: center;
            }

            .header h2 {
                margin: 0 0 5px;
                font-size: 18px;
            }

            .header p {
                margin: 0;
                color: #666666;
                font-size: 10px;
            }

            .filter-info {
                margin-bottom: 12px;
                padding: 8px 10px;
                border: 1px solid #dddddd;
                background: #f7f7f7;
            }

            .filter-info p {
                margin: 2px 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                padding: 6px 5px;
                border: 1px solid #777777;
                vertical-align: middle;
            }

            th {
                background: #d9edf9;
                font-size: 9px;
                text-align: center;
            }

            td {
                font-size: 9px;
            }

            .text-center {
                text-align: center;
            }

            .footer {
                margin-top: 12px;
                color: #666666;
                font-size: 8px;
                text-align: right;
            }
        </style>
    </head>

    <body>
        <div class="header">
            <h2>Laporan Member</h2>
            <p>Perpustakaan Peminjaman Buku Paket</p>
        </div>

        @if(request('kelas') || request('status'))
            <div class="filter-info">
                @if(request('kelas'))
                    <p>
                        <strong>Kelas:</strong>
                        {{ request('kelas') }}
                    </p>
                @endif

                @if(request('status'))
                    <p>
                        <strong>Status:</strong>
                        {{ ucfirst(request('status')) }}
                    </p>
                @endif
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="14%">NIS</th>
                    <th width="24%">Nama</th>
                    <th width="10%">Role</th>
                    <th width="12%">Kelas</th>
                    <th width="23%">Email</th>
                    <th width="12%">Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($member as $item)
                    <tr>
                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $item->nis ?? '-' }}
                        </td>

                        <td>
                            {{ $item->name }}
                        </td>

                        <td class="text-center">
                            {{ ucfirst($item->role) }}
                        </td>

                        <td class="text-center">
                            {{ $item->kelas ?? '-' }}
                        </td>

                        <td>
                            {{ $item->email ?? '-' }}
                        </td>

                        <td class="text-center">
                            {{ ucfirst($item->status) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            Tidak ada data member.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            Dicetak pada {{ now()->format('d/m/Y H:i') }}
        </div>
    </body>
</html>
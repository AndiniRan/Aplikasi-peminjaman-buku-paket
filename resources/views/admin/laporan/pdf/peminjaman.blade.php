<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Laporan Peminjaman</title>
        <style>
            @page {
                margin: 25px;
            }

            body {
                margin: 0;
                color: #222222;
                font-family: DejaVu Sans, sans-serif;
                font-size: 10px;
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
            <h2>Laporan Peminjaman Buku</h2>
            <p>Perpustakaan Peminjaman Buku Paket</p>
        </div>

        @if(request('tanggal_awal') || request('tanggal_akhir'))
            <div class="filter-info">
                <p>
                    <strong>Periode:</strong>

                    {{ request('tanggal_awal')
                        ? \Carbon\Carbon::parse(request('tanggal_awal'))->format('d/m/Y')
                        : 'Semua' }}

                    s/d

                    {{ request('tanggal_akhir')
                        ? \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y')
                        : 'Semua' }}
                </p>
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th width="9%">NIS</th>
                    <th width="14%">Member</th>
                    <th width="19%">Buku</th>
                    <th width="13%">Tgl Pengajuan</th>
                    <th width="12%">Tgl Pinjam</th>
                    <th width="12%">Jatuh Tempo</th>
                    <th width="10%">Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($peminjaman as $item)
                    <tr>
                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $item->user?->nis ?? '-' }}
                        </td>

                        <td>
                            {{ $item->user?->name ?? '-' }}
                        </td>

                        <td>
                            {{ $item->buku?->judul ?? '-' }}
                        </td>

                        <td class="text-center">
                            {{ $item->pengajuan?->tanggal_pengajuan?->format('d/m/Y') ?? '-' }}
                        </td>

                        <td class="text-center">
                            {{ $item->tanggal_pinjam?->format('d/m/Y') ?? '-' }}
                        </td>

                        <td class="text-center">
                            {{ $item->tanggal_jatuh_tempo?->format('d/m/Y') ?? '-' }}
                        </td>

                        <td class="text-center">
                            @if($item->status === 'dikembalikan')
                                Selesai
                            @elseif($item->status === 'terlambat')
                                Terlambat
                            @else
                                Dipinjam
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            Tidak ada data peminjaman.
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
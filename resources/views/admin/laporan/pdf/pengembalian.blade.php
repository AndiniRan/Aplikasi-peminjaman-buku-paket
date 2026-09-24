<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Laporan Pengembalian</title>
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
                padding: 5px 4px;
                border: 1px solid #777777;
                vertical-align: middle;
            }

            th {
                background: #d9edf9;
                font-size: 8px;
                text-align: center;
            }

            td {
                font-size: 8px;
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
            <h2>Laporan Pengembalian Buku</h2>
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
                    <th>No</th>
                    <th>NIS</th>
                    <th>Member</th>
                    <th>Buku</th>
                    <th>Tgl Pengajuan</th>
                    <th>Tgl Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pengembalian as $item)
                    @php
                        $terlambat =
                            $item->tanggal_kembali &&
                            $item->tanggal_jatuh_tempo &&
                            $item->tanggal_kembali->gt(
                                $item->tanggal_jatuh_tempo
                            );
                    @endphp

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
                            {{ $item->tanggal_kembali?->format('d/m/Y') ?? '-' }}
                        </td>

                        <td class="text-center">
                            {{ $terlambat ? 'Terlambat' : 'Selesai' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            Tidak ada data pengembalian.
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
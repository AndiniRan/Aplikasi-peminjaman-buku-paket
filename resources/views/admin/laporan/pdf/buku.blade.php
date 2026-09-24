<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Buku</title>
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
            <h2>Laporan Data Buku</h2>
            <p>Perpustakaan Peminjaman Buku Paket</p>
        </div>

        @if($kategoriDipilih)
            <div class="filter-info">
                <p>
                    <strong>Kategori:</strong>
                    {{ $kategoriDipilih->nama_kategori }}
                </p>
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th width="24%">Judul Buku</th>
                    <th width="13%">Kategori</th>
                    <th width="15%">Penerbit</th>
                    <th width="17%">Pengarang</th>
                    <th width="10%">Tahun</th>
                    <th width="9%">Total Stok</th>
                    <th width="9%">Tersedia</th>
                </tr>
            </thead>

            <tbody>
                @forelse($buku as $item)
                    <tr>
                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $item->judul }}
                        </td>

                        <td>
                            {{ $item->kategori?->nama_kategori ?? '-' }}
                        </td>

                        <td>
                            {{ $item->penerbit }}
                        </td>

                        <td>
                            {{ $item->pengarang }}
                        </td>

                        <td class="text-center">
                            {{ $item->tahun_terbit }}
                        </td>

                        <td class="text-center">
                            {{ $item->total_stok }}
                        </td>

                        <td class="text-center">
                            {{ $item->stok_tersedia }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            Tidak ada data buku.
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
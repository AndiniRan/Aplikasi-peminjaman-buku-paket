@extends('layouts.dashboard')

@section('title', 'Laporan Buku')
@section('page-title', 'Laporan Buku')

@vite([
    'resources/css/adminLaporan.css',
    'resources/js/adminLaporan.js'
])

@section('content')

<div class="laporan-page">
    <div class="laporan-card">
        <div class="laporan-title">
            <h2>Laporan Buku</h2>
            <p>Daftar seluruh data dan stok buku perpustakaan</p>
        </div>

        <form action="{{ route('admin.laporan.buku') }}" method="GET" class="laporan-filter-form">
            <div class="laporan-filter-group">
                <label>Kategori</label>

                <div class="laporan-filter-select">
                    <input type="hidden" name="kategori_id" value="{{ request('kategori_id') }}" class="laporan-filter-input">

                    <button type="button" class="laporan-filter-button">
                        <span class="laporan-filter-text">
                            @if(request('kategori_id'))
                                {{ $kategori->firstWhere('id', request('kategori_id'))?->nama_kategori ?? 'Semua Kategori' }}
                            @else
                                Semua Kategori
                            @endif
                        </span>

                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="laporan-filter-options">
                        <button type="button" data-value="">
                            Semua Kategori
                        </button>

                        @foreach($kategori as $item)
                            <button
                                type="button"
                                data-value="{{ $item->id }}"
                                @class([
                                    'active' =>
                                        (string) request('kategori_id') ===
                                        (string) $item->id
                                ])
                            >
                                {{ $item->nama_kategori }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="laporan-filter-actions">
                <button type="submit" class="laporan-btn-filter">
                    <i class="bi bi-search"></i>
                    Tampilkan
                </button>

                <a href="{{ route('admin.laporan.buku') }}" class="laporan-btn-refresh">
                    <i class="bi bi-arrow-clockwise"></i>
                    Refresh
                </a>

                <div class="laporan-export">
                    <button type="button" class="laporan-btn-export" data-export-button>
                        <i class="bi bi-download"></i>
                        Export
                        <i class="bi bi-chevron-down export-chevron"></i>
                    </button>

                    <div class="laporan-export-menu">
                        <a href="{{ route('admin.laporan.buku.export.excel', request()->query()) }}" data-export-link>
                            <i class="bi bi-file-earmark-excel"></i>
                            Excel
                        </a>

                        <a href="{{ route('admin.laporan.buku.export.pdf', request()->query()) }}" data-export-link>
                            <i class="bi bi-file-earmark-pdf"></i>
                            PDF
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <div class="laporan-control-row">
            <div class="laporan-show-control">
                <span>Show</span>

                <div class="entries-filter-select">
                    <button type="button" class="entries-filter-button">
                        <span class="entries-value">10</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="entries-filter-options">
                        <button type="button" data-value="5">5</button>
                        <button type="button" data-value="10" class="active">10</button>
                        <button type="button" data-value="25">25</button>
                        <button type="button" data-value="50">50</button>
                    </div>
                </div>
                <span>entries</span>
            </div>

            <div class="laporan-search">
                <i class="bi bi-search"></i>
                <input type="text" class="laporan-search-input" placeholder="Cari buku..." value="{{ request('search') }}" autocomplete="off">
            </div>
        </div>

        <div class="laporan-table-wrapper">
            <table class="laporan-table buku-table">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th>Sampul</th>
                        <th>Judul Buku</th>
                        <th>Kategori</th>
                        <th>Penerbit</th>
                        <th>Pengarang</th>
                        <th>Tahun Terbit</th>
                        <th>Total Stok</th>
                        <th>Tersedia</th>
                    </tr>
                </thead>

                <tbody class="laporan-table-body">
                    @forelse($buku as $item)
                        <tr
                            class="laporan-row"
                            data-search="
                                {{ $item->judul }}
                                {{ $item->kategori?->nama_kategori ?? '' }}
                                {{ $item->penerbit }}
                                {{ $item->pengarang }}
                                {{ $item->tahun_terbit }}
                            "
                        >
                            <td class="text-center row-number">
                                {{ $loop->iteration }}
                            </td>

                            <td class="text-center">
                                @if($item->sampul)
                                    <img src="{{ asset('storage/' . $item->sampul) }}" alt="{{ $item->judul }}" class="laporan-book-cover">
                                @else
                                    <div class="laporan-book-placeholder">
                                        <i class="bi bi-book"></i>
                                    </div>
                                @endif
                            </td>

                            <td class="laporan-book-title">
                                {{ $item->judul }}
                            </td>

                            <td>
                                {{ $item->kategori?->nama_kategori ?? '-' }}
                            </td>

                            <td>
                                {{ $item->penerbit ?? '-' }}
                            </td>

                            <td>
                                {{ $item->pengarang ?? '-' }}
                            </td>

                            <td class="text-center">
                                {{ $item->tahun_terbit ?? '-' }}
                            </td>

                            <td class="text-center">
                                {{ $item->total_stok }}
                            </td>

                            <td class="text-center">
                                {{ $item->stok_tersedia }}
                            </td>
                        </tr>
                    @empty
                        <tr class="database-empty-row">
                            <td colspan="9">
                                <div class="laporan-empty">
                                    <i class="bi bi-inbox"></i>
                                    <strong>Belum ada data buku</strong>
                                    <span>Data buku belum tersedia.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="laporan-table-footer">
            <div class="laporan-info">
                Belum ada data buku
            </div>

            <div class="laporan-pagination"></div>
        </div>
    </div>
</div>

@endsection
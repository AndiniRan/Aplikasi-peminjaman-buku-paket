@extends('layouts.dashboard')

@section('title', 'Laporan Pengembalian')
@section('page-title', 'Laporan Pengembalian')

@vite([
    'resources/css/adminLaporan.css',
    'resources/js/adminLaporan.js'
])

@section('content')

<div class="laporan-page">
    <div class="laporan-card">
        <div class="laporan-title">
            <h2>Laporan Pengembalian</h2>
            <p>Daftar seluruh transaksi pengembalian buku</p>
        </div>

        <form action="{{ route('admin.laporan.pengembalian') }}" method="GET" class="laporan-filter-form">
            <div class="laporan-filter-group">
                <label>Tanggal Awal</label>

                <div class="laporan-date-picker" data-date-picker>
                    <input type="hidden" name="tanggal_awal" value="{{ request('tanggal_awal') }}" data-date-value>

                    <button type="button" class="laporan-date-button" data-date-button>
                        <span data-date-text>Pilih tanggal</span>
                        <i class="bi bi-calendar3"></i>
                    </button>

                    <div class="laporan-calendar">
                        <div class="laporan-calendar-header">
                            <button type="button" data-calendar-prev>
                                <i class="bi bi-chevron-left"></i>
                            </button>

                            <strong data-calendar-title></strong>

                            <button type="button" data-calendar-next>
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>

                        <div class="laporan-calendar-days">
                            <span>Min</span>
                            <span>Sen</span>
                            <span>Sel</span>
                            <span>Rab</span>
                            <span>Kam</span>
                            <span>Jum</span>
                            <span>Sab</span>
                        </div>

                        <div class="laporan-calendar-grid" data-calendar-grid></div>
                    </div>
                </div>
            </div>

            <div class="laporan-filter-group">
                <label>Tanggal Akhir</label>

                <div class="laporan-date-picker" data-date-picker>
                    <input type="hidden" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" data-date-value>

                    <button type="button" class="laporan-date-button" data-date-button>
                        <span data-date-text>Pilih tanggal</span>
                        <i class="bi bi-calendar3"></i>
                    </button>

                    <div class="laporan-calendar">
                        <div class="laporan-calendar-header">
                            <button type="button" data-calendar-prev>
                                <i class="bi bi-chevron-left"></i>
                            </button>

                            <strong data-calendar-title></strong>

                            <button type="button" data-calendar-next>
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>

                        <div class="laporan-calendar-days">
                            <span>Min</span>
                            <span>Sen</span>
                            <span>Sel</span>
                            <span>Rab</span>
                            <span>Kam</span>
                            <span>Jum</span>
                            <span>Sab</span>
                        </div>

                        <div class="laporan-calendar-grid" data-calendar-grid></div>
                    </div>
                </div>
            </div>

            <div class="laporan-filter-actions">
                <button type="submit" class="laporan-btn-filter">
                    <i class="bi bi-search"></i>
                    Tampilkan
                </button>

                <a href="{{ route('admin.laporan.pengembalian') }}" class="laporan-btn-refresh">
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
                        <a href="{{ route('admin.laporan.pengembalian.export.excel', request()->query()) }}" data-export-link>
                            <i class="bi bi-file-earmark-excel"></i>
                            Excel
                        </a>

                        <a href="{{ route('admin.laporan.pengembalian.export.pdf', request()->query()) }}" data-export-link>
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

                <input type="text" class="laporan-search-input" placeholder="Cari pengembalian..." value="{{ request('search') }}" autocomplete="off">
            </div>
        </div>

        <div class="laporan-table-wrapper">
            <table class="laporan-table pengembalian-table">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th>Member</th>
                        <th>Buku</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Tanggal Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody class="laporan-table-body">
                    @forelse($pengembalian as $item)
                        @php
                            $terlambat =
                                $item->tanggal_kembali &&
                                $item->tanggal_jatuh_tempo &&
                                $item->tanggal_kembali->gt(
                                    $item->tanggal_jatuh_tempo
                                );
                        @endphp

                        <tr
                            class="laporan-row"
                            data-search="
                                {{ $item->user?->nis ?? '' }}
                                {{ $item->user?->name ?? '' }}
                                {{ $item->buku?->judul ?? '' }}
                            "
                        >
                            <td class="text-center row-number">
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                <strong>
                                    {{ $item->user?->name ?? '-' }}
                                </strong>

                                @if($item->user?->nis)
                                    <small class="laporan-subtext">
                                        {{ $item->user->nis }}
                                    </small>
                                @endif
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
                                @if($terlambat)
                                    <span class="laporan-status terlambat">
                                        Terlambat
                                    </span>
                                @else
                                    <span class="laporan-status selesai">
                                        Selesai
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr class="database-empty-row">
                            <td colspan="8">
                                <div class="laporan-empty">
                                    <i class="bi bi-inbox"></i>
                                    <strong>Belum ada data pengembalian</strong>
                                    <span>Data pengembalian belum tersedia.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="laporan-table-footer">
            <div class="laporan-info">
                Belum ada data pengembalian
            </div>

            <div class="laporan-pagination"></div>
        </div>
    </div>
</div>

@endsection
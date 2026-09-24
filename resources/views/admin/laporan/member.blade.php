@extends('layouts.dashboard')

@section('title', 'Laporan Member')
@section('page-title', 'Laporan Member')

@vite([
    'resources/css/adminLaporan.css',
    'resources/js/adminLaporan.js'
])

@section('content')

<div class="laporan-page">
    <div class="laporan-card">
        <div class="laporan-title">
            <h2>Laporan Member</h2>
            <p>Daftar seluruh member siswa dan guru perpustakaan</p>
        </div>

        <form action="{{ route('admin.laporan.member') }}" method="GET" class="laporan-filter-form">
            <div class="laporan-filter-group">
                <label>Kelas</label>

                <div class="laporan-filter-select">
                    <input type="hidden" name="kelas" value="{{ request('kelas') }}" class="laporan-filter-input">

                    <button type="button" class="laporan-filter-button">
                        <span class="laporan-filter-text">
                            {{ request('kelas') ?: 'Semua Kelas' }}
                        </span>

                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="laporan-filter-options">
                        <button type="button" data-value="">
                            Semua Kelas
                        </button>

                        @foreach($kelas as $item)
                            <button
                                type="button"
                                data-value="{{ $item }}"
                                @class([
                                    'active' => request('kelas') === $item
                                ])
                            >
                                {{ $item }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="laporan-filter-group">
                <label>Status</label>

                <div class="laporan-filter-select">
                    <input type="hidden" name="status" value="{{ request('status') }}" class="laporan-filter-input">

                    <button type="button" class="laporan-filter-button">
                        <span class="laporan-filter-text">
                            @if(request('status') === 'aktif')
                                Aktif
                            @elseif(request('status') === 'nonaktif')
                                Nonaktif
                            @else
                                Semua Status
                            @endif
                        </span>

                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="laporan-filter-options">
                        <button type="button" data-value="">
                            Semua Status
                        </button>

                        <button
                            type="button"
                            data-value="aktif"
                            @class([
                                'active' => request('status') === 'aktif'
                            ])
                        >
                            Aktif
                        </button>

                        <button
                            type="button"
                            data-value="nonaktif"
                            @class([
                                'active' => request('status') === 'nonaktif'
                            ])
                        >
                            Nonaktif
                        </button>
                    </div>
                </div>
            </div>

            <div class="laporan-filter-actions">
                <button type="submit" class="laporan-btn-filter">
                    <i class="bi bi-search"></i>
                    Tampilkan
                </button>

                <a href="{{ route('admin.laporan.member') }}" class="laporan-btn-refresh">
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
                        <a href="{{ route('admin.laporan.member.export.excel', request()->query()) }}" data-export-link>
                            <i class="bi bi-file-earmark-excel"></i>
                            Excel
                        </a>

                        <a href="{{ route('admin.laporan.member.export.pdf', request()->query()) }}" data-export-link>
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

                <input type="text" class="laporan-search-input" placeholder="Cari member..." value="{{ request('search') }}" autocomplete="off">
            </div>
        </div>

        <div class="laporan-table-wrapper">
            <table class="laporan-table member-table">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th>NIS</th>
                        <th>Nama Lengkap</th>
                        <th>Role</th>
                        <th>Kelas</th>
                        <th>Email</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody class="laporan-table-body">
                    @forelse($member as $item)
                        <tr
                            class="laporan-row"
                            data-search="
                                {{ $item->nis }}
                                {{ $item->name }}
                                {{ $item->role }}
                                {{ $item->kelas }}
                                {{ $item->email }}
                                {{ $item->status }}
                            "
                        >
                            <td class="text-center row-number">
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $item->nis ?? '-' }}
                            </td>

                            <td>
                                <strong>{{ $item->name }}</strong>
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
                                @if($item->status === 'aktif')
                                    <span class="laporan-status aktif">
                                        Aktif
                                    </span>
                                @else
                                    <span class="laporan-status nonaktif">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr class="database-empty-row">
                            <td colspan="7">
                                <div class="laporan-empty">
                                    <i class="bi bi-inbox"></i>
                                    <strong>Belum ada data member</strong>
                                    <span>Data member belum tersedia.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="laporan-table-footer">
            <div class="laporan-info">
                Belum ada data member
            </div>

            <div class="laporan-pagination"></div>
        </div>
    </div>
</div>

@endsection
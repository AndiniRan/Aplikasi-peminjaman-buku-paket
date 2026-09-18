@extends('layouts.dashboard')

@section('title', 'Peminjaman')
@section('page-title', 'Peminjaman')

@vite([
    'resources/css/adminTransaksi.css',
    'resources/js/adminTransaksi.js'
])

@section('content')
<div class="transaksi-page" data-page="peminjaman">
    <div class="transaksi-card">
        <div class="transaksi-title">
            <h2>Daftar seluruh peminjaman buku</h2>
        </div>

        @if(session('success'))
            <div class="transaksi-alert transaksi-alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="transaksi-alert transaksi-alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="transaksi-top-actions">
            <a href="{{ route('admin.transaksi.peminjaman.create') }}" class="transaksi-top-btn transaksi-add-loan">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Peminjaman</span>
            </a>
        </div>

        <div class="transaksi-controls">
            <div class="transaksi-controls-left">
                <div class="transaksi-show-control">
                    <span>Show</span>
                    <div class="transaksi-dropdown" data-dropdown>
                        <button type="button" class="transaksi-dropdown-button" data-dropdown-button>
                            <span data-entries-text>10</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>

                        <div class="transaksi-dropdown-menu">
                            <button type="button" data-entries="5">
                                5
                            </button>

                            <button type="button" data-entries="10" class="active">
                                10
                            </button>

                            <button type="button" data-entries="25">
                                25
                            </button>

                            <button type="button" data-entries="50">
                                50
                            </button>
                        </div>
                    </div>

                    <span>entries</span>
                </div>

                <div class="transaksi-status-control">
                    <span>Status</span>
                    <div class="transaksi-dropdown" data-dropdown>
                        <button type="button" class="transaksi-dropdown-button transaksi-status-button" data-dropdown-button>
                            <span data-status-text>Semua</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>

                        <div class="transaksi-dropdown-menu">
                            <button type="button" data-status="" class="active">
                                Semua
                            </button>

                            <button type="button" data-status="dipinjam">
                                Dipinjam
                            </button>

                            <button type="button" data-status="terlambat">
                                Terlambat
                            </button>

                            <button type="button" data-status="dikembalikan">
                                Selesai
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="transaksi-search">
                <i class="bi bi-search"></i>
                <input type="text" id="transaksiSearch" placeholder="Cari transaksi..." autocomplete="off">
            </div>
        </div>

        <div class="transaksi-table-wrapper">
            <table class="transaksi-table" id="transaksiTable">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th>Member</th>
                        <th>Buku</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Tanggal Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Tanggal Kembali</th>
                        <th class="col-status">Status</th>
                        <th class="col-action">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($peminjaman as $item)
                        @php
                            $tanggalPengajuan = $item->pengajuan?->tanggal_pengajuan;

                            $sampul = $item->buku?->sampul
                                ? asset('storage/' . $item->buku->sampul)
                                : asset('images/default-book.png');

                            $kelasMember = $item->user?->role === 'siswa'
                                ? ($item->user?->kelas ?? '-')
                                : 'Guru';
                        @endphp

                        <tr class="transaksi-row" data-transaksi-row data-status="{{ $item->status }}">
                            <td class="transaksi-number">
                                {{ $loop->iteration }}
                            </td>

                            <td class="transaksi-member-name">
                                {{ $item->user?->name ?? '-' }}
                            </td>

                            <td class="transaksi-book-name">
                                {{ $item->buku?->judul ?? '-' }}
                            </td>

                            <td>
                                {{ $tanggalPengajuan
                                    ? $tanggalPengajuan->format('d M Y')
                                    : '-' }}
                            </td>

                            <td>
                                {{ $item->tanggal_pinjam
                                    ? $item->tanggal_pinjam->format('d M Y')
                                    : '-' }}
                            </td>

                            <td>
                                {{ $item->tanggal_jatuh_tempo
                                    ? $item->tanggal_jatuh_tempo->format('d M Y')
                                    : '-' }}
                            </td>

                            <td>
                                {{ $item->tanggal_kembali
                                    ? $item->tanggal_kembali->format('d M Y')
                                    : '-' }}
                            </td>

                            <td>
                                @if($item->status === 'dipinjam')
                                    <span class="transaksi-badge badge-dipinjam">
                                        Dipinjam
                                    </span>
                                @elseif($item->status === 'terlambat')
                                    <span class="transaksi-badge badge-terlambat">
                                        Terlambat
                                    </span>
                                @else
                                    <span class="transaksi-badge badge-selesai">
                                        Selesai
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="transaksi-actions">
                                    <button type="button"
                                            class="transaksi-action-btn detail"
                                            title="Detail"
                                            data-detail
                                            data-member="{{ $item->user?->name ?? '-' }}"
                                            data-kelas="{{ $kelasMember }}"
                                            data-buku="{{ $item->buku?->judul ?? '-' }}"
                                            data-penerbit="{{ $item->buku?->penerbit ?? '-' }}"
                                            data-kategori="{{ $item->buku?->kategori?->nama_kategori ?? '-' }}"
                                            data-sampul="{{ $sampul }}"
                                            data-pengajuan="{{ $tanggalPengajuan ? $tanggalPengajuan->format('d M Y') : '-' }}"
                                            data-pinjam="{{ $item->tanggal_pinjam ? $item->tanggal_pinjam->format('d M Y') : '-' }}"
                                            data-jatuh-tempo="{{ $item->tanggal_jatuh_tempo ? $item->tanggal_jatuh_tempo->format('d M Y') : '-' }}"
                                            data-kembali="{{ $item->tanggal_kembali ? $item->tanggal_kembali->format('d M Y') : '-' }}"
                                            data-status="{{ $item->status }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    @if(
                                        $item->pengajuan_id === null &&
                                        $item->status !== 'dikembalikan'
                                    )
                                        <a href="{{ route('admin.transaksi.peminjaman.edit', $item) }}"
                                           class="transaksi-action-btn edit"
                                           title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr class="transaksi-empty-row">
                            <td colspan="9">
                                <div class="transaksi-empty">
                                    <i class="bi bi-inbox"></i>
                                    <strong>Belum ada transaksi</strong>
                                    <span>Data peminjaman akan muncul di sini.</span>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                    <tr class="transaksi-search-empty"
                        id="transaksiSearchEmpty">
                        <td colspan="9">
                            <div class="transaksi-empty">
                                <i class="bi bi-search"></i>
                                <strong>Data tidak ditemukan</strong>
                                <span>Coba gunakan kata kunci atau filter lain.</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="transaksi-footer">
            <div class="transaksi-info" id="transaksiInfo">
            </div>

            <div class="transaksi-pagination" id="transaksiPagination">
            </div>
        </div>
    </div>
</div>

<div class="transaksi-modal" id="transaksiDetailModal">
    <div class="transaksi-modal-overlay" data-modal-close>
    </div>

    <div class="transaksi-modal-box">
        <div class="transaksi-modal-header">
            <h3>Detail Transaksi</h3>
            <button type="button" class="transaksi-modal-x" data-modal-close>
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="transaksi-modal-content">
            <div class="transaksi-detail-left">
                <div class="transaksi-book-detail">
                    <h4>Buku</h4>
                    <img src="" alt="Sampul Buku" id="detailSampul">
                    <strong id="detailBuku">-</strong>

                    <div class="transaksi-book-info">
                        <span>Penerbit</span>
                        <b>:</b>
                        <span id="detailPenerbit">-</span>
                    </div>

                    <div class="transaksi-book-info">
                        <span>Kategori</span>
                        <b>:</b>
                        <span id="detailKategori">-</span>
                    </div>
                </div>

                <div class="transaksi-member-detail">
                    <h4>Member</h4>
                    <div class="transaksi-member-row">
                        <div class="transaksi-member-avatar">
                            <i class="bi bi-person-fill"></i>
                        </div>

                        <div>
                            <strong id="detailMember">-</strong>
                            <p> Kelas :
                                <span id="detailKelas">-</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="transaksi-detail-divider"></div>
            <div class="transaksi-detail-right">
                <div class="transaksi-detail-row">
                    <i class="bi bi-calendar3"></i>
                    <span>Tanggal Pengajuan</span>
                    <b>:</b>
                    <span id="detailPengajuan">-</span>
                </div>

                <div class="transaksi-detail-row">
                    <i class="bi bi-calendar-check"></i>
                    <span>Tanggal Pinjam</span>
                    <b>:</b>
                    <span id="detailPinjam">-</span>
                </div>

                <div class="transaksi-detail-row">
                    <i class="bi bi-clock-history"></i>
                    <span>Jatuh Tempo</span>
                    <b>:</b>
                    <span id="detailJatuhTempo">-</span>
                </div>

                <div class="transaksi-detail-row">
                    <i class="bi bi-arrow-return-left"></i>
                    <span>Tanggal Kembali</span>
                    <b>:</b>
                    <span id="detailKembali">-</span>
                </div>

                <div class="transaksi-detail-status">
                    <div>
                        <i class="bi bi-record-circle"></i>
                        <span>Status</span>
                    </div>

                    <span class="transaksi-badge" id="detailStatus">
                        -
                    </span>
                </div>
            </div>
        </div>

        <div class="transaksi-modal-footer">
            <button type="button" class="transaksi-close-btn" data-modal-close>
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection
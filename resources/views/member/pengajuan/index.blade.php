@extends('layouts.dashboard')

@section('title', 'Pengajuan Saya')
@section('page-title', 'Pengajuan Saya')

@vite([
    'resources/css/memberPengajuan.css',
    'resources/js/memberPengajuan.js'
])

@section('content')
<div class="pengajuan-page">
    <div class="pengajuan-card">
        <div class="pengajuan-title">
            <h2>Daftar pengajuan peminjaman saya</h2>
        </div>

        @if(session('success'))
            <div class="pengajuan-alert success">
                <i class="bi bi-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="pengajuan-alert error">
                <i class="bi bi-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('member.pengajuan.index') }}" method="GET" class="pengajuan-filter-form" id="pengajuanStatusForm">
            <div class="pengajuan-filter-group">
                <label>Status</label>

                <div class="pengajuan-filter-select" id="statusFilterDropdown">
                    <input type="hidden" name="status" id="statusFilterInput" value="{{ $statusFilter ?? '' }}">
                    <button type="button" class="pengajuan-filter-button" id="statusFilterButton">
                        <span id="statusFilterText">
                            Semua
                        </span>

                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="pengajuan-filter-options">
                        <button type="button" data-value="">Semua</button>
                        <button type="button" data-value="menunggu">Menunggu</button>
                        <button type="button" data-value="siap">Siap</button>
                        <button type="button" data-value="disetujui">Disetujui</button>
                        <button type="button" data-value="ditolak">Ditolak</button>
                    </div>
                </div>
            </div>

            <div class="pengajuan-filter-actions">
                <a href="{{ route('member.pengajuan.index') }}" class="pengajuan-btn-refresh">
                    <i class="bi bi-arrow-clockwise"></i>
                    Refresh
                </a>
            </div>
        </form>

        <div class="pengajuan-control-row">
            <div class="pengajuan-show-control">
                <span>Show</span>

                <div class="entries-filter-select" id="entriesDropdown">
                    <button type="button" class="entries-filter-button" id="entriesButton">
                        <span id="entriesValue">10</span>
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

            <div class="pengajuan-search">
                <i class="bi bi-search"></i>
                <input type="text" id="pengajuanSearch" placeholder="Cari pengajuan..." autocomplete="off">
            </div>
        </div>

        <div class="pengajuan-table-wrapper">
            <table class="pengajuan-table">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-tanggal">Tanggal Pengajuan</th>
                        <th class="col-tanggal">Tanggal Disiapkan</th>
                        <th class="col-tanggal">Batas Pengambilan</th>
                        <th class="col-tanggal">Tanggal Diambil</th>
                        <th class="col-nama">Nama Lengkap</th>
                        <th class="col-kelas">Kelas</th>
                        <th class="col-buku">Judul Buku</th>
                        <th class="col-status">Status</th>
                        <th class="col-aksi">Aksi</th>
                    </tr>
                </thead>

                <tbody id="pengajuanTableBody">
                    @forelse($pengajuan as $item)
                        <tr class="pengajuan-row"
                            data-search="
                                {{ $item->user->name ?? '' }}
                                {{ $item->user->kelas ?? '' }}
                                {{ $item->buku->judul ?? '' }}
                                {{ $item->status }}
                            "

                            data-id="{{ $item->pengajuan_id }}"
                            data-nama="{{ $item->user->name ?? '-' }}"
                            data-kelas="{{ $item->user->kelas ?? '-' }}"

                            data-buku="{{ $item->buku->judul ?? '-' }}"
                            data-penerbit="{{ $item->buku->penerbit ?? '-' }}"
                            data-pengarang="{{ $item->buku->pengarang ?? '-' }}"

                            data-kategori="{{ $item->buku?->kategori?->nama_kategori ?? '-' }}"

                            @if($item->buku && $item->buku->sampul)
                                data-cover="{{ asset('storage/' . $item->buku->sampul) }}"
                            @else
                                data-cover=""
                            @endif

                            data-status="{{ $item->status }}"

                            data-tanggal-pengajuan="{{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y H:i') : '-' }}"
                            data-tanggal-disiapkan="{{ $item->tanggal_disiapkan ? $item->tanggal_disiapkan->format('d/m/Y H:i') : '' }}"
                            data-batas-pengambilan="{{ $item->batas_pengambilan ? $item->batas_pengambilan->format('d/m/Y H:i') : '' }}"
                            data-tanggal-diambil="{{ $item->tanggal_diambil ? $item->tanggal_diambil->format('d/m/Y H:i') : '' }}"

                            data-alasan="{{ $item->alasan_ditolak ?? '' }}"
                        >

                            <td class="text-center row-number">
                                {{ $loop->iteration }}
                            </td>

                            <td class="text-center">
                                {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y') : '-' }}
                            </td>

                            <td class="text-center">
                                {{ $item->tanggal_disiapkan ? $item->tanggal_disiapkan->format('d/m/Y') : '-' }}
                            </td>

                            <td class="text-center">
                                {{ $item->batas_pengambilan ? $item->batas_pengambilan->format('d/m/Y') : '-' }}
                            </td>

                            <td class="text-center">
                                {{ $item->tanggal_diambil ? $item->tanggal_diambil->format('d/m/Y') : '-' }}
                            </td>

                            <td class="pengajuan-name-cell">
                                {{ $item->user->name ?? '-' }}
                            </td>

                            <td class="text-center">
                                {{ $item->user->kelas ?? '-' }}
                            </td>

                            <td class="pengajuan-book-cell">
                                {{ $item->buku->judul ?? '-' }}
                            </td>

                            <td class="text-center">
                                @if($item->status === 'menunggu')
                                    <span class="pengajuan-status menunggu">
                                        Menunggu
                                    </span>
                                @elseif($item->status === 'siap')
                                    <span class="pengajuan-status siap">
                                        Siap
                                    </span>
                                @elseif($item->status === 'disetujui')
                                    <span class="pengajuan-status disetujui">
                                        Disetujui
                                    </span>
                                @elseif($item->status === 'ditolak')
                                    <span class="pengajuan-status ditolak">
                                        Ditolak
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="pengajuan-actions">
                                    <button type="button" class="pengajuan-action-btn view btn-detail-pengajuan" title="Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="pengajuan-empty-row">
                            <td colspan="10">
                                <div class="pengajuan-empty">
                                    <i class="bi bi-inbox"></i>

                                    <strong>
                                        Belum ada data pengajuan peminjaman
                                    </strong>

                                    <span>
                                        Kamu belum mengajukan peminjaman buku
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pengajuan-table-footer">
            <div class="pengajuan-info" id="pengajuanInfo">
                Belum ada data pengajuan
            </div>

            <div class="pengajuan-pagination" id="pengajuanPagination"></div>
        </div>
    </div>
</div>

{{-- DETAIL PENGAJUAN --}}
<div class="pengajuan-modal" id="pengajuanModal" aria-hidden="true">
    <div class="pengajuan-modal-overlay"></div>
    <div class="pengajuan-modal-dialog">
        <div class="pengajuan-modal-header">
            <h3>
                Detail Pengajuan Peminjaman
            </h3>

            <button type="button" class="pengajuan-modal-close" id="closePengajuanModal">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="pengajuan-modal-body">
            <div class="pengajuan-detail-layout">
                {{-- BUKU --}}
                <div class="pengajuan-book-preview">
                    <h4 class="pengajuan-book-label">
                        Buku
                    </h4>

                    <div class="pengajuan-cover-wrapper">
                        <img src="" alt="Sampul Buku" id="detailCover">
                        <div class="pengajuan-cover-placeholder" id="detailCoverPlaceholder">
                            <i class="bi bi-book"></i>
                        </div>
                    </div>

                    <h4 id="detailJudulBuku">
                        -
                    </h4>

                    <div class="pengajuan-book-meta">
                        <div>
                            <span>Penerbit</span>
                            <b>:</b>
                            <span id="detailPenerbit">-</span>
                        </div>

                        <div>
                            <span>Kategori</span>
                            <b>:</b>
                            <span id="detailKategori">-</span>
                        </div>
                    </div>

                    <span id="detailPengarang" hidden></span>
                </div>


                {{-- INFORMASI --}}
                <div class="pengajuan-detail-content">
                    <div class="pengajuan-detail-list">
                        <div class="pengajuan-detail-row">
                            <i class="bi bi-calendar3"></i>

                            <span class="pengajuan-detail-label">
                                Tanggal Pengajuan
                            </span>

                            <span class="pengajuan-detail-separator">
                                :
                            </span>

                            <span class="pengajuan-detail-value" id="detailTanggalPengajuan">
                                -
                            </span>
                        </div>

                        <div class="pengajuan-detail-row" id="rowTanggalDisiapkan">
                            <i class="bi bi-calendar3"></i>

                            <span class="pengajuan-detail-label">
                                Tanggal Disiapkan
                            </span>

                            <span class="pengajuan-detail-separator">
                                :
                            </span>

                            <span class="pengajuan-detail-value" id="detailTanggalDisiapkan">
                                -
                            </span>
                        </div>

                        <div class="pengajuan-detail-row" id="rowBatasPengambilan">
                            <i class="bi bi-calendar3"></i>

                            <span class="pengajuan-detail-label">
                                Batas Pengambilan
                            </span>

                            <span class="pengajuan-detail-separator">
                                :
                            </span>

                            <span class="pengajuan-detail-value" id="detailBatasPengambilan">
                                -
                            </span>
                        </div>

                        <div class="pengajuan-detail-row" id="rowTanggalDiambil">
                            <i class="bi bi-calendar3"></i>

                            <span class="pengajuan-detail-label">
                                Tanggal Diambil
                            </span>

                            <span class="pengajuan-detail-separator">
                                :
                            </span>

                            <span class="pengajuan-detail-value" id="detailTanggalDiambil">
                                -
                            </span>
                        </div>

                        <div class="pengajuan-detail-row">
                            <i class="bi bi-person"></i>

                            <span class="pengajuan-detail-label">
                                Nama Lengkap
                            </span>

                            <span class="pengajuan-detail-separator">
                                :
                            </span>

                            <span class="pengajuan-detail-value" id="detailNama">
                                -
                            </span>
                        </div>

                        <div class="pengajuan-detail-row">
                            <i class="bi bi-star-fill"></i>

                            <span class="pengajuan-detail-label">
                                Kelas
                            </span>

                            <span class="pengajuan-detail-separator">
                                :
                            </span>

                            <span class="pengajuan-detail-value" id="detailKelas">
                                -
                            </span>
                        </div>

                        <div class="pengajuan-detail-row">
                            <i class="bi bi-bullseye"></i>

                            <span class="pengajuan-detail-label">
                                Status
                            </span>

                            <span class="pengajuan-detail-separator">
                                :
                            </span>

                            <span class="pengajuan-detail-value">
                                <span class="pengajuan-status" id="detailStatus">
                                    -
                                </span>
                            </span>
                        </div>
                    </div>

                    {{-- MENUNGGU --}}
                    <div class="pengajuan-state-box" id="sectionMenunggu">
                        <div class="pengajuan-state-title">
                            <i class="bi bi-clock"></i>

                            <span>
                                Menunggu Konfirmasi
                            </span>
                        </div>

                        <p>
                            Pengajuan ini menunggu konfirmasi dari petugas
                        </p>
                    </div>

                    {{-- SIAP --}}
                    <div class="pengajuan-state-box" id="sectionSiap">
                        <div class="pengajuan-state-title">
                            <i class="bi bi-check-circle"></i>

                            <span>
                                Buku Siap Diambil
                            </span>
                        </div>

                        <p>
                            Pengajuan telah dikonfirmasi.<br>
                            Silahkan ambil buku di perpustakaan
                        </p>
                    </div>

                    {{-- DISETUJUI --}}
                    <div class="pengajuan-state-box" id="sectionDisetujui">
                        <div class="pengajuan-state-title">
                            <i class="bi bi-check-circle-fill"></i>

                            <span>
                                Pengajuan Disetujui
                            </span>
                        </div>

                        <p>
                            Pengajuan ini telah disetujui
                        </p>
                    </div>

                    {{-- DITOLAK --}}
                    <div class="pengajuan-state-box" id="sectionDitolak">
                        <div class="pengajuan-reject-title">
                            <i class="bi bi-exclamation-circle-fill"></i>

                            <span>
                                Alasan Ditolak
                            </span>
                        </div>

                        <div class="pengajuan-reject-result" id="detailAlasanDitolak">
                            -
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pengajuan-modal-footer">
            <button type="button" class="pengajuan-modal-btn close" id="closePengajuanModalFooter">
                Tutup
            </button>
        </div>
    </div>
</div>

@endsection
@extends('layouts.dashboard')

@section('title', 'Data Siswa')
@section('page-title', 'Data Member')

@section('content')

@vite([
    'resources/css/adminMember.css',
    'resources/js/adminMember.js'
])

<div class="member-page" data-member-type="siswa">
    <div class="member-card">
        <div class="member-title">
            <h2>Daftar seluruh siswa</h2>
        </div>

        <div class="member-main-actions">
            <a href="{{ route('admin.member.siswa.create') }}" class="member-btn member-btn-add">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Siswa</span>
            </a>

            <button type="button" class="member-btn member-btn-import">
                <i class="bi bi-file-earmark-arrow-up"></i>
                <span>Import Excel</span>
            </button>
        </div>

        @if(session('success'))
            <div class="member-alert success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="member-control-row">
            <div class="member-show-control">
                <span>Show</span>
                <input type="hidden" id="memberEntries" value="10">

                <div class="entries-filter-select">
                    <button type="button" class="entries-filter-button">
                        <span>10</span>
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

            <div class="member-search">
                <i class="bi bi-search"></i>
                <input type="text" id="memberSearch" placeholder="Cari member..." autocomplete="off">
            </div>
        </div>

        <div class="member-table-wrapper">
            <table class="member-table">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-photo">Foto</th>
                        <th>NIS</th>
                        <th>Nama Lengkap</th>
                        <th>Kelas</th>
                        <th class="col-status">Status</th>
                        <th class="col-action">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($siswa as $item)
                        <tr class="member-row"
                            data-search="{{ strtolower($item->nis . ' ' . $item->name . ' ' . $item->kelas . ' ' . $item->status) }}">

                            <td class="text-center member-number"></td>

                            <td class="text-center">
                                @if($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" class="member-photo" alt="{{ $item->name }}">
                                @else
                                    <div class="member-photo-default">
                                        {{ strtoupper(substr($item->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>

                            <td>{{ $item->nis }}</td>
                            <td class="member-name">{{ $item->name }}</td>
                            <td class="text-center">{{ $item->kelas }}</td>

                            <td class="text-center">
                                <span class="member-status {{ $item->status }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            <td>
                                <div class="member-actions">
                                    <button
                                        type="button"
                                        class="member-action-btn view member-detail"
                                        title="Detail siswa"
                                        data-name="{{ $item->name }}"
                                        data-nis="{{ $item->nis }}"
                                        data-kelas="{{ $item->kelas }}"
                                        data-role="siswa"
                                        data-status="{{ $item->status }}"
                                        data-foto="{{ $item->foto ? asset('storage/' . $item->foto) : '' }}"
                                        data-edit="{{ route('admin.member.siswa.edit', $item) }}"
                                        data-form="deletesiswa{{ $item->id }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    <a href="{{ route('admin.member.siswa.edit', $item) }}" class="member-action-btn edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <button type="button" class="member-action-btn delete member-delete" data-form="deleteSiswa{{ $item->id }}">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>

                                <form action="{{ route('admin.member.siswa.destroy', $item) }}" method="POST" id="deleteSiswa{{ $item->id }}" class="member-delete-form">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr id="memberDatabaseEmpty">
                            <td colspan="7">
                                <div class="member-empty">
                                    <i class="bi bi-people"></i>
                                    <strong>Belum ada data siswa</strong>
                                    <span>Silakan tambahkan siswa terlebih dahulu.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    @if($siswa->count() > 0)
                        <tr id="memberSearchEmpty">
                            <td colspan="7">
                                <div class="member-empty">
                                    <i class="bi bi-search"></i>
                                    <strong>Siswa tidak ditemukan</strong>
                                    <span>Coba gunakan kata kunci lainnya.</span>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="member-footer">
            <div class="member-info" id="memberInfo">Menampilkan data siswa</div>
            <div class="member-pagination" id="memberPagination"></div>
        </div>
    </div>
</div>

<div class="member-detail-modal" id="memberDetailModal">
    <div class="member-detail-box">
        <div class="member-detail-header">
            <h3>Detail Siswa</h3>

            <button type="button" class="member-detail-close" id="memberDetailClose">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="member-detail-body">
            <div class="member-detail-photo-area">
                <div class="member-detail-photo">
                    <i class="bi bi-person-fill" id="detailPhotoIcon"></i>

                    <img src="" alt="Foto Member" id="detailPhoto">
                </div>
            </div>

            <div class="member-detail-info">
                <div class="member-detail-row">
                    <div class="detail-label">
                        <i class="bi bi-person-vcard"></i>
                        <span>NIS</span>
                    </div>

                    <span class="detail-separator">:</span>
                    <span id="detailNis">-</span>
                </div>

                <div class="member-detail-row">
                    <div class="detail-label">
                        <i class="bi bi-person"></i>
                        <span>Nama Lengkap</span>
                    </div>

                    <span class="detail-separator">:</span>
                    <span id="detailName">-</span>
                </div>

                <div class="member-detail-row">
                    <div class="detail-label">
                        <i class="bi bi-stars"></i>
                        <span>Kelas</span>
                    </div>

                    <span class="detail-separator">:</span>
                    <span id="detailKelas">-</span>
                </div>

                <div class="member-detail-row">
                    <div class="detail-label">
                        <i class="bi bi-person-gear"></i>
                        <span>Role</span>
                    </div>

                    <span class="detail-separator">:</span>
                    <span class="member-detail-role" id="detailRole">
                        Siswa
                    </span>
                </div>

                <div class="member-detail-row">
                    <div class="detail-label">
                        <i class="bi bi-circle"></i>
                        <span>Status</span>
                    </div>

                    <span class="detail-separator">:</span>
                    <span class="member-status aktif" id="detailStatus">
                        Aktif
                    </span>
                </div>
            </div>
        </div>

        <div class="member-detail-footer">
            <div class="member-detail-left">
                <button type="button" class="member-detail-delete" id="detailDeleteButton">
                    Hapus
                </button>

                <a href="#" class="member-detail-edit" id="detailEditButton">
                    Edit
                </a>
            </div>

            <button type="button" class="member-detail-close-button" id="memberDetailCloseButton">
                Tutup
            </button>
        </div>
    </div>
</div>

<div class="member-delete-modal" id="memberDeleteModal">
    <div class="member-delete-box">
        <div class="member-delete-icon">
            <i class="bi bi-trash3"></i>
        </div>

        <h3>Hapus Siswa?</h3>
        <p>Data siswa yang sudah dihapus tidak dapat dikembalikan.</p>

        <div class="member-delete-actions">
            <button type="button" class="modal-cancel" id="memberCancelDelete">Batal</button>
            <button type="button" class="modal-delete" id="memberConfirmDelete">Hapus</button>
        </div>
    </div>
</div>

@endsection
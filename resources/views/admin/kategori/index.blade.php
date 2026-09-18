@extends('layouts.dashboard')

@section('title', 'Kategori Buku')
@section('page-title', 'Kategori Buku')

@section('content')

@vite([
    'resources/css/adminKategori.css',
    'resources/js/adminKategori.js'
])

<div class="kategori-page">
    <div class="kategori-card">

        <!-- {{-- TITLE --}} -->
        <div class="kategori-title">
            <h2>Daftar seluruh kategori buku</h2>
        </div>

        <!-- {{-- ACTION BUTTON --}} -->
        <div class="kategori-main-actions">
            <a href="{{ route('admin.kategori.create') }}" class="kategori-btn-add">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Kategori</span>
            </a>
        </div>

        <!-- {{-- SUCCESS --}} -->
        @if(session('success'))
            <div class="kategori-alert">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- {{-- ERROR --}} -->
        @if(session('error'))
            <div class="kategori-alert kategori-alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- {{-- CONTROL --}} -->
        <div class="kategori-control-row">

            <!-- {{-- SHOW ENTRIES --}} -->
            <div class="kategori-show-control">
                <span>Show</span>
                <input type="hidden" id="kategoriEntries" value="10">

                <div class="entries-filter-select" data-target="kategoriEntries">
                    <button type="button" class="entries-filter-button">
                        <span>10</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="entries-filter-options">
                        <button type="button" data-value="10" class="active">10</button>
                        <button type="button" data-value="25">25</button>
                        <button type="button" data-value="50">50</button>
                    </div>
                </div>

                <span>entries</span>
            </div>

            <!-- {{-- SEARCH --}} -->
            <div class="kategori-search">
                <i class="bi bi-search"></i>
                <input
                    type="text"
                    id="kategoriSearch"
                    placeholder="Cari kategori..."
                    autocomplete="off"
                >
            </div>
        </div>

        <!-- {{-- TABLE --}} -->
        <div class="kategori-table-wrapper">
            <table class="kategori-table">
                <thead>
                    <tr>
                        <th class="kategori-col-no">No</th>
                        <th>Nama Kategori</th>
                        <th class="kategori-col-action">Aksi</th>
                    </tr>
                </thead>

                <tbody id="kategoriTableBody">
                    @forelse($kategori as $item)
                        <tr class="kategori-row" data-name="{{ strtolower($item->nama_kategori) }}">
                            <td class="kategori-number">
                                {{ $loop->iteration }}
                            </td>

                            <td class="kategori-name">
                                {{ $item->nama_kategori }}
                            </td>

                            <td>
                                <div class="kategori-actions">
                                    <a href="{{ route('admin.kategori.edit', $item) }}" class="kategori-action-btn kategori-edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <button type="button" class="kategori-action-btn kategori-delete" data-form="deleteKategori{{ $item->id }}">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>

                                    <form id="deleteKategori{{ $item->id }}" action="{{ route('admin.kategori.destroy', $item) }}" method="POST" class="kategori-delete-form">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="kategoriDatabaseEmpty">
                            <td colspan="3">
                                <div class="kategori-empty">
                                    <i class="bi bi-folder2-open"></i>
                                    <strong>Belum ada kategori</strong>
                                    <span>Tambahkan kategori terlebih dahulu.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <tr id="kategoriSearchEmpty">
                        <td colspan="3">
                            <div class="kategori-empty">
                                <i class="bi bi-search"></i>
                                <strong>Kategori tidak ditemukan</strong>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- {{-- FOOTER --}} -->
        <div class="kategori-footer">
            <div class="kategori-info" id="kategoriInfo">
                Menampilkan data kategori
            </div>

            <div class="kategori-pagination" id="kategoriPagination"></div>
        </div>
    </div>
</div>

<!-- {{-- DELETE MODAL --}} -->
<div class="kategori-delete-modal" id="kategoriDeleteModal">
    <div class="kategori-delete-box">
        <div class="kategori-delete-icon">
            <i class="bi bi-trash3"></i>
        </div>

        <h3>Hapus Kategori?</h3>
        <p>Data kategori yang dihapus tidak dapat dikembalikan.</p>

        <div class="kategori-delete-actions">
            <button type="button" id="kategoriCancelDelete" class="kategori-modal-cancel">
                Batal
            </button>

            <button type="button" id="kategoriConfirmDelete" class="kategori-modal-delete">
                Hapus
            </button>
        </div>
    </div>
</div>

@endsection
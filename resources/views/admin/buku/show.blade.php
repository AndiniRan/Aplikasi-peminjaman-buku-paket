@extends('layouts.dashboard')

@section('title', 'Detail Buku')
@section('page-title', 'Detail Buku')

@section('content')

@vite([
    'resources/css/adminBuku.css',
    'resources/js/adminBuku.js'
])

<div class="buku-detail-page">
    <div class="buku-detail-card">
        <div class="buku-detail-top">
            <a href="{{ url()->previous() }}" class="buku-detail-back">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        <div class="buku-detail-content">
            <div class="buku-detail-cover">
                @if($buku->sampul)
                    <img src="{{ asset('storage/' . $buku->sampul) }}" alt="{{ $buku->judul }}">
                @else
                    <div class="buku-detail-placeholder">
                        <i class="bi bi-book"></i>
                    </div>
                @endif
            </div>

            <div class="buku-detail-info">
                <div class="buku-detail-row">
                    <span class="detail-label">Judul Buku</span>
                    <span class="detail-separator">:</span>
                    <strong>{{ $buku->judul }}</strong>
                </div>

                <div class="buku-detail-row">
                    <span class="detail-label">Kategori</span>
                    <span class="detail-separator">:</span>
                    <strong class="detail-category">
                        {{ $buku->kategori->nama_kategori }}
                    </strong>
                </div>

                <div class="buku-detail-row">
                    <span class="detail-label">Pengarang</span>
                    <span class="detail-separator">:</span>
                    <strong>{{ $buku->pengarang }}</strong>
                </div>

                <div class="buku-detail-row">
                    <span class="detail-label">Penerbit</span>
                    <span class="detail-separator">:</span>
                    <strong>{{ $buku->penerbit }}</strong>
                </div>

                <div class="buku-detail-row">
                    <span class="detail-label">Tahun Terbit</span>
                    <span class="detail-separator">:</span>
                    <strong>{{ $buku->tahun_terbit }}</strong>
                </div>

                <div class="buku-detail-row">
                    <span class="detail-label">Stok Buku</span>
                    <span class="detail-separator">:</span>

                    <div class="detail-stock">
                        <span class="detail-stock-total">
                            {{ $buku->total_stok }}
                        </span>
                        <strong>Buku</strong>
                    </div>
                </div>

                <div class="buku-detail-row">
                    <span class="detail-label">Stok Tersedia</span>
                    <span class="detail-separator">:</span>

                    <div class="detail-stock">
                        <span class="detail-stock-available">
                            {{ $buku->stok_tersedia }}
                        </span>
                        <strong>Buku</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="buku-detail-actions">
            <a href="{{ route('admin.buku.edit', $buku) }}" class="detail-btn-edit">
                <i class="bi bi-pencil-square"></i>
                <span>Edit</span>
            </a>

            <button type="button" class="detail-btn-delete buku-delete" data-form="deleteBuku{{ $buku->id }}">
                <i class="bi bi-trash-fill"></i>
                <span>Hapus</span>
            </button>

            <form action="{{ route('admin.buku.destroy', $buku) }}" method="POST" id="deleteBuku{{ $buku->id }}" class="buku-delete-form">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

<div class="buku-delete-modal" id="bukuDeleteModal">
    <div class="buku-delete-box">
        <div class="buku-delete-icon">
            <i class="bi bi-trash3"></i>
        </div>

        <h3>Hapus Buku?</h3>
        <p>Data buku yang sudah dihapus tidak dapat dikembalikan.</p>

        <div class="buku-delete-actions">
            <button type="button" class="modal-cancel" id="bukuCancelDelete">
                Batal
            </button>

            <button type="button" class="modal-delete" id="bukuConfirmDelete">
                Hapus
            </button>
        </div>
    </div>
</div>

@endsection
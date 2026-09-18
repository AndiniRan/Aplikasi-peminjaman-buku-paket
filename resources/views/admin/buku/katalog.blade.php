@extends('layouts.dashboard')

@section('title', 'Katalog Buku')
@section('page-title', 'Katalog Buku')

@section('content')

@vite([
    'resources/css/adminBuku.css',
    'resources/js/adminBuku.js'
])

<div class="katalog-page">
    <div class="katalog-toolbar">
        <div class="katalog-search">
            <i class="bi bi-search"></i>
            <input type="text" id="katalogSearch" placeholder="Cari buku berdasarkan judul, penulis, kategori..." autocomplete="off">
        </div>

        <div class="katalog-filter">
            <input type="hidden" id="katalogKategori" value="">

            <div class="katalog-filter-select" data-target="katalogKategori">
                <button type="button" class="katalog-filter-button">
                    <span>Semua Kategori</span>
                    <i class="bi bi-chevron-down"></i>
                </button>

                <div class="katalog-filter-options">
                    <button type="button" data-value="" class="active">
                        Semua Kategori
                    </button>

                    @foreach($kategori as $item)
                        <button
                            type="button"
                            data-value="{{ strtolower($item->nama_kategori) }}"
                        >
                            {{ $item->nama_kategori }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="katalog-card">
        <a href="{{ route('admin.buku.index') }}" class="katalog-back">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali</span>
        </a>

        <div class="katalog-grid" id="katalogGrid">
            @foreach($buku as $item)
                <a
                    href="{{ route('admin.buku.show', $item) }}"
                    class="katalog-item"
                    data-search="{{ strtolower(
                        $item->judul . ' ' .
                        $item->pengarang . ' ' .
                        $item->penerbit . ' ' .
                        $item->kategori->nama_kategori
                    ) }}"
                    data-category="{{ strtolower($item->kategori->nama_kategori) }}">

                    <div class="katalog-cover">
                        @if($item->sampul)
                            <img src="{{ asset('storage/' . $item->sampul) }}" alt="{{ $item->judul }}">
                        @else
                            <div class="katalog-cover-placeholder">
                                <i class="bi bi-book"></i>
                            </div>
                        @endif
                    </div>

                    <div class="katalog-item-content">
                        <h3>{{ $item->judul }}</h3>
                        <p>{{ $item->pengarang }}</p>
                        <p>{{ $item->penerbit }}</p>
                        <p>{{ $item->tahun_terbit }}</p>

                        <div class="katalog-item-stock">
                            <span>Stok tersedia:</span>
                            <strong>{{ $item->stok_tersedia }}</strong>
                        </div>

                        <div class="katalog-item-footer">
                            <span class="katalog-category">
                                {{ $item->kategori->nama_kategori }}
                            </span>

                            @if($item->stok_tersedia > 0)
                                <span class="katalog-status available">
                                    Tersedia
                                </span>
                            @else
                                <span class="katalog-status unavailable">
                                    Habis
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="katalog-empty" id="katalogEmpty">
            <i class="bi bi-search"></i>
            <strong>Buku tidak ditemukan</strong>
            <span>Coba ubah pencarian atau kategori.</span>
        </div>

        <div class="katalog-pagination" id="katalogPagination"></div>
    </div>
</div>

@endsection
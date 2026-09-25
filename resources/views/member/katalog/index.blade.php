@extends('layouts.dashboard')

@section('title', 'Katalog Buku')
@section('page-title', 'Katalog Buku')

@section('content')

@vite([
    'resources/css/memberKatalog.css',
    'resources/js/memberKatalog.js'
])

<div class="member-katalog-page">
    <div class="member-katalog-toolbar">
        <div class="member-katalog-search">
            <i class="bi bi-search"></i>

            <input type="text" id="memberKatalogSearch" placeholder="Cari buku berdasarkan judul, penulis, kategori..." autocomplete="off">
        </div>

        <div class="member-katalog-filter">
            <input type="hidden" id="memberKatalogKategori" value="">

            <div class="member-katalog-filter-select" data-target="memberKatalogKategori">
                <button type="button" class="member-katalog-filter-button">
                    <span>Semua Kategori</span>
                    <i class="bi bi-chevron-down"></i>
                </button>

                <div class="member-katalog-filter-options">
                    <button type="button" data-value="" class="active">
                        Semua Kategori
                    </button>

                    @foreach($kategori as $item)
                        <button type="button" data-value="{{ strtolower($item->nama_kategori) }}">
                            {{ $item->nama_kategori }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="member-katalog-card">
        <div class="member-katalog-grid" id="memberKatalogGrid">
            @foreach($buku as $item)
                <a
                    href="{{ route('member.katalog.show', $item) }}"
                    class="member-katalog-item"
                    data-search="{{ strtolower(
                        $item->judul . ' ' .
                        $item->pengarang . ' ' .
                        $item->penerbit . ' ' .
                        ($item->kategori->nama_kategori ?? '')
                    ) }}"
                    data-category="{{ strtolower(
                        $item->kategori->nama_kategori ?? ''
                    ) }}"
                >

                    <div class="member-katalog-cover">
                        @if($item->sampul)
                            <img src="{{ asset('storage/' . $item->sampul) }}" alt="{{ $item->judul }}">
                        @else
                            <div class="member-katalog-cover-placeholder">
                                <i class="bi bi-book"></i>
                            </div>
                        @endif
                    </div>

                    <div class="member-katalog-content">
                        <h3>
                            {{ $item->judul }}
                        </h3>

                        <p>
                            {{ $item->pengarang }}
                        </p>

                        <p>
                            {{ $item->penerbit }}
                        </p>

                        <p>
                            {{ $item->tahun_terbit }}
                        </p>

                        <div class="member-katalog-stock">
                            <span>Stok tersedia:</span>

                            <strong>
                                {{ $item->stok_tersedia }}
                            </strong>
                        </div>

                        <div class="member-katalog-footer">
                            <span class="member-katalog-category">
                                {{ $item->kategori->nama_kategori ?? '-' }}
                            </span>
                            @if($item->stok_tersedia > 0)
                                <span class="member-katalog-status available">
                                    Tersedia
                                </span>
                            @else
                                <span class="member-katalog-status unavailable">
                                    Habis
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="member-katalog-empty" id="memberKatalogEmpty">
            <i class="bi bi-search"></i>
            <strong>Buku tidak ditemukan</strong>

            <span>
                Coba ubah pencarian atau kategori.
            </span>
        </div>

        <div class="member-katalog-pagination" id="memberKatalogPagination"></div>
    </div>
</div>

@endsection
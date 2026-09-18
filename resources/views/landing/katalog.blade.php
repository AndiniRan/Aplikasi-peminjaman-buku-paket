@extends('layouts.app')

@section('content')

@vite([
    'resources/css/landingKatalog.css',
    'resources/js/landingKatalog.js'
])

<div class="landing-katalog-page">
    <div class="landing-katalog-toolbar">
        <div class="landing-katalog-search">
            <i class="bi bi-search"></i>

            <input type="text" id="landingKatalogSearch" placeholder="Cari buku berdasarkan judul, penulis, kategori..." autocomplete="off">

        </div>

        {{-- FILTER KATEGORI --}}
        <div class="landing-katalog-filter">

            <input type="hidden" id="landingKatalogKategori" value="">

            <div class="landing-katalog-filter-select">

                <button type="button" class="landing-katalog-filter-button" id="landingKatalogFilterButton"> <span>Semua Kategori</span>
                    <i class="bi bi-chevron-down"></i>
                </button>

                <div class="landing-katalog-filter-options" id="landingKatalogFilterOptions">
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

    <!-- {{-- CARD UTAMA --}} -->
    <div class="landing-katalog-card">
        <a href="{{ route('landing.landingPage') }}" class="landing-katalog-back">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali</span>
        </a>

        <!-- {{-- GRID BUKU --}} -->
        <div class="landing-katalog-grid" id="landingKatalogGrid">
            @foreach($buku as $item)

                <button type="button" class="landing-katalog-item landing-katalog-login-required"
                    data-search="{{ strtolower(
                        $item->judul . ' ' .
                        $item->pengarang . ' ' .
                        $item->penerbit . ' ' .
                        ($item->kategori?->nama_kategori ?? '')
                    ) }}"
                    data-category="{{ strtolower(
                        $item->kategori?->nama_kategori ?? ''
                    ) }}"
                >

                    <!-- {{-- COVER --}} -->
                    <div class="landing-katalog-cover">
                        @if($item->sampul)
                            <img src="{{ asset('storage/' . $item->sampul) }}" alt="{{ $item->judul }}">
                        @else
                            <div class="landing-katalog-cover-placeholder">
                                <i class="bi bi-book"></i>
                            </div>
                        @endif
                    </div>

                    <!-- {{-- INFORMASI --}} -->
                    <div class="landing-katalog-item-content">
                        <h3>{{ $item->judul }}</h3>
                        <p>{{ $item->pengarang }}</p>
                        <p>{{ $item->penerbit }}</p>
                        <p>{{ $item->tahun_terbit }}</p>

                        <div class="landing-katalog-stock">
                            <span>Stok tersedia:</span>
                            <strong>{{ $item->stok_tersedia }}</strong>
                        </div>

                        <div class="landing-katalog-item-footer">
                            <span class="landing-katalog-category">
                                {{ $item->kategori?->nama_kategori ?? '-' }}
                            </span>

                            @if($item->stok_tersedia > 0)
                                <span class="landing-katalog-status available">
                                    Tersedia
                                </span>
                            @else
                                <span class="landing-katalog-status unavailable">
                                    Habis
                                </span>
                            @endif
                        </div>
                    </div>
                </button>
            @endforeach
        </div>

        <!-- {{-- DATA KOSONG --}} -->
        <div class="landing-katalog-empty" id="landingKatalogEmpty">       
            <i class="bi bi-search"></i>
            <strong>
                Buku tidak ditemukan
            </strong>
            <span>
                Coba ubah pencarian atau kategori.
            </span>
        </div>

        <!-- {{-- PAGINATION --}} -->
        <div class="landing-katalog-pagination" id="landingKatalogPagination"></div>
    </div>
</div>

<!-- {{-- MODAL LOGIN --}} -->
<div class="landing-katalog-login-modal" id="landingKatalogLoginModal"
>
    <div class="landing-katalog-login-box">
        <div class="landing-katalog-login-icon">
            <i class="bi bi-person-lock"></i>
        </div>

        <h3>Login Diperlukan</h3>
        <p>Anda harus login terlebih dahulu untuk melihat detail buku.</p>

        <div class="landing-katalog-login-actions">
            <button type="button" class="landing-katalog-login-cancel" id="landingKatalogLoginCancel">
                Batal
            </button>

            <a href="{{ route('login') }}" class="landing-katalog-login-button">
                Login
            </a>
        </div>
    </div>
</div>

@endsection
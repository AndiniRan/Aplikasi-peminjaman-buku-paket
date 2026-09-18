@extends('layouts.app')

@section('content')

<div class="library-page">
    <section class="hero">
        <div class="library-container hero-content">
            <div class="hero-text">
                <h1>Perpustakaan <br>SMPN 69 Jakarta</h1>

                <p> Temukan buku terbaik, perluas wawasan, dan
                    <br class="desktop-only"> bangun masa depan lebih cerah bersama perpustakaan
                </p>

                <div class="hero-buttons">
                    <a href="{{ route('landing.katalog') }}" class="hero-button">
                        <i class="bi bi-journals"></i>
                        Katalog buku
                    </a>
                    <a href="#kontak" class="hero-button">
                        <i class="bi bi-person"></i>
                        Kontak kami
                    </a>
                </div>
            </div>

            <div class="hero-image">
                <img src="{{ asset('images/hero-books.png') }}" alt="Tumpukan buku">
            </div>
        </div>
    </section>

    <div class="library-container search-wrapper">
        <div class="search-box">
            <i class="bi bi-search"></i>

            <inpu type="text" id="landingBookSearch" placeholder="Cari buku berdasarkan judul, penulis, kategori..." autocomplete="off">
        </div>
    </div>

    <section class="features">
        <div class="library-container">
            <div class="row g-3 justify-content-center">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon blue">
                            <i class="bi bi-journals"></i>
                        </div>

                        <div>
                            <h5>Koleksi Lengkap</h5>
                            <p>Ratusan buku pelajaran, fiksi, dan referensisiap untuk dibaca.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon orange">
                            <i class="bi bi-mortarboard"></i>
                        </div>

                        <div>
                            <h5>Mendukung Belajar</h5>
                            <p>Sumber ilmu yang membantu prestasi dan kreativitas.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon green">
                            <i class="bi bi-clock-history"></i>
                        </div>

                        <div>
                            <h5>Akses Mudah</h5>
                            <p>Cek koleksi dan informasi kapan saja melalui website.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="katalog" class="catalog-section">
        <div class="library-container">
            <div class="catalog-title">
                <a href="{{ route('landing.katalog') }}">
                    Katalog Buku
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="row g-3" id="landingBookList">
                @forelse ($books as $book)

                    <div
                        class="col-lg-3 col-md-6 landing-book-item"
                        data-title="{{ strtolower($book->judul) }}"
                        data-author="{{ strtolower($book->pengarang) }}"
                        data-category="{{ strtolower($book->kategori?->nama_kategori ?? '') }}"
                    >

                        <button type="button" class="book-card landing-login-required">
                            @if($book->sampul)
                                <img src="{{ asset('storage/' . $book->sampul) }}" alt="{{ $book->judul }}">
                            @else
                                <div class="book-cover-placeholder">
                                    <i class="bi bi-book"></i>
                                </div>
                            @endif

                            <div class="book-info">
                                <h6>{{ $book->judul }}</h6>
                                <p>{{ $book->pengarang }}</p>
                                <span>{{ $book->penerbit }}</span>
                                <small>{{ $book->tahun_terbit }}</small>
                                <span class="category">
                                    {{ $book->kategori?->nama_kategori ?? '-' }}
                                </span>
                                <div class="book-status {{ $book->stok_tersedia > 0 ? 'available' : 'unavailable' }}">
                                    {{ $book->stok_tersedia > 0 ? 'Tersedia' : 'Dipinjam' }}
                                </div>
                            </div>
                        </button>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center">Belum ada buku yang tersedia.</p>
                    </div>
                @endforelse
            </div>

            <div id="landingBookEmpty" class="text-center" style="display: none; padding: 20px 0;">
                Buku tidak ditemukan.
            </div>
        </div>
    </section>

    <section id="cara-peminjaman" class="contact-section">
        <div class="library-container text-center">
            <h2>Cara Peminjaman Buku Paket</h2>
            <p>Pilih buku paket yang dibutuhkan, ajukan peminjaman, lalu ambil buku sesuai proses yang berlaku di perpustakaan.</p>
        </div>
    </section>

    <footer class="library-footer">
        <div class="library-container">
            <div class="footer-content">
                <div class="footer-column footer-about">
                    <div class="footer-title">
                        <i class="bi bi-book-half"></i>
                        <strong>Perpustakaan</strong>
                    </div>
                    <p>Website Perpustakaan SMPN 69 Jakarta yang digunakan untuk membantu pengelolaan dan peminjaman buku secara lebih mudah dan terstruktur.</p>
                </div>

                <div class="footer-column footer-navigation">
                    <h6>Navigasi</h6>
                    <a href="{{ url('/') }}">
                        Beranda
                    </a>
                    <a href="{{ route('landing.katalog') }}">
                        Katalog Buku
                    </a>
                    <a href="#cara-peminjaman">
                        Tentang Kami
                    </a>
                </div>

                <div class="footer-column">
                    <h6>
                        Informasi
                    </h6>
                    <span>
                        <i class="bi bi-building"></i>
                        SMPN 69 Jakarta
                    </span>
                    <span>
                        <i class="bi bi-geo-alt"></i>
                        Jakarta, Indonesia
                    </span>
                    <span>
                        <i class="bi bi-book"></i>
                        Layanan Perpustakaan
                    </span>
                </div>
            </div>

            <div class="footer-bottom">
                <span>
                    © {{ date('Y') }} Perpustakaan
                    <br>
                    SMPN 69 Jakarta
                </span>
            </div>
        </div>
    </footer>
</div>

<div class="public-login-modal" id="publicLoginModal">
    <div class="public-login-modal-box">
        <div class="public-login-modal-icon">
            <i class="bi bi-person-lock"></i>
        </div>

        <h3>Login Diperlukan</h3>
        <p>Anda harus login terlebih dahulu untuk melihat detail buku.</p>

        <div class="public-login-modal-actions">
            <button type="button" class="public-login-modal-cancel" id="publicLoginModalCancel">
                Batal
            </button>

            <a href="{{ route('login') }}" class="public-login-modal-login">
                Login
            </a>
        </div>
    </div>
</div>

@endsection
@extends('layouts.dashboard')

@section('title', 'Detail Buku')
@section('page-title', 'Detail Buku')

@section('content')

@vite([
    'resources/css/memberKatalog.css',
    'resources/js/memberKatalog.js'
])

<div class="member-book-detail-page">
    <div class="member-book-detail-card">
        <div class="member-book-detail-top">
            <a href="{{ route('member.katalog.index') }}" class="member-book-back">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        <div class="member-book-detail-content">
            <div class="member-book-detail-cover">
                @if($buku->sampul)
                    <img src="{{ asset('storage/' . $buku->sampul) }}" alt="{{ $buku->judul }}">
                @else
                    <div class="member-book-detail-placeholder">
                        <i class="bi bi-book"></i>
                    </div>
                @endif

            </div>

            <div class="member-book-detail-info">
                <div class="member-book-info-row">
                    <span class="member-book-label">
                        Judul Buku
                    </span>

                    <span class="member-book-separator">:</span>

                    <strong>
                        {{ $buku->judul }}
                    </strong>
                </div>

                <div class="member-book-info-row">
                    <span class="member-book-label">
                        Kategori
                    </span>

                    <span class="member-book-separator">:</span>

                    <strong class="member-book-category">
                        {{ $buku->kategori->nama_kategori ?? '-' }}
                    </strong>
                </div>

                <div class="member-book-info-row">
                    <span class="member-book-label">
                        Pengarang
                    </span>

                    <span class="member-book-separator">:</span>

                    <strong>
                        {{ $buku->pengarang }}
                    </strong>
                </div>

                <div class="member-book-info-row">
                    <span class="member-book-label">
                        Penerbit
                    </span>

                    <span class="member-book-separator">:</span>

                    <strong>
                        {{ $buku->penerbit }}
                    </strong>
                </div>

                <div class="member-book-info-row">
                    <span class="member-book-label">
                        Tahun Terbit
                    </span>

                    <span class="member-book-separator">:</span>

                    <strong>
                        {{ $buku->tahun_terbit }}
                    </strong>
                </div>

                <div class="member-book-info-row">
                    <span class="member-book-label">
                        Stok Buku
                    </span>

                    <span class="member-book-separator">:</span>

                    <div class="member-book-stock">
                        <span class="member-stock-total">
                            {{ $buku->total_stok }}
                        </span>

                        <strong>Buku</strong>
                    </div>
                </div>

                <div class="member-book-info-row">
                    <span class="member-book-label">
                        Stok Tersedia
                    </span>

                    <span class="member-book-separator">:</span>

                    <div class="member-book-stock">
                        <span class="member-stock-available {{ $buku->stok_tersedia <= 0 ? 'empty' : '' }}">
                            {{ $buku->stok_tersedia }}
                        </span>

                        <strong>Buku</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="member-book-detail-bottom">
            <div class="member-book-status-card">
                <h3>Status Buku</h3>

                @if($buku->stok_tersedia > 0)
                    <div class="member-status-box available">
                        <div class="member-status-title">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Tersedia</span>
                        </div>

                        <p>Buku ini tersedia dan dapat dipinjam</p>
                    </div>
                @else
                    <div class="member-status-box unavailable">
                        <div class="member-status-title">
                            <i class="bi bi-x-circle-fill"></i>
                            <span>Tidak Tersedia</span>
                        </div>

                        <p>
                            Stok buku sedang habis
                        </p>
                    </div>
                @endif
            </div>

            <div class="member-book-status-card">
                <h3>Status Peminjaman Saya</h3>
                <div class="member-status-box loan">
                    <div class="member-status-title">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Belum Dipinjam</span>
                    </div>

                    <p>Anda belum meminjam buku ini</p>
                </div>
            </div>

            <div class="member-book-action">
                @if($buku->stok_tersedia > 0)
                    <a href="{{ route('member.katalog.pengajuan', $buku) }}" class="member-book-submit">
                        Ajukan Peminjaman
                    </a>
                @else
                    <button type="button" class="member-book-submit disabled" disabled>
                        Stok Habis
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
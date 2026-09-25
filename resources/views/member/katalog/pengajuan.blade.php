@extends('layouts.dashboard')

@section('title', 'Ajukan Peminjaman')
@section('page-title', 'Ajukan Peminjaman')

@section('content')

@vite([
    'resources/css/memberKatalog.css',
    'resources/js/memberKatalog.js'
])

<div class="member-detail-page">
    <div class="member-detail-card">
        <div class="member-detail-header">
            <h2>Konfirmasi Pengajuan Peminjaman</h2>

            <p>Pastikan data peminjaman sudah benar sebelum diajukan.</p>
        </div>

        <div class="member-detail-form">
            <div class="member-detail-row">
                <label>Nama Member</label>

                <div class="member-detail-field">
                    <span>
                        {{ auth()->user()->name }}
                    </span>

                    <small>(otomatis)</small>
                </div>
            </div>

            <div class="member-detail-row">
                <label>Kelas</label>
                <div class="member-detail-field">
                    <span>
                        {{ auth()->user()->kelas ?? '-' }}
                    </span>

                    <small>(otomatis)</small>
                </div>
            </div>

            <div class="member-detail-row">
                <label>Judul Buku</label>
                <div class="member-detail-field">
                    <span>
                        {{ $buku->judul }}
                    </span>

                    <small>(otomatis)</small>
                </div>
            </div>

            <div class="member-detail-row">
                <label>Kategori</label>

                <div class="member-detail-field">
                    <span>
                        {{ $buku->kategori->nama_kategori ?? '-' }}
                    </span>

                    <small>(otomatis)</small>
                </div>
            </div>

            <div class="member-detail-row member-detail-gap">
                <label>Stok Tersedia</label>

                <div class="member-detail-field readonly">
                    <span>
                        {{ $buku->stok_tersedia }}
                    </span>

                    <small>(readonly)</small>
                </div>
            </div>

            <div class="member-detail-row">
                <label>Tanggal Pengajuan</label>

                <div class="member-detail-field">
                    <span>
                        {{ now()->format('d-m-Y') }}
                    </span>

                    <small>(otomatis)</small>
                </div>
            </div>

            <div class="member-detail-row">
                <label>Status</label>

                <div class="member-detail-field readonly">
                    <span>Menunggu</span>

                    <small>(readonly)</small>
                </div>
            </div>
        </div>

        <div class="member-detail-actions">
            <a href="{{ route('member.katalog.show', $buku) }}" class="member-detail-cancel">
                Batal
            </a>

            <button type="button" class="member-detail-submit">
                Ajukan
            </button>
        </div>
    </div>
</div>

@endsection
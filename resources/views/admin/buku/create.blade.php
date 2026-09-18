@extends('layouts.dashboard')

@section('title', 'Tambah Buku')
@section('page-title', 'Tambah Buku')

@section('content')

@vite([
    'resources/css/adminBuku.css',
    'resources/js/adminBuku.js'
])

@php
    $oldKategoriId = old('kategori_id');
    $oldKategori = $kategori->firstWhere('id', (int) $oldKategoriId);
@endphp

<div class="buku-form-page">
    <div class="buku-form-card">
        <div class="buku-form-header">
            <h2>Tambah Buku</h2>
            <p>Silakan isi data buku pada form di bawah ini</p>
        </div>

        <form action="{{ route('admin.buku.store') }}" method="POST" enctype="multipart/form-data" class="buku-form">
            @csrf

            <div class="buku-form-row">
                <label for="sampul">Cover</label>

                <div class="buku-field">
                    <div class="buku-file">
                        <label for="sampul" class="buku-file-button">Pilih File</label>
                        <span id="fileName" class="buku-file-name">Tidak ada file yang dipilih</span>
                        <input type="file" id="sampul" name="sampul" accept=".jpg,.jpeg,.png,.webp">
                    </div>

                    <small>Format: JPG, PNG, JPEG, WEBP. Maks. 5MB</small>

                    @error('sampul')
                        <div class="buku-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="buku-form-row">
                <label for="judul">Judul</label>

                <div class="buku-field">
                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}" placeholder="Masukan judul buku">
                    @error('judul')
                        <div class="buku-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="buku-form-row">
                <label for="kategori_id">Kategori</label>

                <div class="buku-field">
                    <input type="hidden" id="kategori_id" name="kategori_id" value="{{ old('kategori_id') }}">

                    <div class="form-filter-select" data-target="kategori_id">
                        <button type="button" class="form-filter-button">
                            <span>
                                {{ $oldKategori ? $oldKategori->nama_kategori : '-- Pilih kategori --' }}
                            </span>
                            <i class="bi bi-chevron-down"></i>
                        </button>

                        <div class="form-filter-options">
                            <button type="button" data-value="" class="{{ old('kategori_id') ? '' : 'active' }}">
                                -- Pilih kategori --
                            </button>

                            @foreach($kategori as $item)
                                <button type="button" data-value="{{ $item->id }}" class="{{ old('kategori_id') == $item->id ? 'active' : '' }}">
                                    {{ $item->nama_kategori }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @error('kategori_id')
                        <div class="buku-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="buku-form-row">
                <label for="penerbit">Penerbit</label>

                <div class="buku-field">
                    <input type="text" id="penerbit" name="penerbit" value="{{ old('penerbit') }}" placeholder="Masukan penerbit">
                    @error('penerbit')
                        <div class="buku-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="buku-form-row">
                <label for="pengarang">Pengarang</label>

                <div class="buku-field">
                    <input type="text" id="pengarang" name="pengarang" value="{{ old('pengarang') }}" placeholder="Masukan pengarang">
                    @error('pengarang')
                        <div class="buku-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="buku-form-row">
                <label for="tahun_terbit">Tahun Terbit</label>

                <div class="buku-field">
                    <input type="number" id="tahun_terbit" name="tahun_terbit" value="{{ old('tahun_terbit') }}" placeholder="Masukan tahun terbit" min="1900" max="{{ date('Y') + 1 }}">
                    @error('tahun_terbit')
                        <div class="buku-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="buku-form-row">
                <label for="total_stok">Total Stok</label>

                <div class="buku-field">
                    <input type="number" id="total_stok" name="total_stok" value="{{ old('total_stok') }}" placeholder="Masukan total stok" min="0">
                    @error('total_stok')
                        <div class="buku-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="buku-form-actions">
                <a href="{{ route('admin.buku.index') }}" class="buku-form-cancel">
                    Batal
                </a>

                <button type="submit" class="buku-form-save">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
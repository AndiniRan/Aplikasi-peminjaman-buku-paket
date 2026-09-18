@extends('layouts.dashboard')

@section('title', 'Edit Buku')
@section('page-title', 'Edit Buku')

@section('content')

@vite([
    'resources/css/adminBuku.css',
    'resources/js/adminBuku.js'
])

@php
    $selectedKategoriId = old('kategori_id', $buku->kategori_id);
    $selectedKategori = $kategori->firstWhere('id', (int) $selectedKategoriId);
@endphp

<div class="buku-form-page">
    <div class="buku-form-card">
        <div class="buku-form-header">
            <h2>Edit Buku</h2>
            <p>Silakan ubah data buku pada form di bawah ini</p>
        </div>

        <form action="{{ route('admin.buku.update', $buku) }}" method="POST" enctype="multipart/form-data" class="buku-form">
            @csrf
            @method('PUT')

            <!-- {{-- COVER --}} -->
            <div class="buku-form-row">
                <label for="sampul">Cover</label>

                <div class="buku-field">
                    @if($buku->sampul)
                        <div class="buku-current-cover">
                            <img src="{{ asset('storage/' . $buku->sampul) }}" alt="{{ $buku->judul }}">

                            <div class="buku-current-cover-info">
                                <span>Cover saat ini</span>
                                <small>Pilih file baru jika ingin mengganti cover.</small>
                            </div>
                        </div>
                    @endif

                    <div class="buku-file {{ $buku->sampul ? 'has-current-cover' : '' }}">
                        <label for="sampul" class="buku-file-button">
                            Pilih File
                        </label>

                        <span id="fileName" class="buku-file-name">
                            Tidak ada file baru yang dipilih
                        </span>

                        <input type="file" id="sampul" name="sampul" accept=".jpg,.jpeg,.png,.webp">
                    </div>

                    <small>Format: JPG, PNG, JPEG, WEBP. Maks. 5MB</small>

                    @error('sampul')
                        <div class="buku-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- {{-- JUDUL --}} -->
            <div class="buku-form-row">
                <label for="judul">Judul</label>

                <div class="buku-field">
                    <input type="text" id="judul" name="judul" value="{{ old('judul', $buku->judul) }}" placeholder="Masukan judul buku">

                    @error('judul')
                        <div class="buku-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- {{-- KATEGORI --}} -->
            <div class="buku-form-row">
                <label for="kategori_id">Kategori</label>

                <div class="buku-field">
                    <input type="hidden" id="kategori_id" name="kategori_id" value="{{ $selectedKategoriId }}">

                    <div class="form-filter-select" data-target="kategori_id">
                        <button type="button" class="form-filter-button">
                            <span>
                                {{ $selectedKategori ? $selectedKategori->nama_kategori : '-- Pilih kategori --' }}
                            </span>

                            <i class="bi bi-chevron-down"></i>
                        </button>

                        <div class="form-filter-options">
                            <button type="button" data-value="" class="{{ $selectedKategoriId ? '' : 'active' }}">
                                -- Pilih kategori --
                            </button>

                            @foreach($kategori as $item)
                                <button type="button" data-value="{{ $item->id }}" class="{{ $selectedKategoriId == $item->id ? 'active' : '' }}">
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

            <!-- {{-- PENERBIT --}} -->
            <div class="buku-form-row">
                <label for="penerbit">Penerbit</label>

                <div class="buku-field">
                    <input type="text" id="penerbit" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}" placeholder="Masukan penerbit">                   

                    @error('penerbit')
                        <div class="buku-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- {{-- PENGARANG --}} -->
            <div class="buku-form-row">
                <label for="pengarang">Pengarang</label>

                <div class="buku-field">
                    <input type="text" id="pengarang" name="pengarang" value="{{ old('pengarang', $buku->pengarang) }}" placeholder="Masukan pengarang">                   

                    @error('pengarang')
                        <div class="buku-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- {{-- TAHUN TERBIT --}} -->
            <div class="buku-form-row">
                <label for="tahun_terbit">Tahun Terbit</label>

                <div class="buku-field">
                    <input type="number" id="tahun_terbit" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" placeholder="Masukan tahun terbit" min="1900" max="{{ date('Y') + 1 }}">

                    @error('tahun_terbit')
                        <div class="buku-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- {{-- TOTAL STOK --}} -->
            <div class="buku-form-row">
                <label for="total_stok">Total Stok</label>

                <div class="buku-field">
                    <input type="number" id="total_stok" name="total_stok" value="{{ old('total_stok', $buku->total_stok) }}" placeholder="Masukan total stok" min="0">

                    <small>Stok tersedia saat ini: {{ $buku->stok_tersedia }}</small>

                    @error('total_stok')
                        <div class="buku-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- {{-- ACTION --}} -->
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
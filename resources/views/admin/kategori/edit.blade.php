@extends('layouts.dashboard')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('content')

@vite([
    'resources/css/adminKategori.css',
])

<div class="kategori-form-page">
    <div class="kategori-form-card">
        <div class="kategori-form-header">
            <h2>Edit Kategori</h2>
            <p>Silakan ubah data kategori</p>
        </div>

        <form action="{{ route('admin.kategori.update', $kategori) }}" method="POST" class="kategori-form">
            @csrf
            @method('PUT')
            <div class="kategori-form-field">
                <label for="nama_kategori">
                    Kategori
                </label>

                <div class="kategori-form-input-wrapper">
                    <input type="text" id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" placeholder="Masukan nama kategori" class="{{ $errors->has('nama_kategori') ? 'input-error' : '' }}" autocomplete="off">

                    @error('nama_kategori')
                        <div class="kategori-form-error">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="kategori-form-actions">
                <a href="{{ route('admin.kategori.index') }}" class="kategori-form-cancel">
                    Batal
                </a>

                <button type="submit" class="kategori-form-save">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
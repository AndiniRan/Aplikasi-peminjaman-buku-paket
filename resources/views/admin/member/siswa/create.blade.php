@extends('layouts.dashboard')

@section('title', 'Tambah Siswa')
@section('page-title', 'Tambah Siswa')

@section('content')

@vite([
    'resources/css/adminMember.css',
    'resources/js/adminMember.js'
])

<div class="member-form-page" data-member-type="siswa">
    <div class="member-form-card">
        <div class="member-form-header">
            <h2>Tambah Siswa</h2>
            <p>Silakan isi data siswa pada form di bawah ini</p>
        </div>

        <form action="{{ route('admin.member.siswa.store') }}" method="POST" enctype="multipart/form-data" class="member-form">
            @csrf
            <div class="member-form-row">
                <label>Foto Profil</label>
                <div class="member-field">
                    <div class="member-photo-upload">
                        <div class="member-photo-preview">
                            <i class="bi bi-person"></i>
                            <img src="" alt="" id="photoPreview">
                        </div>

                        <label for="foto" class="member-photo-button">
                            <i class="bi bi-camera"></i>
                            Pilih Foto
                        </label>

                        <input type="file" name="foto" id="foto" accept=".jpg,.jpeg,.png,.webp" hidden>
                    </div>

                    @error('foto')
                        <div class="member-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label>NIS</label>
                <div class="member-field">
                    <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Masukkan NIS">

                    @error('nis')
                        <div class="member-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label>Nama Lengkap</label>
                <div class="member-field">
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap">

                    @error('name')
                        <div class="member-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label>Kelas</label>
                <div class="member-field">
                    <input type="text" name="kelas" value="{{ old('kelas') }}" placeholder="Masukkan Kelas">

                    @error('kelas')
                        <div class="member-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label>Password</label>
                <div class="member-field password-field">
                    <input type="password" name="password" id="password" placeholder="Masukkan Password">

                    <button type="button" class="password-toggle" data-target="password">
                        <i class="bi bi-eye-slash"></i>
                    </button>

                    @error('password')
                        <div class="member-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label>Konfirmasi Password</label>
                <div class="member-field password-field">
                    <input type="password" name="password_confirmation" id="passwordConfirmation" placeholder="Konfirmasi Password">

                    <button type="button" class="password-toggle" data-target="passwordConfirmation">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
            </div>

            <div class="member-form-actions">
                <a href="{{ route('admin.member.siswa.index') }}" class="member-form-cancel">
                    Batal
                </a>

                <button type="submit" class="member-form-save">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
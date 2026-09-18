@extends('layouts.dashboard')

@section('title', 'Edit Guru')
@section('page-title', 'Edit Guru')

@section('content')

@vite([
    'resources/css/adminMember.css',
    'resources/js/adminMember.js'
])

<div class="member-form-page" data-member-type="guru">
    <div class="member-form-card">
        <div class="member-form-header">
            <h2>Edit Guru</h2>
            <p>Ubah data guru pada form di bawah ini</p>
        </div>

        <form action="{{ route('admin.member.guru.update', $guru) }}" method="POST" enctype="multipart/form-data" class="member-form">
            @csrf
            @method('PUT')

            <div class="member-form-row">
                <label for="foto">Foto Profil</label>
                <div class="member-field">
                    <div class="member-photo-upload">
                        <div class="member-photo-preview">
                            <i class="bi bi-person"></i>
                            @if($guru->foto)
                                <img src="{{ asset('storage/' . $guru->foto) }}" alt="{{ $guru->name }}" id="photoPreview" class="show">
                            @else
                                <img src="" alt="Foto profil" id="photoPreview">
                            @endif
                        </div>

                        <div class="member-photo-input">
                            <label for="foto" class="member-photo-button">
                                <i class="bi bi-camera"></i>
                                <span>Ganti Foto</span>
                            </label>

                            <span class="member-photo-name" id="photoName">
                                Tidak ada file baru
                            </span>
                        </div>

                        <input type="file" name="foto" id="foto" accept=".jpg,.jpeg,.png,.webp" hidden>
                    </div>

                    <small class="member-field-help">
                        Kosongkan jika foto tidak ingin diganti.
                    </small>
                    @error('foto')
                        <div class="member-form-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label for="email">Email</label>
                <div class="member-field">
                    <input type="email" name="email" id="email" value="{{ old('email', $guru->email) }}" placeholder="Masukkan email">
                    @error('email')
                        <div class="member-form-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label for="name">Nama Lengkap</label>
                <div class="member-field">
                    <input type="text" name="name" id="name" value="{{ old('name', $guru->name) }}" placeholder="Masukkan nama lengkap">
                    @error('name')
                        <div class="member-form-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label>Status</label>
                <div class="member-field">
                    <input type="hidden" name="status" id="memberStatus" value="{{ old('status', $guru->status) }}">

                    <div class="form-filter-select" data-target="memberStatus">
                        <button type="button" class="form-filter-button">
                            <span>
                                {{ ucfirst(old('status', $guru->status)) }}
                            </span>

                            <i class="bi bi-chevron-down"></i>
                        </button>

                        <div class="form-filter-options">
                            <button type="button" data-value="aktif" class="{{ old('status', $guru->status) === 'aktif' ? 'active' : '' }}">
                                Aktif
                            </button>

                            <button type="button" data-value="nonaktif" class="{{ old('status', $guru->status) === 'nonaktif' ? 'active' : '' }}">
                                Nonaktif
                            </button>
                        </div>
                    </div>

                    @error('status')
                        <div class="member-form-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label for="password">Password Baru</label>
                <div class="member-field password-field">
                    <input type="password" name="password" id="password" placeholder="Masukkan password baru">
                    
                    <button type="button" class="password-toggle" data-target="password">
                        <i class="bi bi-eye-slash"></i>
                    </button>

                    <small class="member-field-help">
                        Kosongkan jika password tidak ingin diganti.
                    </small>
                    @error('password')
                        <div class="member-form-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label for="passwordConfirmation">
                    Konfirmasi Password
                </label>

                <div class="member-field password-field">
                    <input type="password" name="password_confirmation" id="passwordConfirmation" placeholder="Konfirmasi password baru">

                    <button type="button" class="password-toggle" data-target="passwordConfirmation">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
            </div>

            <div class="member-form-actions">
                <a href="{{ route('admin.member.guru.index') }}" class="member-form-cancel">
                    Batal
                </a>

                <button type="submit" class="member-form-save">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
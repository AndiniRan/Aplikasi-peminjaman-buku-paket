@extends('layouts.dashboard')

@section('title', 'Profil')
@section('page-title', 'Profil')

@vite([
    'resources/css/adminProfile.css',
    'resources/js/adminProfile.js'
])

@section('content')

<div class="profile-page">
    @if(session('success'))
        <div class="profile-alert success">
            <i class="bi bi-check-circle-fill"></i>

            <span>{{ session('success') }}</span>

            <button type="button" class="profile-alert-close" aria-label="Tutup">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="profile-alert error">
            <i class="bi bi-exclamation-circle-fill"></i>

            <div>
                <strong>Profil belum berhasil diperbarui.</strong>
                <span>{{ $errors->first() }}</span>
            </div>

            <button type="button" class="profile-alert-close" aria-label="Tutup">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    <!-- INFORMASI PROFIL -->
    <div class="profile-card profile-information-card">
        <div class="profile-information">
            <div class="profile-avatar-wrapper">
                @if($user->foto)
                    <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->name }}" class="profile-avatar">
                @else
                    <div class="profile-avatar-default">
                        <i class="bi bi-person-fill"></i>
                    </div>
                @endif
            </div>

            <div class="profile-detail">
                <div class="profile-name-row">
                    <h2>Admin Perpustakaan</h2>

                    <span class="profile-role-badge">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>

                <div class="profile-detail-list">
                    <div class="profile-detail-item">
                        <div class="profile-detail-label">
                            <i class="bi bi-person"></i>
                            <span>Username</span>
                        </div>

                        <span class="profile-detail-separator">:</span>

                        <span class="profile-detail-value">
                            {{ $user->name }}
                        </span>
                    </div>

                    <div class="profile-detail-item">
                        <div class="profile-detail-label">
                            <i class="bi bi-person-lock"></i>
                            <span>Email</span>
                        </div>

                        <span class="profile-detail-separator">:</span>

                        <span class="profile-detail-value">
                            {{ $user->email }}
                        </span>
                    </div>

                    <div class="profile-detail-item">
                        <div class="profile-detail-label">
                            <i class="bi bi-globe2"></i>
                            <span>Role</span>
                        </div>

                        <span class="profile-detail-separator">:</span>

                        <span class="profile-detail-value role">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>

                    <div class="profile-detail-item">
                        <div class="profile-detail-label">
                            <i class="bi bi-stopwatch"></i>
                            <span>Status Akun</span>
                        </div>

                        <span class="profile-detail-separator">:</span>

                        <span class="profile-account-status {{ $user->status }}">
                            <span class="profile-status-dot"></span>
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT PROFIL -->
    <div class="profile-card profile-edit-card">
        <div class="profile-edit-title">
            <h2>Edit Profil</h2>
        </div>

        <div class="profile-info-message">
            <i class="bi bi-info-circle"></i>

            <span>
                Anda dapat mengubah informasi akun pada akun form di bawah ini
            </span>
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
            @csrf
            @method('PUT')

            <!-- FOTO -->
            <div class="profile-photo-section">
                <h3>Foto Profil</h3>

                <div class="profile-photo-content">
                    <div class="profile-photo-preview-wrapper">
                        @if($user->foto)
                            <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->name }}" class="profile-photo-preview" id="profilePhotoPreview">

                            <div class="profile-photo-default" id="profilePhotoDefault" hidden>
                                <i class="bi bi-person-fill"></i>
                            </div>
                        @else
                            <img src="" alt="Preview Foto" class="profile-photo-preview" id="profilePhotoPreview" hidden>

                            <div class="profile-photo-default" id="profilePhotoDefault">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        @endif

                        <div class="profile-photo-camera">
                            <i class="bi bi-camera-fill"></i>
                        </div>
                    </div>

                    <p class="profile-photo-help">
                        Format JPG, PNG. Maks. 10MB
                    </p>

                    <input type="file" name="foto" id="profilePhotoInput" accept=".jpg,.jpeg,.png" hidden>

                    <button type="button" class="profile-change-photo" id="profileChangePhoto">
                        <i class="bi bi-camera"></i>
                        Ubah Foto
                    </button>

                    @error('foto')
                        <span class="profile-field-error profile-photo-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            <div class="profile-form-divider"></div>

            <!-- FORM -->
            <div class="profile-fields">
                <div class="profile-field">
                    <label for="profileName">
                        Username
                    </label>

                    <div class="profile-input-wrapper">
                        <input type="text" name="name" id="profileName" value="{{ old('name', $user->name) }}" placeholder="Masukan Username" required>
                    </div>

                    @error('name')
                        <span class="profile-field-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="profile-field">
                    <label for="profileEmail">
                        Email
                    </label>

                    <div class="profile-input-wrapper">
                        <input type="email" name="email" id="profileEmail" value="{{ old('email', $user->email) }}" placeholder="Masukan Email" required>
                    </div>

                    @error('email')
                        <span class="profile-field-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="profile-field">
                    <label for="profilePassword">
                        Password Baru
                    </label>

                    <div class="profile-input-wrapper">
                        <input type="password" name="password" id="profilePassword" placeholder="Masukan Password Baru" autocomplete="new-password">

                        <button type="button" class="profile-password-toggle" id="profilePasswordToggle" aria-label="Tampilkan password">
                            <i class="bi bi-eye-slash-fill"></i>
                        </button>
                    </div>

                    @error('password')
                        <span class="profile-field-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="profile-submit-wrapper">
                    <button type="submit" class="profile-submit-button">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@extends('layouts.dashboard')

@section('title', 'Profil')
@section('page-title', 'Profil')

@vite([
    'resources/css/memberProfile.css',
    'resources/js/memberProfile.js'
])

@section('content')

<div class="profile-page">
    <!-- {{-- ALERT SUCCESS --}} -->
    @if(session('success'))
        <div class="profile-alert success">
            <i class="bi bi-check-circle-fill"></i>

            <span>
                {{ session('success') }}
            </span>

            <button type="button" class="profile-alert-close" aria-label="Tutup">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    <!-- {{-- ALERT ERROR --}} -->
    @if($errors->any())
        <div class="profile-alert error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <div>
                <strong>
                    Profil belum berhasil diperbarui.
                </strong>

                <span>
                    {{ $errors->first() }}
                </span>
            </div>

            <button type="button" class="profile-alert-close" aria-label="Tutup">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    <!-- {{-- INFORMASI PROFIL --}} -->
    <div class="profile-card profile-information-card">
        <div class="profile-information">
            <!-- {{-- AVATAR --}} -->
            <div class="profile-avatar-wrapper">
                @if($user->foto)
                    <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->name }}" class="profile-avatar">
                @else
                    <div class="profile-avatar-default">
                        <i class="bi bi-person-fill"></i>
                    </div>
                @endif
            </div>

            <!-- {{-- DETAIL USER --}} -->
            <div class="profile-detail">
                <div class="profile-name-row">
                    <h2>
                        {{ $user->name }}
                    </h2>

                    <span class="profile-role-badge">
                        Member
                    </span>
                </div>

                <div class="profile-detail-list">
                    <!-- {{-- NIS --}} -->
                    <div class="profile-detail-item">
                        <div class="profile-detail-label">
                            <i class="bi bi-person-vcard"></i>
                            <span>NIS</span>
                        </div>

                        <span class="profile-detail-separator">
                            :
                        </span>

                        <span class="profile-detail-value">
                            {{ $user->nis ?? '-' }}
                        </span>
                    </div>

                    <!-- {{-- NAMA --}} -->
                    <div class="profile-detail-item">
                        <div class="profile-detail-label">
                            <i class="bi bi-person"></i>
                            <span>Nama Lengkap</span>
                        </div>

                        <span class="profile-detail-separator">
                            :
                        </span>

                        <span class="profile-detail-value">
                            {{ $user->name }}
                        </span>
                    </div>

                    <!-- {{-- KELAS --}} -->
                    <div class="profile-detail-item">
                        <div class="profile-detail-label">
                            <i class="bi bi-award"></i>
                            <span>Kelas</span>
                        </div>

                        <span class="profile-detail-separator">
                            :
                        </span>

                        <span class="profile-detail-value">
                            {{ $user->kelas ?? '-' }}
                        </span>
                    </div>

                    <!-- {{-- ROLE --}} -->
                    <div class="profile-detail-item">
                        <div class="profile-detail-label">
                            <i class="bi bi-globe2"></i>
                            <span>Role</span>
                        </div>

                        <span class="profile-detail-separator">
                            :
                        </span>

                        <span class="profile-detail-value role">
                            Member
                        </span>
                    </div>

                    <!-- {{-- STATUS --}} -->
                    <div class="profile-detail-item">
                        <div class="profile-detail-label">
                            <i class="bi bi-stopwatch"></i>
                            <span>Status Akun</span>
                        </div>

                        <span class="profile-detail-separator">
                            :
                        </span>

                        <span class="profile-account-status {{ strtolower($user->status ?? 'aktif') }}">
                            <span class="profile-status-dot"></span>
                            {{ ucfirst($user->status ?? 'aktif') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- {{-- EDIT PROFIL --}} -->
    <div class="profile-card profile-edit-card">
        <div class="profile-edit-title">
            <h2>
                Edit Profil
            </h2>
        </div>

        <!-- {{-- INFO --}} -->
        <div class="profile-info-message">
            <i class="bi bi-info-circle"></i>
            <span>
                Anda dapat mengubah foto profil.
                Data akun lainnya hanya dapat diubah melalui Admin.
            </span>
        </div>

        <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
            @csrf
            @method('PUT')

            <!-- {{-- FOTO PROFIL --}} -->
            <div class="profile-photo-section">
                <h3>
                    Foto Profil
                </h3>

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

                    <button type="button"  class="profile-change-photo" id="profileChangePhoto">
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

            <!-- {{-- DIVIDER --}} -->
            <div class="profile-form-divider"></div>

            <!-- {{-- DATA AKUN --}} -->
            <div class="profile-fields">
                <!-- {{-- NIS --}} -->
                <div class="profile-field">
                    <label for="profileNis">
                        NIS
                    </label>

                    <div class="profile-input-wrapper">
                        <input
                            type="text"
                            id="profileNis"
                            value="{{ $user->nis ?? '-' }}"
                            readonly
                        >
                    </div>
                </div>

                <!-- {{-- NAMA --}} -->
                <div class="profile-field">
                    <label for="profileName">
                        Nama Lengkap
                    </label>

                    <div class="profile-input-wrapper">
                        <input
                            type="text"
                            id="profileName"
                            value="{{ $user->name }}"
                            readonly
                        >
                    </div>
                </div>

                <!-- {{-- PASSWORD --}} -->
                <div class="profile-field">
                    <label for="profilePassword">
                        Password
                    </label>

                    <div class="profile-input-wrapper">
                        <input
                            type="password"
                            id="profilePassword"
                            value="***************"
                            readonly
                        >

                        <i class="bi bi-lock-fill profile-lock-icon"></i>
                    </div>
                </div>

                <!-- {{-- INFO ADMIN --}} -->
                <div class="profile-admin-message">
                    <i class="bi bi-lock-fill"></i>

                    <span>
                        Data hanya dapat diubah melalui Admin
                    </span>
                </div>

                <!-- {{-- SUBMIT --}} -->
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
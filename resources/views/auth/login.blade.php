@extends('layouts.app')

@section('content')

<x-guest-layout>
    @vite(['resources/css/login.css'])

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')"/>

    <div class="login-page">
        <!-- Login Container -->
        <main class="login-container">
            <div class="login-card">
                <a href="/" class="login-back">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <div class="login-logo">
                    <img src="{{ asset('images/logo-login.png') }}" alt="Logo Perpustakaan">
                </div>
                
                <h1>Login</h1>

                <p class="login-description">Masuk untuk akses layanan perpustakaan</p>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <!-- NIS / Email -->
                    <div class="login-input-group">
                        <span class="login-input-icon">
                            <i class="bi bi-person"></i>
                        </span>

                        <x-text-input
                            id="login"
                            class="login-input"
                            type="text"
                            name="login"
                            :value="old('login')"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Masukan NIS atau Email"
                        />
                    </div>

                    <x-input-error :messages="$errors->get('login')" class="login-error"/>

                    <!-- Password -->
                    <div class="login-input-group">
                        <span class="login-input-icon">
                            <i class="bi bi-lock"></i>
                        </span>

                        <x-text-input
                            id="password"
                            class="login-input"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukan Kata Sandi"
                        />
                    </div>

                    <x-input-error :messages="$errors->get('password')" class="login-error"/>

                    <!-- Tombol Masuk -->
                    <button type="submit" class="login-button">
                        <span>Masuk</span>

                        <span class="login-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </span>
                    </button>

                    <!-- Bantuan -->
                    <div class="login-help">
                        <div class="login-help-line"></div>
                        <span>Perlu bantuan?</span>
                        <div class="login-help-line"></div>
                    </div>

                    <a href="#" class="login-contact">
                        <i class="bi bi-telephone"></i>
                        Hubungi petugas perpustakaan
                    </a>
                </form>
            </div>
        </main>
    </div>
</x-guest-layout>

@endsection
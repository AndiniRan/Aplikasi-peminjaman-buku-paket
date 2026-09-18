@extends('layouts.dashboard')

@section('title', 'Tambah Peminjaman')
@section('page-title', 'Tambah Peminjaman')

@vite([
    'resources/css/adminTransaksi.css',
    'resources/js/adminTransaksi.js'
])

@section('content')
<div class="transaksi-form-page" data-form="peminjaman">
    <div class="transaksi-form-card">
        <div class="transaksi-form-title">
            <h2>Tambah Data Peminjaman Buku</h2>
            <p>Silakan isi data peminjaman buku pada form di bawah ini</p>
        </div>

        @if(session('error'))
            <div class="transaksi-alert transaksi-alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="transaksi-alert transaksi-alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('admin.transaksi.peminjaman.store') }}" method="POST" class="transaksi-form">
            @csrf

            <!-- {{-- MEMBER --}} -->
            <div class="transaksi-form-row">
                <label>Member</label>
                <div class="transaksi-custom-select" data-form-select data-target="user_id">
                    <button type="button" class="transaksi-custom-select-button" data-form-select-button>
                        <span data-form-select-text>Pilih Member</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="transaksi-custom-select-menu">
                        @foreach($member as $item)
                            @php
                                $memberText = $item->name;

                                if ($item->role === 'siswa' && $item->kelas) {
                                    $memberText .= ' - Kelas ' . $item->kelas;
                                } else {
                                    $memberText .= ' - Guru';
                                }
                            @endphp

                            <button type="button"
                                    class="transaksi-custom-select-option
                                    {{ old('user_id') == $item->id ? 'active' : '' }}"
                                    data-form-select-option
                                    data-value="{{ $item->id }}">
                                {{ $memberText }}
                            </button>
                        @endforeach
                    </div>

                    <select name="user_id" id="user_id" class="transaksi-hidden-select" required>
                        <option value="">Pilih Member</option>
                        @foreach($member as $item)
                            <option value="{{ $item->id }}"
                                    @selected(old('user_id') == $item->id)>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- {{-- BUKU --}} -->
            <div class="transaksi-form-row">
                <label>Buku</label>
                <div class="transaksi-custom-select" data-form-select data-target="buku_id">
                    <button type="button" class="transaksi-custom-select-button" data-form-select-button>
                        <span data-form-select-text>Pilih Buku</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="transaksi-custom-select-menu">
                        @foreach($buku as $item)
                            <button type="button"
                                    class="transaksi-custom-select-option
                                    {{ old('buku_id') == $item->id ? 'active' : '' }}"
                                    data-form-select-option
                                    data-value="{{ $item->id }}"
                                    data-stock="{{ $item->stok_tersedia }}">
                                {{ $item->judul }}
                            </button>
                        @endforeach
                    </div>

                    <select name="buku_id" id="buku_id" class="transaksi-hidden-select" required>
                        <option value="" data-stock="0">
                            Pilih Buku
                        </option>

                        @foreach($buku as $item)
                            <option value="{{ $item->id }}"
                                    data-stock="{{ $item->stok_tersedia }}"
                                    @selected(old('buku_id') == $item->id)>
                                {{ $item->judul }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- {{-- STOK --}} -->
            <div class="transaksi-form-row">
                <label>Stok Tersedia</label>
                <div class="transaksi-readonly">
                    <input type="text" id="stokTersedia" value="0" readonly>
                    <span>(readonly)</span>
                </div>
            </div>

            <!-- {{-- TANGGAL PINJAM --}} -->
            <div class="transaksi-form-row">
                <label>Tanggal Pinjam</label>
                <div class="transaksi-custom-date" data-date-picker>
                    <input type="text" class="transaksi-custom-date-display" data-date-display placeholder="DD/MM/YYYY" readonly>
                    <button type="button" class="transaksi-custom-date-button" data-date-button aria-label="Pilih tanggal">
                        <i class="bi bi-calendar3"></i>
                    </button>

                    <input type="hidden" name="tanggal_pinjam" id="tanggal_pinjam" data-date-input value="{{ old('tanggal_pinjam') }}">

                    <div class="transaksi-calendar" data-calendar>
                        <div class="transaksi-calendar-header">
                            <button type="button" class="transaksi-calendar-nav" data-calendar-prev>
                                <i class="bi bi-chevron-left"></i>
                            </button>

                            <div class="transaksi-calendar-title"
                                 data-calendar-title>
                            </div>

                            <button type="button" class="transaksi-calendar-nav" data-calendar-next>
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>

                        <div class="transaksi-calendar-weekdays">
                            <span>Min</span>
                            <span>Sen</span>
                            <span>Sel</span>
                            <span>Rab</span>
                            <span>Kam</span>
                            <span>Jum</span>
                            <span>Sab</span>
                        </div>

                        <div class="transaksi-calendar-days"
                             data-calendar-days>
                        </div>
                    </div>
                </div>
            </div>

            <!-- {{-- JATUH TEMPO --}} -->
            <div class="transaksi-form-row">
                <label>Jatuh Tempo</label>
                <div class="transaksi-readonly">
                    <input type="text" id="jatuhTempoPreview" value="00-00-0000" readonly>
                    <span>(otomatis)</span>
                </div>
            </div>

            <!-- {{-- STATUS --}} -->
            <div class="transaksi-form-row">
                <label>Status</label>
                <div class="transaksi-readonly">
                    <input type="text" value="Dipinjam" readonly>
                    <span>(readonly)</span>
                </div>
            </div>

            <!-- {{-- ACTION --}} -->
            <div class="transaksi-form-actions">
                <a href="{{ route('admin.transaksi.peminjaman.index') }}" class="transaksi-form-btn cancel">
                    Batal
                </a>

                <button type="submit"
                        class="transaksi-form-btn save">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
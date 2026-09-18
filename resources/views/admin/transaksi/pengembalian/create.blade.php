@extends('layouts.dashboard')

@section('title', 'Tambah Pengembalian')
@section('page-title', 'Tambah Pengembalian')

@vite([
    'resources/css/adminTransaksi.css',
    'resources/js/adminTransaksi.js'
])

@section('content')
<div class="transaksi-form-page" data-form="pengembalian">
    <div class="transaksi-form-card">
        <div class="transaksi-form-title">
            <h2>Pengembalian Buku</h2>
            <p>Silakan pilih transaksi aktif untuk pengembalian buku</p>
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

        <form action="{{ route('admin.transaksi.pengembalian.store') }}" method="POST" class="transaksi-form">
            @csrf

            <!-- {{-- TRANSAKSI AKTIF --}} -->
            <div class="transaksi-form-row">
                <label>Transaksi Aktif</label>
                <div class="transaksi-custom-select" data-form-select data-target="transaksi_id">
                    <button type="button" class="transaksi-custom-select-button" data-form-select-button>
                        <span data-form-select-text>
                            Pilih Transaksi Aktif
                        </span>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="transaksi-custom-select-menu transaksi-return-menu">
                        @foreach($transaksiAktif as $item)
                            <button type="button"
                                    class="transaksi-custom-select-option
                                    {{ old('transaksi_id') == $item->transaksi_id ? 'active' : '' }}"
                                    data-form-select-option
                                    data-value="{{ $item->transaksi_id }}">

                                <span class="transaksi-option-main">
                                    {{ $item->user?->name ?? '-' }}
                                </span>

                                <span class="transaksi-option-sub">
                                    {{ $item->buku?->judul ?? '-' }}
                                </span>
                            </button>
                        @endforeach
                    </div>

                    <select name="transaksi_id" id="transaksi_id" class="transaksi-hidden-select" required>
                        <option value="">
                            Pilih Transaksi Aktif
                        </option>

                        @foreach($transaksiAktif as $item)
                            <option value="{{ $item->transaksi_id }}"
                                    data-member="{{ $item->user?->name ?? '-' }}"
                                    data-buku="{{ $item->buku?->judul ?? '-' }}"
                                    data-pinjam="{{ $item->tanggal_pinjam?->format('Y-m-d') }}"
                                    data-jatuh-tempo="{{ $item->tanggal_jatuh_tempo?->format('Y-m-d') }}"
                                    @selected(old('transaksi_id') == $item->transaksi_id)>
                                {{ $item->user?->name ?? '-' }}
                                - {{ $item->buku?->judul ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- {{-- MEMBER --}} -->
            <div class="transaksi-form-row">
                <label>Nama Member</label>
                <div class="transaksi-readonly">
                    <input type="text" id="returnMember" value="-" readonly>
                    <span>(readonly)</span>
                </div>
            </div>

            <!-- {{-- BUKU --}} -->
            <div class="transaksi-form-row">
                <label>Judul Buku</label>
                <div class="transaksi-readonly">
                    <input type="text" id="returnBuku" value="-" readonly>
                    <span>(readonly)</span>
                </div>
            </div>

            <!-- {{-- TANGGAL PINJAM --}} -->
            <div class="transaksi-form-row">
                <label>Tanggal Pinjam</label>
                <div class="transaksi-readonly">
                    <input type="text" id="returnTanggalPinjam" value="-" readonly>
                    <span>(readonly)</span>
                </div>
            </div>

            <!-- {{-- JATUH TEMPO --}} -->
            <div class="transaksi-form-row">
                <label>Jatuh Tempo</label>
                <div class="transaksi-readonly">
                    <input type="text" id="returnJatuhTempo" value="-" readonly>
                    <span>(readonly)</span>
                </div>
            </div>

            <!-- {{-- TANGGAL KEMBALI --}} -->
            <div class="transaksi-form-row">
                <label>Tanggal Kembali</label>
                <div class="transaksi-custom-date" data-date-picker>
                    <input type="text" class="transaksi-custom-date-display" data-date-display placeholder="DD/MM/YYYY" readonly>
                    <button type="button" class="transaksi-custom-date-button" data-date-button aria-label="Pilih tanggal">
                        <i class="bi bi-calendar3"></i>
                    </button>

                    <input type="hidden" name="tanggal_kembali" id="tanggal_kembali" data-date-input value="{{ old('tanggal_kembali') }}">
                    <div class="transaksi-calendar" data-calendar>
                        <div class="transaksi-calendar-header">
                            <button type="button" class="transaksi-calendar-nav" data-calendar-prev>
                                <i class="bi bi-chevron-left"></i>
                            </button>

                            <div class="transaksi-calendar-title" data-calendar-title>
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

                        <div class="transaksi-calendar-days" data-calendar-days>
                        </div>
                    </div>
                </div>
            </div>

            <!-- {{-- STATUS PENGEMBALIAN --}} -->
            <div class="transaksi-form-row transaksi-return-status-row">
                <label>Status Pengembalian</label>
                <div class="transaksi-return-status" id="returnStatus">
                    <div class="return-option tepat" id="statusTepat">
                        <div class="return-status-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>

                        <div class="return-status-text">
                            <strong>Tepat Waktu</strong>
                            <span>Dikembalikan sesuai batas waktu</span>
                        </div>
                    </div>

                    <div class="return-option terlambat" id="statusTerlambat">
                        <div class="return-status-icon">
                            <i class="bi bi-exclamation-circle-fill"></i>
                        </div>

                        <div class="return-status-text">
                            <strong>Terlambat</strong>
                            <span>Melewati tanggal jatuh tempo</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- {{-- ACTION --}} -->
            <div class="transaksi-form-actions">
                <a href="{{ route('admin.transaksi.peminjaman.index') }}" class="transaksi-form-btn cancel">
                    Batal
                </a>

                <button type="submit" class="transaksi-form-btn save">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
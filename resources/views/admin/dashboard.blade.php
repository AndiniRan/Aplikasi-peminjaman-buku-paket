@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')

<div class="admin-dashboard-page">

    <!-- {{-- HEADER --}} -->
    <div class="admin-dashboard-header">
        <div class="admin-dashboard-heading">
            <h2>Halo, Admin!</h2>
            <p>Berikut ringkasan informasi perpustakaan hari ini.</p>
        </div>

        <!-- {{-- DATE --}} -->
        <div class="admin-date-card">
            <div class="admin-date-icon">
                <i class="bi bi-calendar3"></i>
            </div>

            <div class="admin-date-content">
                <strong>
                    {{ now()->locale('id')->translatedFormat('d F Y') }}
                </strong>

                <span>
                    {{ now()->locale('id')->translatedFormat('l, H.i') }} WIB
                </span>
            </div>
        </div>
    </div>

    <!-- {{-- SUMMARY CARDS --}} -->
    <div class="admin-summary-grid">

        <!-- {{-- TOTAL BUKU --}} -->
        <div class="admin-summary-card">
            <div class="admin-summary-icon blue">
                <i class="bi bi-journals"></i>
            </div>

            <div class="admin-summary-content">
                <h3>Total Buku</h3>
                <strong>{{ $totalBuku ?? 0 }}</strong>
                <span>Semua Koleksi</span>
            </div>
        </div>

        <!-- {{-- TOTAL MEMBER --}} -->
        <div class="admin-summary-card">
            <div class="admin-summary-icon orange">
                <i class="bi bi-people-fill"></i>
            </div>

            <div class="admin-summary-content">
                <h3>Total Member</h3>
                <strong>{{ $totalMember ?? 0 }}</strong>
                <span>Member Terdaftar</span>
            </div>
        </div>

        <!-- {{-- BUKU DIPINJAM --}} -->
        <div class="admin-summary-card">
            <div class="admin-summary-icon green">
                <i class="bi bi-journal-check"></i>
            </div>

            <div class="admin-summary-content">
                <h3>Buku Dipinjam</h3>
                <strong>{{ $bukuDipinjam ?? 0 }}</strong>
                <span>Sedang Dipinjam</span>
            </div>
        </div>

        <!-- {{-- BUKU TERLAMBAT --}} -->
        <div class="admin-summary-card">
            <div class="admin-summary-icon red">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <div class="admin-summary-content">
                <h3>Buku Terlambat</h3>
                <strong>{{ $bukuTerlambat ?? 0 }}</strong>
                <span>Perlu Dikembalikan</span>
            </div>
        </div>
    </div>

    <!-- {{-- STATISTIK --}} -->
    <div class="admin-statistics-card">
        <div class="admin-statistics-header">
            <div class="admin-statistics-title">
                <div class="admin-statistics-icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>

                <div>
                    <h3>Statistik Peminjaman Buku Paket</h3>
                    <p>Jumlah peminjaman berdasarkan judul buku paket.</p>
                </div>
            </div>

            <!-- {{-- FILTER --}} -->
            <div class="admin-statistics-filters">

                <!-- {{-- PERIODE --}} -->
                <div class="admin-filter-group">
                    <label>Periode</label>
                    <input type="hidden" id="periodeFilter" value="bulan">

                    <div class="admin-filter-select" data-target="periodeFilter">
                        <button type="button" class="admin-filter-button">
                            <span>Per Bulan</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>

                        <div class="admin-filter-options">
                            <button type="button" data-value="hari">
                                Per Hari
                            </button>

                            <button type="button" data-value="bulan" class="active">
                                Per Bulan
                            </button>

                            <button type="button" data-value="tahun">
                                Per Tahun
                            </button>
                        </div>
                    </div>
                </div>

                <!-- {{-- KATEGORI --}} -->
                <div class="admin-filter-group">
                    <label>Kategori</label>
                    <input type="hidden" id="kategoriFilter" value="">

                    <div class="admin-filter-select" data-target="kategoriFilter">
                        <button type="button" class="admin-filter-button">
                            <span>Semua Kategori</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>

                        <div class="admin-filter-options">
                            <button type="button" data-value="" class="active">
                                Semua Kategori
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- {{-- BODY STATISTIK --}} -->
        <div class="admin-statistics-body">

            <!-- {{-- TOTAL --}} -->
            <div class="admin-total-card">
                <span class="admin-total-label">
                    Total Peminjaman
                </span>

                <strong id="totalPeminjaman">
                    0
                </strong>

                <div class="admin-data-badge">
                    <i class="bi bi-graph-up-arrow"></i>

                    <span>
                        Data Peminjaman
                    </span>
                </div>
            </div>

            <!-- {{-- CHART --}} -->
            <div class="admin-chart-card">
                <canvas id="loanChart"></canvas>

                <div class="admin-chart-empty" id="chartEmpty">
                    <i class="bi bi-calendar2-week"></i>

                    <span>
                        Belum ada data peminjaman.
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
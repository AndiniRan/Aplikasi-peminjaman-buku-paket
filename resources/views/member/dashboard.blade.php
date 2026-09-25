@extends('layouts.dashboard')

@section('title', 'Dashboard Member')
@section('page-title', 'Dashboard')

@vite([
    'resources/css/memberDashboard.css',
])

@section('content')

<div class="member-dashboard-page">
    <!-- HEADER -->
    <div class="member-dashboard-header">
        <div class="member-dashboard-heading">
            <h2>
                Selamat Datang, {{ $user->name }}!
            </h2>

            <p>
                Yuk, mulai eksplorasi buku dan tambah pengetahuanmu hari ini.
            </p>
        </div>

        <!-- DATE -->
        <div class="member-date-card">
            <div class="member-date-icon">
                <i class="bi bi-calendar3"></i>
            </div>

            <div class="member-date-content">
                <strong>
                    {{ now()->locale('id')->translatedFormat('d F Y') }}
                </strong>

                <span>
                    {{ now()->locale('id')->translatedFormat('l, H.i') }} WIB
                </span>
            </div>
        </div>
    </div>


    <!-- SUMMARY -->
    <div class="member-summary-grid">
        <!-- BUKU DIPINJAM -->
        <div class="member-summary-card">
            <div class="member-summary-icon blue">
                <i class="bi bi-book"></i>
            </div>

            <div class="member-summary-content">
                <h3>Buku Sedang Dipinjam</h3>

                <strong>
                    {{ $bukuDipinjam ?? 0 }}
                </strong>

                <span>
                    Buku
                </span>
            </div>
        </div>

        <!-- JATUH TEMPO -->
        <div class="member-summary-card">
            <div class="member-summary-icon orange">
                <i class="bi bi-calendar2-x"></i>
            </div>

            <div class="member-summary-content">
                <h3>Jatuh Tempo Terdekat</h3>

                @if($jatuhTempoTerdekat)
                    <strong class="member-due-date">
                        {{ \Carbon\Carbon::parse($jatuhTempoTerdekat->tanggal_jatuh_tempo)->translatedFormat('d M Y') }}
                    </strong>

                    <span>
                        Segera dikembalikan
                    </span>
                @else
                    <strong>
                        -
                    </strong>

                    <span>
                        Belum ada jatuh tempo
                    </span>
                @endif
            </div>
        </div>

        <!-- RIWAYAT -->
        <div class="member-summary-card">
            <div class="member-summary-icon green">
                <i class="bi bi-clock-history"></i>
            </div>

            <div class="member-summary-content">
                <h3>Total Riwayat Peminjaman</h3>

                <strong>
                    {{ $totalRiwayat ?? 0 }}
                </strong>

                <span>
                    Transaksi
                </span>
            </div>
        </div>
    </div>

    <!-- NOTIFIKASI -->
    <div class="member-notification-card">
        <div class="member-notification-header">
            <div class="member-notification-title">
                <div class="member-notification-title-icon">
                    <i class="bi bi-bell"></i>
                </div>

                <div>
                    <h3>Notifikasi Terbaru</h3>

                    <p>
                        Informasi terbaru mengenai pengajuan dan peminjaman buku.
                    </p>
                </div>
            </div>
        </div>

        <div class="member-notification-list">
            @forelse($notifikasi ?? [] as $item)
                <div class="member-notification-item">
                    <div class="member-notification-icon {{ $item['type'] ?? 'blue' }}">
                        <i class="{{ $item['icon'] ?? 'bi bi-bell' }}"></i>
                    </div>

                    <div class="member-notification-content">
                        <div class="member-notification-top">
                            <h4>
                                {{ $item['title'] }}
                            </h4>

                            <span>
                                {{ $item['time'] ?? '' }}
                            </span>
                        </div>

                        <p>
                            {{ $item['message'] }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="member-notification-empty">
                    <div class="member-notification-empty-icon">
                        <i class="bi bi-bell"></i>
                    </div>

                    <h4>Belum Ada Notifikasi</h4>

                    <p>
                        Notifikasi mengenai pengajuan dan peminjaman buku akan muncul di sini.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
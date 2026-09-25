

<?php $__env->startSection('title', 'Dashboard Member'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php echo app('Illuminate\Foundation\Vite')([
    'resources/css/memberDashboard.css',
]); ?>

<?php $__env->startSection('content'); ?>

<div class="member-dashboard-page">
    <!-- HEADER -->
    <div class="member-dashboard-header">
        <div class="member-dashboard-heading">
            <h2>
                Selamat Datang, <?php echo e($user->name); ?>!
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
                    <?php echo e(now()->locale('id')->translatedFormat('d F Y')); ?>

                </strong>

                <span>
                    <?php echo e(now()->locale('id')->translatedFormat('l, H.i')); ?> WIB
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
                    <?php echo e($bukuDipinjam ?? 0); ?>

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

                <?php if($jatuhTempoTerdekat): ?>
                    <strong class="member-due-date">
                        <?php echo e(\Carbon\Carbon::parse($jatuhTempoTerdekat->tanggal_jatuh_tempo)->translatedFormat('d M Y')); ?>

                    </strong>

                    <span>
                        Segera dikembalikan
                    </span>
                <?php else: ?>
                    <strong>
                        -
                    </strong>

                    <span>
                        Belum ada jatuh tempo
                    </span>
                <?php endif; ?>
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
                    <?php echo e($totalRiwayat ?? 0); ?>

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
            <?php $__empty_1 = true; $__currentLoopData = $notifikasi ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="member-notification-item">
                    <div class="member-notification-icon <?php echo e($item['type'] ?? 'blue'); ?>">
                        <i class="<?php echo e($item['icon'] ?? 'bi bi-bell'); ?>"></i>
                    </div>

                    <div class="member-notification-content">
                        <div class="member-notification-top">
                            <h4>
                                <?php echo e($item['title']); ?>

                            </h4>

                            <span>
                                <?php echo e($item['time'] ?? ''); ?>

                            </span>
                        </div>

                        <p>
                            <?php echo e($item['message']); ?>

                        </p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="member-notification-empty">
                    <div class="member-notification-empty-icon">
                        <i class="bi bi-bell"></i>
                    </div>

                    <h4>Belum Ada Notifikasi</h4>

                    <p>
                        Notifikasi mengenai pengajuan dan peminjaman buku akan muncul di sini.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/member/dashboard.blade.php ENDPATH**/ ?>


<?php $__env->startSection('title', 'Detail Buku'); ?>
<?php $__env->startSection('page-title', 'Detail Buku'); ?>

<?php $__env->startSection('content'); ?>

<?php echo app('Illuminate\Foundation\Vite')([
    'resources/css/memberKatalog.css',
    'resources/js/memberKatalog.js'
]); ?>

<div class="member-book-detail-page">
    <div class="member-book-detail-card">
        <div class="member-book-detail-top">
            <a href="<?php echo e(route('member.katalog.index')); ?>" class="member-book-back">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        <div class="member-book-detail-content">
            <div class="member-book-detail-cover">
                <?php if($buku->sampul): ?>
                    <img src="<?php echo e(asset('storage/' . $buku->sampul)); ?>" alt="<?php echo e($buku->judul); ?>">
                <?php else: ?>
                    <div class="member-book-detail-placeholder">
                        <i class="bi bi-book"></i>
                    </div>
                <?php endif; ?>

            </div>

            <div class="member-book-detail-info">
                <div class="member-book-info-row">
                    <span class="member-book-label">
                        Judul Buku
                    </span>

                    <span class="member-book-separator">:</span>

                    <strong>
                        <?php echo e($buku->judul); ?>

                    </strong>
                </div>

                <div class="member-book-info-row">
                    <span class="member-book-label">
                        Kategori
                    </span>

                    <span class="member-book-separator">:</span>

                    <strong class="member-book-category">
                        <?php echo e($buku->kategori->nama_kategori ?? '-'); ?>

                    </strong>
                </div>

                <div class="member-book-info-row">
                    <span class="member-book-label">
                        Pengarang
                    </span>

                    <span class="member-book-separator">:</span>

                    <strong>
                        <?php echo e($buku->pengarang); ?>

                    </strong>
                </div>

                <div class="member-book-info-row">
                    <span class="member-book-label">
                        Penerbit
                    </span>

                    <span class="member-book-separator">:</span>

                    <strong>
                        <?php echo e($buku->penerbit); ?>

                    </strong>
                </div>

                <div class="member-book-info-row">
                    <span class="member-book-label">
                        Tahun Terbit
                    </span>

                    <span class="member-book-separator">:</span>

                    <strong>
                        <?php echo e($buku->tahun_terbit); ?>

                    </strong>
                </div>

                <div class="member-book-info-row">
                    <span class="member-book-label">
                        Stok Buku
                    </span>

                    <span class="member-book-separator">:</span>

                    <div class="member-book-stock">
                        <span class="member-stock-total">
                            <?php echo e($buku->total_stok); ?>

                        </span>

                        <strong>Buku</strong>
                    </div>
                </div>

                <div class="member-book-info-row">
                    <span class="member-book-label">
                        Stok Tersedia
                    </span>

                    <span class="member-book-separator">:</span>

                    <div class="member-book-stock">
                        <span class="member-stock-available <?php echo e($buku->stok_tersedia <= 0 ? 'empty' : ''); ?>">
                            <?php echo e($buku->stok_tersedia); ?>

                        </span>

                        <strong>Buku</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="member-book-detail-bottom">
            <div class="member-book-status-card">
                <h3>Status Buku</h3>

                <?php if($buku->stok_tersedia > 0): ?>
                    <div class="member-status-box available">
                        <div class="member-status-title">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Tersedia</span>
                        </div>

                        <p>Buku ini tersedia dan dapat dipinjam</p>
                    </div>
                <?php else: ?>
                    <div class="member-status-box unavailable">
                        <div class="member-status-title">
                            <i class="bi bi-x-circle-fill"></i>
                            <span>Tidak Tersedia</span>
                        </div>

                        <p>
                            Stok buku sedang habis
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="member-book-status-card">
                <h3>Status Peminjaman Saya</h3>
                <div class="member-status-box loan">
                    <div class="member-status-title">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Belum Dipinjam</span>
                    </div>

                    <p>Anda belum meminjam buku ini</p>
                </div>
            </div>

            <div class="member-book-action">
                <?php if($buku->stok_tersedia > 0): ?>
                    <a href="<?php echo e(route('member.katalog.pengajuan', $buku)); ?>" class="member-book-submit">
                        Ajukan Peminjaman
                    </a>
                <?php else: ?>
                    <button type="button" class="member-book-submit disabled" disabled>
                        Stok Habis
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/member/katalog/show.blade.php ENDPATH**/ ?>
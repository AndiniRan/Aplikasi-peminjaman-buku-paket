

<?php $__env->startSection('title', 'Ajukan Peminjaman'); ?>
<?php $__env->startSection('page-title', 'Ajukan Peminjaman'); ?>

<?php $__env->startSection('content'); ?>

<?php echo app('Illuminate\Foundation\Vite')([
    'resources/css/memberKatalog.css',
    'resources/js/memberKatalog.js'
]); ?>

<div class="member-detail-page">
    <div class="member-detail-card">
        <div class="member-detail-header">
            <h2>Konfirmasi Pengajuan Peminjaman</h2>

            <p>Pastikan data peminjaman sudah benar sebelum diajukan.</p>
        </div>

        <div class="member-detail-form">
            <div class="member-detail-row">
                <label>Nama Member</label>

                <div class="member-detail-field">
                    <span>
                        <?php echo e(auth()->user()->name); ?>

                    </span>

                    <small>(otomatis)</small>
                </div>
            </div>

            <div class="member-detail-row">
                <label>Kelas</label>
                <div class="member-detail-field">
                    <span>
                        <?php echo e(auth()->user()->kelas ?? '-'); ?>

                    </span>

                    <small>(otomatis)</small>
                </div>
            </div>

            <div class="member-detail-row">
                <label>Judul Buku</label>
                <div class="member-detail-field">
                    <span>
                        <?php echo e($buku->judul); ?>

                    </span>

                    <small>(otomatis)</small>
                </div>
            </div>

            <div class="member-detail-row">
                <label>Kategori</label>

                <div class="member-detail-field">
                    <span>
                        <?php echo e($buku->kategori->nama_kategori ?? '-'); ?>

                    </span>

                    <small>(otomatis)</small>
                </div>
            </div>

            <div class="member-detail-row member-detail-gap">
                <label>Stok Tersedia</label>

                <div class="member-detail-field readonly">
                    <span>
                        <?php echo e($buku->stok_tersedia); ?>

                    </span>

                    <small>(readonly)</small>
                </div>
            </div>

            <div class="member-detail-row">
                <label>Tanggal Pengajuan</label>

                <div class="member-detail-field">
                    <span>
                        <?php echo e(now()->format('d-m-Y')); ?>

                    </span>

                    <small>(otomatis)</small>
                </div>
            </div>

            <div class="member-detail-row">
                <label>Status</label>

                <div class="member-detail-field readonly">
                    <span>Menunggu</span>

                    <small>(readonly)</small>
                </div>
            </div>
        </div>

        <div class="member-detail-actions">
            <a href="<?php echo e(route('member.katalog.show', $buku)); ?>" class="member-detail-cancel">
                Batal
            </a>

            <button type="button" class="member-detail-submit">
                Ajukan
            </button>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/member/katalog/pengajuan.blade.php ENDPATH**/ ?>


<?php $__env->startSection('title', 'Detail Buku'); ?>
<?php $__env->startSection('page-title', 'Detail Buku'); ?>

<?php $__env->startSection('content'); ?>

<?php echo app('Illuminate\Foundation\Vite')([
    'resources/css/adminBuku.css',
    'resources/js/adminBuku.js'
]); ?>

<div class="buku-detail-page">
    <div class="buku-detail-card">
        <div class="buku-detail-top">
            <a href="<?php echo e(url()->previous()); ?>" class="buku-detail-back">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        <div class="buku-detail-content">
            <div class="buku-detail-cover">
                <?php if($buku->sampul): ?>
                    <img src="<?php echo e(asset('storage/' . $buku->sampul)); ?>" alt="<?php echo e($buku->judul); ?>">
                <?php else: ?>
                    <div class="buku-detail-placeholder">
                        <i class="bi bi-book"></i>
                    </div>
                <?php endif; ?>
            </div>

            <div class="buku-detail-info">
                <div class="buku-detail-row">
                    <span class="detail-label">Judul Buku</span>
                    <span class="detail-separator">:</span>
                    <strong><?php echo e($buku->judul); ?></strong>
                </div>

                <div class="buku-detail-row">
                    <span class="detail-label">Kategori</span>
                    <span class="detail-separator">:</span>
                    <strong class="detail-category">
                        <?php echo e($buku->kategori->nama_kategori); ?>

                    </strong>
                </div>

                <div class="buku-detail-row">
                    <span class="detail-label">Pengarang</span>
                    <span class="detail-separator">:</span>
                    <strong><?php echo e($buku->pengarang); ?></strong>
                </div>

                <div class="buku-detail-row">
                    <span class="detail-label">Penerbit</span>
                    <span class="detail-separator">:</span>
                    <strong><?php echo e($buku->penerbit); ?></strong>
                </div>

                <div class="buku-detail-row">
                    <span class="detail-label">Tahun Terbit</span>
                    <span class="detail-separator">:</span>
                    <strong><?php echo e($buku->tahun_terbit); ?></strong>
                </div>

                <div class="buku-detail-row">
                    <span class="detail-label">Stok Buku</span>
                    <span class="detail-separator">:</span>

                    <div class="detail-stock">
                        <span class="detail-stock-total">
                            <?php echo e($buku->total_stok); ?>

                        </span>
                        <strong>Buku</strong>
                    </div>
                </div>

                <div class="buku-detail-row">
                    <span class="detail-label">Stok Tersedia</span>
                    <span class="detail-separator">:</span>

                    <div class="detail-stock">
                        <span class="detail-stock-available">
                            <?php echo e($buku->stok_tersedia); ?>

                        </span>
                        <strong>Buku</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="buku-detail-actions">
            <a href="<?php echo e(route('admin.buku.edit', $buku)); ?>" class="detail-btn-edit">
                <i class="bi bi-pencil-square"></i>
                <span>Edit</span>
            </a>

            <button type="button" class="detail-btn-delete buku-delete" data-form="deleteBuku<?php echo e($buku->id); ?>">
                <i class="bi bi-trash-fill"></i>
                <span>Hapus</span>
            </button>

            <form action="<?php echo e(route('admin.buku.destroy', $buku)); ?>" method="POST" id="deleteBuku<?php echo e($buku->id); ?>" class="buku-delete-form">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
            </form>
        </div>
    </div>
</div>

<div class="buku-delete-modal" id="bukuDeleteModal">
    <div class="buku-delete-box">
        <div class="buku-delete-icon">
            <i class="bi bi-trash3"></i>
        </div>

        <h3>Hapus Buku?</h3>
        <p>Data buku yang sudah dihapus tidak dapat dikembalikan.</p>

        <div class="buku-delete-actions">
            <button type="button" class="modal-cancel" id="bukuCancelDelete">
                Batal
            </button>

            <button type="button" class="modal-delete" id="bukuConfirmDelete">
                Hapus
            </button>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/admin/buku/show.blade.php ENDPATH**/ ?>
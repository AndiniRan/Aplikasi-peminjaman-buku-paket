

<?php $__env->startSection('title', 'Katalog Buku'); ?>
<?php $__env->startSection('page-title', 'Katalog Buku'); ?>

<?php $__env->startSection('content'); ?>

<?php echo app('Illuminate\Foundation\Vite')([
    'resources/css/adminBuku.css',
    'resources/js/adminBuku.js'
]); ?>

<div class="katalog-page">
    <div class="katalog-toolbar">
        <div class="katalog-search">
            <i class="bi bi-search"></i>
            <input type="text" id="katalogSearch" placeholder="Cari buku berdasarkan judul, penulis, kategori..." autocomplete="off">
        </div>

        <div class="katalog-filter">
            <input type="hidden" id="katalogKategori" value="">

            <div class="katalog-filter-select" data-target="katalogKategori">
                <button type="button" class="katalog-filter-button">
                    <span>Semua Kategori</span>
                    <i class="bi bi-chevron-down"></i>
                </button>

                <div class="katalog-filter-options">
                    <button type="button" data-value="" class="active">
                        Semua Kategori
                    </button>

                    <?php $__currentLoopData = $kategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button
                            type="button"
                            data-value="<?php echo e(strtolower($item->nama_kategori)); ?>"
                        >
                            <?php echo e($item->nama_kategori); ?>

                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="katalog-card">
        <a href="<?php echo e(route('admin.buku.index')); ?>" class="katalog-back">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali</span>
        </a>

        <div class="katalog-grid" id="katalogGrid">
            <?php $__currentLoopData = $buku; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a
                    href="<?php echo e(route('admin.buku.show', $item)); ?>"
                    class="katalog-item"
                    data-search="<?php echo e(strtolower(
                        $item->judul . ' ' .
                        $item->pengarang . ' ' .
                        $item->penerbit . ' ' .
                        $item->kategori->nama_kategori
                    )); ?>"
                    data-category="<?php echo e(strtolower($item->kategori->nama_kategori)); ?>">

                    <div class="katalog-cover">
                        <?php if($item->sampul): ?>
                            <img src="<?php echo e(asset('storage/' . $item->sampul)); ?>" alt="<?php echo e($item->judul); ?>">
                        <?php else: ?>
                            <div class="katalog-cover-placeholder">
                                <i class="bi bi-book"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="katalog-item-content">
                        <h3><?php echo e($item->judul); ?></h3>
                        <p><?php echo e($item->pengarang); ?></p>
                        <p><?php echo e($item->penerbit); ?></p>
                        <p><?php echo e($item->tahun_terbit); ?></p>

                        <div class="katalog-item-stock">
                            <span>Stok tersedia:</span>
                            <strong><?php echo e($item->stok_tersedia); ?></strong>
                        </div>

                        <div class="katalog-item-footer">
                            <span class="katalog-category">
                                <?php echo e($item->kategori->nama_kategori); ?>

                            </span>

                            <?php if($item->stok_tersedia > 0): ?>
                                <span class="katalog-status available">
                                    Tersedia
                                </span>
                            <?php else: ?>
                                <span class="katalog-status unavailable">
                                    Habis
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="katalog-empty" id="katalogEmpty">
            <i class="bi bi-search"></i>
            <strong>Buku tidak ditemukan</strong>
            <span>Coba ubah pencarian atau kategori.</span>
        </div>

        <div class="katalog-pagination" id="katalogPagination"></div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/admin/buku/katalog.blade.php ENDPATH**/ ?>


<?php $__env->startSection('title', 'Katalog Buku'); ?>
<?php $__env->startSection('page-title', 'Katalog Buku'); ?>

<?php $__env->startSection('content'); ?>

<?php echo app('Illuminate\Foundation\Vite')([
    'resources/css/memberKatalog.css',
    'resources/js/memberKatalog.js'
]); ?>

<div class="member-katalog-page">

    <div class="member-katalog-toolbar">

        <div class="member-katalog-search">
            <i class="bi bi-search"></i>

            <input
                type="text"
                id="memberKatalogSearch"
                placeholder="Cari buku berdasarkan judul, penulis, kategori..."
                autocomplete="off"
            >
        </div>

        <div class="member-katalog-filter">

            <input
                type="hidden"
                id="memberKatalogKategori"
                value=""
            >

            <div
                class="member-katalog-filter-select"
                data-target="memberKatalogKategori"
            >

                <button
                    type="button"
                    class="member-katalog-filter-button"
                >
                    <span>Semua Kategori</span>

                    <i class="bi bi-chevron-down"></i>
                </button>

                <div class="member-katalog-filter-options">

                    <button
                        type="button"
                        data-value=""
                        class="active"
                    >
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

    <div class="member-katalog-card">

        <div
            class="member-katalog-grid"
            id="memberKatalogGrid"
        >

            <?php $__currentLoopData = $buku; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div
                    class="member-katalog-item"
                    data-search="<?php echo e(strtolower(
                        $item->judul . ' ' .
                        $item->pengarang . ' ' .
                        $item->penerbit . ' ' .
                        ($item->kategori->nama_kategori ?? '')
                    )); ?>"
                    data-category="<?php echo e(strtolower($item->kategori->nama_kategori ?? '')); ?>"
                >

                    <div class="member-katalog-cover">

                        <?php if($item->sampul): ?>

                            <img
                                src="<?php echo e(asset('storage/' . $item->sampul)); ?>"
                                alt="<?php echo e($item->judul); ?>"
                            >

                        <?php else: ?>

                            <div class="member-katalog-cover-placeholder">
                                <i class="bi bi-book"></i>
                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="member-katalog-content">

                        <h3>
                            <?php echo e($item->judul); ?>

                        </h3>

                        <p>
                            <?php echo e($item->pengarang); ?>

                        </p>

                        <p>
                            <?php echo e($item->penerbit); ?>

                        </p>

                        <p>
                            <?php echo e($item->tahun_terbit); ?>

                        </p>

                        <div class="member-katalog-stock">
                            <span>Stok tersedia:</span>

                            <strong>
                                <?php echo e($item->stok_tersedia); ?>

                            </strong>
                        </div>

                        <div class="member-katalog-footer">

                            <span class="member-katalog-category">
                                <?php echo e($item->kategori->nama_kategori ?? '-'); ?>

                            </span>

                            <?php if($item->stok_tersedia > 0): ?>

                                <span class="member-katalog-status available">
                                    Tersedia
                                </span>

                            <?php else: ?>

                                <span class="member-katalog-status unavailable">
                                    Habis
                                </span>

                            <?php endif; ?>

                        </div>

                    </div>
                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

        <div
            class="member-katalog-empty"
            id="memberKatalogEmpty"
        >
            <i class="bi bi-search"></i>

            <strong>
                Buku tidak ditemukan
            </strong>

            <span>
                Coba ubah pencarian atau kategori.
            </span>
        </div>

        <div
            class="member-katalog-pagination"
            id="memberKatalogPagination"
        ></div>

    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/member/katalog.blade.php ENDPATH**/ ?>
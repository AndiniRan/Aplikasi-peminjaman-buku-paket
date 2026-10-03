

<?php $__env->startSection('content'); ?>

<?php echo app('Illuminate\Foundation\Vite')([
    'resources/css/landingKatalog.css',
    'resources/js/landingKatalog.js'
]); ?>

<div class="landing-katalog-page">
    <div class="landing-katalog-toolbar">
        <div class="landing-katalog-search">
            <i class="bi bi-search"></i>

            <input type="text" id="landingKatalogSearch" placeholder="Cari buku berdasarkan judul, penulis, kategori..." autocomplete="off">

        </div>

        
        <div class="landing-katalog-filter">

            <input type="hidden" id="landingKatalogKategori" value="">

            <div class="landing-katalog-filter-select">

                <button type="button" class="landing-katalog-filter-button" id="landingKatalogFilterButton"> <span>Semua Kategori</span>
                    <i class="bi bi-chevron-down"></i>
                </button>

                <div class="landing-katalog-filter-options" id="landingKatalogFilterOptions">
                    <button type="button" data-value="" class="active">
                        Semua Kategori
                    </button>

                    <?php $__currentLoopData = $kategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <button type="button" data-value="<?php echo e(strtolower($item->nama_kategori)); ?>">
                            <?php echo e($item->nama_kategori); ?>

                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <!--  -->
    <div class="landing-katalog-card">
        <a href="<?php echo e(route('landing.landingPage')); ?>" class="landing-katalog-back">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali</span>
        </a>

        <!--  -->
        <div class="landing-katalog-grid" id="landingKatalogGrid">
            <?php $__currentLoopData = $buku; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <button type="button" class="landing-katalog-item landing-katalog-login-required"
                    data-search="<?php echo e(strtolower(
                        $item->judul . ' ' .
                        $item->pengarang . ' ' .
                        $item->penerbit . ' ' .
                        ($item->kategori?->nama_kategori ?? '')
                    )); ?>"
                    data-category="<?php echo e(strtolower(
                        $item->kategori?->nama_kategori ?? ''
                    )); ?>"
                >

                    <!--  -->
                    <div class="landing-katalog-cover">
                        <?php if($item->sampul): ?>
                            <img src="<?php echo e(asset('storage/' . $item->sampul)); ?>" alt="<?php echo e($item->judul); ?>">
                        <?php else: ?>
                            <div class="landing-katalog-cover-placeholder">
                                <i class="bi bi-book"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!--  -->
                    <div class="landing-katalog-item-content">
                        <h3><?php echo e($item->judul); ?></h3>
                        <p><?php echo e($item->pengarang); ?></p>
                        <p><?php echo e($item->penerbit); ?></p>
                        <p><?php echo e($item->tahun_terbit); ?></p>

                        <div class="landing-katalog-stock">
                            <span>Stok tersedia:</span>
                            <strong><?php echo e($item->stok_tersedia); ?></strong>
                        </div>

                        <div class="landing-katalog-item-footer">
                            <span class="landing-katalog-category">
                                <?php echo e($item->kategori?->nama_kategori ?? '-'); ?>

                            </span>

                            <?php if($item->stok_tersedia > 0): ?>
                                <span class="landing-katalog-status available">
                                    Tersedia
                                </span>
                            <?php else: ?>
                                <span class="landing-katalog-status unavailable">
                                    Habis
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!--  -->
        <div class="landing-katalog-empty" id="landingKatalogEmpty">       
            <i class="bi bi-search"></i>
            <strong>
                Buku tidak ditemukan
            </strong>
            <span>
                Coba ubah pencarian atau kategori.
            </span>
        </div>

        <!--  -->
        <div class="landing-katalog-pagination" id="landingKatalogPagination"></div>
    </div>
</div>

<!--  -->
<div class="landing-katalog-login-modal" id="landingKatalogLoginModal"
>
    <div class="landing-katalog-login-box">
        <div class="landing-katalog-login-icon">
            <i class="bi bi-person-lock"></i>
        </div>

        <h3>Login Diperlukan</h3>
        <p>Anda harus login terlebih dahulu untuk melihat detail buku.</p>

        <div class="landing-katalog-login-actions">
            <button type="button" class="landing-katalog-login-cancel" id="landingKatalogLoginCancel">
                Batal
            </button>

            <a href="<?php echo e(route('login')); ?>" class="landing-katalog-login-button">
                Login
            </a>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/landing/katalog.blade.php ENDPATH**/ ?>
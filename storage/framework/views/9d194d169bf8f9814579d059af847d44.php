

<?php $__env->startSection('title', 'Kategori Buku'); ?>
<?php $__env->startSection('page-title', 'Kategori Buku'); ?>

<?php $__env->startSection('content'); ?>

<?php echo app('Illuminate\Foundation\Vite')([
    'resources/css/adminKategori.css',
    'resources/js/adminKategori.js'
]); ?>

<div class="kategori-page">
    <div class="kategori-card">

        <!--  -->
        <div class="kategori-title">
            <h2>Daftar seluruh kategori buku</h2>
        </div>

        <!--  -->
        <div class="kategori-main-actions">
            <a href="<?php echo e(route('admin.kategori.create')); ?>" class="kategori-btn-add">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Kategori</span>
            </a>
        </div>

        <!--  -->
        <?php if(session('success')): ?>
            <div class="kategori-alert">
                <i class="bi bi-check-circle-fill"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <!--  -->
        <?php if(session('error')): ?>
            <div class="kategori-alert kategori-alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?>

        <!--  -->
        <div class="kategori-control-row">

            <!--  -->
            <div class="kategori-show-control">
                <span>Show</span>
                <input type="hidden" id="kategoriEntries" value="10">

                <div class="entries-filter-select" data-target="kategoriEntries">
                    <button type="button" class="entries-filter-button">
                        <span>10</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="entries-filter-options">
                        <button type="button" data-value="10" class="active">10</button>
                        <button type="button" data-value="25">25</button>
                        <button type="button" data-value="50">50</button>
                    </div>
                </div>

                <span>entries</span>
            </div>

            <!--  -->
            <div class="kategori-search">
                <i class="bi bi-search"></i>
                <input
                    type="text"
                    id="kategoriSearch"
                    placeholder="Cari kategori..."
                    autocomplete="off"
                >
            </div>
        </div>

        <!--  -->
        <div class="kategori-table-wrapper">
            <table class="kategori-table">
                <thead>
                    <tr>
                        <th class="kategori-col-no">No</th>
                        <th>Nama Kategori</th>
                        <th class="kategori-col-action">Aksi</th>
                    </tr>
                </thead>

                <tbody id="kategoriTableBody">
                    <?php $__empty_1 = true; $__currentLoopData = $kategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="kategori-row" data-name="<?php echo e(strtolower($item->nama_kategori)); ?>">
                            <td class="kategori-number">
                                <?php echo e($loop->iteration); ?>

                            </td>

                            <td class="kategori-name">
                                <?php echo e($item->nama_kategori); ?>

                            </td>

                            <td>
                                <div class="kategori-actions">
                                    <a href="<?php echo e(route('admin.kategori.edit', $item)); ?>" class="kategori-action-btn kategori-edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <button type="button" class="kategori-action-btn kategori-delete" data-form="deleteKategori<?php echo e($item->id); ?>">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>

                                    <form id="deleteKategori<?php echo e($item->id); ?>" action="<?php echo e(route('admin.kategori.destroy', $item)); ?>" method="POST" class="kategori-delete-form">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr id="kategoriDatabaseEmpty">
                            <td colspan="3">
                                <div class="kategori-empty">
                                    <i class="bi bi-folder2-open"></i>
                                    <strong>Belum ada kategori</strong>
                                    <span>Tambahkan kategori terlebih dahulu.</span>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <tr id="kategoriSearchEmpty">
                        <td colspan="3">
                            <div class="kategori-empty">
                                <i class="bi bi-search"></i>
                                <strong>Kategori tidak ditemukan</strong>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!--  -->
        <div class="kategori-footer">
            <div class="kategori-info" id="kategoriInfo">
                Menampilkan data kategori
            </div>

            <div class="kategori-pagination" id="kategoriPagination"></div>
        </div>
    </div>
</div>

<!--  -->
<div class="kategori-delete-modal" id="kategoriDeleteModal">
    <div class="kategori-delete-box">
        <div class="kategori-delete-icon">
            <i class="bi bi-trash3"></i>
        </div>

        <h3>Hapus Kategori?</h3>
        <p>Data kategori yang dihapus tidak dapat dikembalikan.</p>

        <div class="kategori-delete-actions">
            <button type="button" id="kategoriCancelDelete" class="kategori-modal-cancel">
                Batal
            </button>

            <button type="button" id="kategoriConfirmDelete" class="kategori-modal-delete">
                Hapus
            </button>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/admin/kategori/index.blade.php ENDPATH**/ ?>
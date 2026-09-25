

<?php $__env->startSection('title', 'Data Buku'); ?>
<?php $__env->startSection('page-title', 'Data Buku'); ?>

<?php $__env->startSection('content'); ?>

<?php echo app('Illuminate\Foundation\Vite')([
    'resources/css/adminBuku.css',
    'resources/js/adminBuku.js'
]); ?>

<div class="buku-page">
    <div class="buku-card">
        <div class="buku-title">
            <h2>Daftar seluruh buku yang tersedia</h2>
        </div>

        <div class="buku-main-actions">
            <a href="<?php echo e(route('admin.buku.create')); ?>" class="buku-btn buku-btn-add">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Buku</span>
            </a>

            <form action="<?php echo e(route('admin.buku.import')); ?>" method="POST" enctype="multipart/form-data" id="importForm" class="buku-import-form">
                <?php echo csrf_field(); ?>
                <input type="file" name="file_excel" id="importFile" accept=".xlsx,.xls,.csv" hidden>

                <button type="button" class="buku-btn buku-btn-import" id="importButton">
                    <i class="bi bi-file-earmark-arrow-up"></i>
                    <span>Import Excel</span>
                </button>
            </form>

            <a href="<?php echo e(route('admin.buku.katalog')); ?>" class="buku-btn buku-btn-catalog">
                <i class="bi bi-grid"></i>
                <span>Katalog Buku</span>
            </a>
        </div>

        <?php if(session('success')): ?>
            <div class="buku-alert success">
                <i class="bi bi-check-circle-fill"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="buku-alert error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?>

        <?php $__errorArgs = ['file_excel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="buku-alert error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span><?php echo e($message); ?></span>
            </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <div class="buku-control-row">
           <div class="buku-show-control">
                <span>Show</span>
                <input type="hidden" id="bukuEntries" value="10">

                <div class="entries-filter-select" data-target="bukuEntries">
                    <button type="button" class="entries-filter-button">
                        <span>10</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="entries-filter-options">
                        <button type="button" data-value="5">5</button>
                        <button type="button" data-value="10" class="active">10</button>
                        <button type="button" data-value="25">25</button>
                        <button type="button" data-value="50">50</button>
                    </div>
                </div>
                <span>entries</span>
            </div>

            <div class="buku-search">
                <i class="bi bi-search"></i>
                <input type="text" id="bukuSearch" placeholder="Cari buku..." autocomplete="off">
            </div>
        </div>

        <div class="buku-table-wrapper">
            <table class="buku-table">
                <thead>
                    <tr>
                        <th class="col-cover">Sampul</th>
                        <th class="col-title">Judul Buku</th>
                        <th class="col-category">Kategori</th>
                        <th class="col-publisher">Penerbit</th>
                        <th class="col-author">Pengarang</th>
                        <th class="col-year">Tahun</th>
                        <th class="col-stock">Total Stok</th>
                        <th class="col-stock">Tersedia</th>
                        <th class="col-action">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $buku; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="buku-row"
                            data-search="<?php echo e(strtolower(
                                $item->judul . ' ' .
                                $item->kategori->nama_kategori . ' ' .
                                $item->penerbit . ' ' .
                                $item->pengarang . ' ' .
                                $item->tahun_terbit
                            )); ?>">

                            <td class="buku-cover-cell">
                                <?php if($item->sampul): ?>
                                    <img src="<?php echo e(asset('storage/' . $item->sampul)); ?>" alt="<?php echo e($item->judul); ?>" class="buku-cover-image">
                                <?php else: ?>
                                    <div class="buku-cover-placeholder">
                                        <i class="bi bi-book"></i>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td class="buku-title-cell">
                                <?php echo e($item->judul); ?>

                            </td>

                            <td class="text-center">
                                <span class="buku-category-badge">
                                    <?php echo e($item->kategori->nama_kategori); ?>

                                </span>
                            </td>

                            <td><?php echo e($item->penerbit); ?></td>
                            <td><?php echo e($item->pengarang); ?></td>

                            <td class="text-center">
                                <?php echo e($item->tahun_terbit); ?>

                            </td>

                            <td class="text-center">
                                <span class="stock-total">
                                    <?php echo e($item->total_stok); ?>

                                </span>
                            </td>

                            <td class="text-center">
                                <?php if($item->stok_tersedia > 0): ?>
                                    <span class="stock-available">
                                        <?php echo e($item->stok_tersedia); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="stock-empty">0</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="buku-actions">
                                    <a href="<?php echo e(route('admin.buku.show', $item)); ?>" class="buku-action-btn view" title="Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>

                                    <a href="<?php echo e(route('admin.buku.edit', $item)); ?>" class="buku-action-btn edit" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <button type="button" class="buku-action-btn delete buku-delete" data-form="deleteBuku<?php echo e($item->id); ?>" title="Hapus">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>

                                <form action="<?php echo e(route('admin.buku.destroy', $item)); ?>" method="POST" id="deleteBuku<?php echo e($item->id); ?>" class="buku-delete-form">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr id="bukuDatabaseEmpty">
                            <td colspan="9">
                                <div class="buku-empty">
                                    <i class="bi bi-journal-x"></i>
                                    <strong>Belum ada data buku</strong>
                                    <span>Silakan tambahkan buku terlebih dahulu.</span>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php if($buku->count() > 0): ?>
                        <tr id="bukuSearchEmpty">
                            <td colspan="9">
                                <div class="buku-empty">
                                    <i class="bi bi-search"></i>
                                    <strong>Buku tidak ditemukan</strong>
                                    <span>Coba gunakan kata kunci lainnya.</span>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="buku-footer">
            <div class="buku-info" id="bukuInfo">
                Menampilkan data buku
            </div>

            <div class="buku-pagination" id="bukuPagination"></div>
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
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/admin/buku/index.blade.php ENDPATH**/ ?>
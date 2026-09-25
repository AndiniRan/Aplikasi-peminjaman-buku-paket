

<?php $__env->startSection('title', 'Edit Buku'); ?>
<?php $__env->startSection('page-title', 'Edit Buku'); ?>

<?php $__env->startSection('content'); ?>

<?php echo app('Illuminate\Foundation\Vite')([
    'resources/css/adminBuku.css',
    'resources/js/adminBuku.js'
]); ?>

<?php
    $selectedKategoriId = old('kategori_id', $buku->kategori_id);
    $selectedKategori = $kategori->firstWhere('id', (int) $selectedKategoriId);
?>

<div class="buku-form-page">
    <div class="buku-form-card">
        <div class="buku-form-header">
            <h2>Edit Buku</h2>
            <p>Silakan ubah data buku pada form di bawah ini</p>
        </div>

        <form action="<?php echo e(route('admin.buku.update', $buku)); ?>" method="POST" enctype="multipart/form-data" class="buku-form">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!--  -->
            <div class="buku-form-row">
                <label for="sampul">Cover</label>

                <div class="buku-field">
                    <?php if($buku->sampul): ?>
                        <div class="buku-current-cover">
                            <img src="<?php echo e(asset('storage/' . $buku->sampul)); ?>" alt="<?php echo e($buku->judul); ?>">

                            <div class="buku-current-cover-info">
                                <span>Cover saat ini</span>
                                <small>Pilih file baru jika ingin mengganti cover.</small>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="buku-file <?php echo e($buku->sampul ? 'has-current-cover' : ''); ?>">
                        <label for="sampul" class="buku-file-button">
                            Pilih File
                        </label>

                        <span id="fileName" class="buku-file-name">
                            Tidak ada file baru yang dipilih
                        </span>

                        <input type="file" id="sampul" name="sampul" accept=".jpg,.jpeg,.png,.webp">
                    </div>

                    <small>Format: JPG, PNG, JPEG, WEBP. Maks. 5MB</small>

                    <?php $__errorArgs = ['sampul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="buku-form-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!--  -->
            <div class="buku-form-row">
                <label for="judul">Judul</label>

                <div class="buku-field">
                    <input type="text" id="judul" name="judul" value="<?php echo e(old('judul', $buku->judul)); ?>" placeholder="Masukan judul buku">

                    <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="buku-form-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!--  -->
            <div class="buku-form-row">
                <label for="kategori_id">Kategori</label>

                <div class="buku-field">
                    <input type="hidden" id="kategori_id" name="kategori_id" value="<?php echo e($selectedKategoriId); ?>">

                    <div class="form-filter-select" data-target="kategori_id">
                        <button type="button" class="form-filter-button">
                            <span>
                                <?php echo e($selectedKategori ? $selectedKategori->nama_kategori : '-- Pilih kategori --'); ?>

                            </span>

                            <i class="bi bi-chevron-down"></i>
                        </button>

                        <div class="form-filter-options">
                            <button type="button" data-value="" class="<?php echo e($selectedKategoriId ? '' : 'active'); ?>">
                                -- Pilih kategori --
                            </button>

                            <?php $__currentLoopData = $kategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button" data-value="<?php echo e($item->id); ?>" class="<?php echo e($selectedKategoriId == $item->id ? 'active' : ''); ?>">
                                    <?php echo e($item->nama_kategori); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <?php $__errorArgs = ['kategori_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="buku-form-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!--  -->
            <div class="buku-form-row">
                <label for="penerbit">Penerbit</label>

                <div class="buku-field">
                    <input type="text" id="penerbit" name="penerbit" value="<?php echo e(old('penerbit', $buku->penerbit)); ?>" placeholder="Masukan penerbit">                   

                    <?php $__errorArgs = ['penerbit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="buku-form-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!--  -->
            <div class="buku-form-row">
                <label for="pengarang">Pengarang</label>

                <div class="buku-field">
                    <input type="text" id="pengarang" name="pengarang" value="<?php echo e(old('pengarang', $buku->pengarang)); ?>" placeholder="Masukan pengarang">                   

                    <?php $__errorArgs = ['pengarang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="buku-form-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!--  -->
            <div class="buku-form-row">
                <label for="tahun_terbit">Tahun Terbit</label>

                <div class="buku-field">
                    <input type="number" id="tahun_terbit" name="tahun_terbit" value="<?php echo e(old('tahun_terbit', $buku->tahun_terbit)); ?>" placeholder="Masukan tahun terbit" min="1900" max="<?php echo e(date('Y') + 1); ?>">

                    <?php $__errorArgs = ['tahun_terbit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="buku-form-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!--  -->
            <div class="buku-form-row">
                <label for="total_stok">Total Stok</label>

                <div class="buku-field">
                    <input type="number" id="total_stok" name="total_stok" value="<?php echo e(old('total_stok', $buku->total_stok)); ?>" placeholder="Masukan total stok" min="0">

                    <small>Stok tersedia saat ini: <?php echo e($buku->stok_tersedia); ?></small>

                    <?php $__errorArgs = ['total_stok'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="buku-form-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!--  -->
            <div class="buku-form-actions">
                <a href="<?php echo e(route('admin.buku.index')); ?>" class="buku-form-cancel">
                    Batal
                </a>

                <button type="submit" class="buku-form-save">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/admin/buku/edit.blade.php ENDPATH**/ ?>
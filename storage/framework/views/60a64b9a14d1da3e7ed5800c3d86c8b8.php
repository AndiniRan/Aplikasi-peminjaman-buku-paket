

<?php $__env->startSection('title', 'Tambah Pengembalian'); ?>
<?php $__env->startSection('page-title', 'Tambah Pengembalian'); ?>

<?php echo app('Illuminate\Foundation\Vite')([
    'resources/css/adminTransaksi.css',
    'resources/js/adminTransaksi.js'
]); ?>

<?php $__env->startSection('content'); ?>
<div class="transaksi-form-page" data-form="pengembalian">
    <div class="transaksi-form-card">
        <div class="transaksi-form-title">
            <h2>Pengembalian Buku</h2>
            <p>Silakan pilih transaksi aktif untuk pengembalian buku</p>
        </div>

        <?php if(session('error')): ?>
            <div class="transaksi-alert transaksi-alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="transaksi-alert transaksi-alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span><?php echo e($errors->first()); ?></span>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('admin.transaksi.pengembalian.store')); ?>" method="POST" class="transaksi-form">
            <?php echo csrf_field(); ?>

            <!--  -->
            <div class="transaksi-form-row">
                <label>Transaksi Aktif</label>
                <div class="transaksi-custom-select" data-form-select data-target="transaksi_id">
                    <button type="button" class="transaksi-custom-select-button" data-form-select-button>
                        <span data-form-select-text>
                            Pilih Transaksi Aktif
                        </span>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="transaksi-custom-select-menu transaksi-return-menu">
                        <?php $__currentLoopData = $transaksiAktif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button type="button"
                                    class="transaksi-custom-select-option
                                    <?php echo e(old('transaksi_id') == $item->transaksi_id ? 'active' : ''); ?>"
                                    data-form-select-option
                                    data-value="<?php echo e($item->transaksi_id); ?>">

                                <span class="transaksi-option-main">
                                    <?php echo e($item->user?->name ?? '-'); ?>

                                </span>

                                <span class="transaksi-option-sub">
                                    <?php echo e($item->buku?->judul ?? '-'); ?>

                                </span>
                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <select name="transaksi_id" id="transaksi_id" class="transaksi-hidden-select" required>
                        <option value="">
                            Pilih Transaksi Aktif
                        </option>

                        <?php $__currentLoopData = $transaksiAktif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->transaksi_id); ?>"
                                    data-member="<?php echo e($item->user?->name ?? '-'); ?>"
                                    data-buku="<?php echo e($item->buku?->judul ?? '-'); ?>"
                                    data-pinjam="<?php echo e($item->tanggal_pinjam?->format('Y-m-d')); ?>"
                                    data-jatuh-tempo="<?php echo e($item->tanggal_jatuh_tempo?->format('Y-m-d')); ?>"
                                    <?php if(old('transaksi_id') == $item->transaksi_id): echo 'selected'; endif; ?>>
                                <?php echo e($item->user?->name ?? '-'); ?>

                                - <?php echo e($item->buku?->judul ?? '-'); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <!--  -->
            <div class="transaksi-form-row">
                <label>Nama Member</label>
                <div class="transaksi-readonly">
                    <input type="text" id="returnMember" value="-" readonly>
                    <span>(readonly)</span>
                </div>
            </div>

            <!--  -->
            <div class="transaksi-form-row">
                <label>Judul Buku</label>
                <div class="transaksi-readonly">
                    <input type="text" id="returnBuku" value="-" readonly>
                    <span>(readonly)</span>
                </div>
            </div>

            <!--  -->
            <div class="transaksi-form-row">
                <label>Tanggal Pinjam</label>
                <div class="transaksi-readonly">
                    <input type="text" id="returnTanggalPinjam" value="-" readonly>
                    <span>(readonly)</span>
                </div>
            </div>

            <!--  -->
            <div class="transaksi-form-row">
                <label>Jatuh Tempo</label>
                <div class="transaksi-readonly">
                    <input type="text" id="returnJatuhTempo" value="-" readonly>
                    <span>(readonly)</span>
                </div>
            </div>

            <!--  -->
            <div class="transaksi-form-row">
                <label>Tanggal Kembali</label>
                <div class="transaksi-custom-date" data-date-picker>
                    <input type="text" class="transaksi-custom-date-display" data-date-display placeholder="DD/MM/YYYY" readonly>
                    <button type="button" class="transaksi-custom-date-button" data-date-button aria-label="Pilih tanggal">
                        <i class="bi bi-calendar3"></i>
                    </button>

                    <input type="hidden" name="tanggal_kembali" id="tanggal_kembali" data-date-input value="<?php echo e(old('tanggal_kembali')); ?>">
                    <div class="transaksi-calendar" data-calendar>
                        <div class="transaksi-calendar-header">
                            <button type="button" class="transaksi-calendar-nav" data-calendar-prev>
                                <i class="bi bi-chevron-left"></i>
                            </button>

                            <div class="transaksi-calendar-title" data-calendar-title>
                            </div>

                            <button type="button" class="transaksi-calendar-nav" data-calendar-next>
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>

                        <div class="transaksi-calendar-weekdays">
                            <span>Min</span>
                            <span>Sen</span>
                            <span>Sel</span>
                            <span>Rab</span>
                            <span>Kam</span>
                            <span>Jum</span>
                            <span>Sab</span>
                        </div>

                        <div class="transaksi-calendar-days" data-calendar-days>
                        </div>
                    </div>
                </div>
            </div>

            <!--  -->
            <div class="transaksi-form-row transaksi-return-status-row">
                <label>Status Pengembalian</label>
                <div class="transaksi-return-status" id="returnStatus">
                    <div class="return-option tepat" id="statusTepat">
                        <div class="return-status-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>

                        <div class="return-status-text">
                            <strong>Tepat Waktu</strong>
                            <span>Dikembalikan sesuai batas waktu</span>
                        </div>
                    </div>

                    <div class="return-option terlambat" id="statusTerlambat">
                        <div class="return-status-icon">
                            <i class="bi bi-exclamation-circle-fill"></i>
                        </div>

                        <div class="return-status-text">
                            <strong>Terlambat</strong>
                            <span>Melewati tanggal jatuh tempo</span>
                        </div>
                    </div>
                </div>
            </div>

            <!--  -->
            <div class="transaksi-form-actions">
                <a href="<?php echo e(route('admin.transaksi.peminjaman.index')); ?>" class="transaksi-form-btn cancel">
                    Batal
                </a>

                <button type="submit" class="transaksi-form-btn save">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/admin/transaksi/pengembalian/create.blade.php ENDPATH**/ ?>
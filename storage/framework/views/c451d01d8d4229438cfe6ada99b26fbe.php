

<?php $__env->startSection('title', 'Laporan Peminjaman'); ?>
<?php $__env->startSection('page-title', 'Laporan Peminjaman'); ?>

<?php echo app('Illuminate\Foundation\Vite')([
    'resources/css/adminLaporan.css',
    'resources/js/adminLaporan.js'
]); ?>

<?php $__env->startSection('content'); ?>

<div class="laporan-page">
    <div class="laporan-card">

        <div class="laporan-title">
            <h2>Laporan Peminjaman</h2>
            <p>Daftar seluruh transaksi peminjaman buku</p>
        </div>

        <form
            action="<?php echo e(route('admin.laporan.peminjaman')); ?>"
            method="GET"
            class="laporan-filter-form"
        >
            <div class="laporan-filter-group">
                <label>Tanggal Awal</label>

                <div class="laporan-date-picker" data-date-picker>
                    <input
                        type="hidden"
                        name="tanggal_awal"
                        value="<?php echo e(request('tanggal_awal')); ?>"
                        data-date-value
                    >

                    <button
                        type="button"
                        class="laporan-date-button"
                        data-date-button
                    >
                        <span data-date-text>Pilih tanggal</span>
                        <i class="bi bi-calendar3"></i>
                    </button>

                    <div class="laporan-calendar">
                        <div class="laporan-calendar-header">
                            <button type="button" data-calendar-prev>
                                <i class="bi bi-chevron-left"></i>
                            </button>

                            <strong data-calendar-title></strong>

                            <button type="button" data-calendar-next>
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>

                        <div class="laporan-calendar-days">
                            <span>Min</span>
                            <span>Sen</span>
                            <span>Sel</span>
                            <span>Rab</span>
                            <span>Kam</span>
                            <span>Jum</span>
                            <span>Sab</span>
                        </div>

                        <div
                            class="laporan-calendar-grid"
                            data-calendar-grid
                        ></div>
                    </div>
                </div>
            </div>

            <div class="laporan-filter-group">
                <label>Tanggal Akhir</label>

                <div class="laporan-date-picker" data-date-picker>
                    <input
                        type="hidden"
                        name="tanggal_akhir"
                        value="<?php echo e(request('tanggal_akhir')); ?>"
                        data-date-value
                    >

                    <button
                        type="button"
                        class="laporan-date-button"
                        data-date-button
                    >
                        <span data-date-text>Pilih tanggal</span>
                        <i class="bi bi-calendar3"></i>
                    </button>

                    <div class="laporan-calendar">
                        <div class="laporan-calendar-header">
                            <button type="button" data-calendar-prev>
                                <i class="bi bi-chevron-left"></i>
                            </button>

                            <strong data-calendar-title></strong>

                            <button type="button" data-calendar-next>
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>

                        <div class="laporan-calendar-days">
                            <span>Min</span>
                            <span>Sen</span>
                            <span>Sel</span>
                            <span>Rab</span>
                            <span>Kam</span>
                            <span>Jum</span>
                            <span>Sab</span>
                        </div>

                        <div
                            class="laporan-calendar-grid"
                            data-calendar-grid
                        ></div>
                    </div>
                </div>
            </div>

            <div class="laporan-filter-actions">
                <button type="submit" class="laporan-btn-filter">
                    <i class="bi bi-search"></i>
                    Tampilkan
                </button>

                <a
                    href="<?php echo e(route('admin.laporan.peminjaman')); ?>"
                    class="laporan-btn-refresh"
                >
                    <i class="bi bi-arrow-clockwise"></i>
                    Refresh
                </a>

                <div class="laporan-export">
                    <button
                        type="button"
                        class="laporan-btn-export"
                        data-export-button
                    >
                        <i class="bi bi-download"></i>
                        Export
                        <i class="bi bi-chevron-down export-chevron"></i>
                    </button>

                    <div class="laporan-export-menu">
                        <a
                            href="<?php echo e(route('admin.laporan.peminjaman.export.excel', request()->query())); ?>"
                            data-export-link
                        >
                            <i class="bi bi-file-earmark-excel"></i>
                            Excel
                        </a>

                        <a
                            href="<?php echo e(route('admin.laporan.peminjaman.export.pdf', request()->query())); ?>"
                            data-export-link
                        >
                            <i class="bi bi-file-earmark-pdf"></i>
                            PDF
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <div class="laporan-control-row">
            <div class="laporan-show-control">
                <span>Show</span>

                <div class="entries-filter-select">
                    <button type="button" class="entries-filter-button">
                        <span class="entries-value">10</span>
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

            <div class="laporan-search">
                <i class="bi bi-search"></i>

                <input
                    type="text"
                    class="laporan-search-input"
                    placeholder="Cari peminjaman..."
                    value="<?php echo e(request('search')); ?>"
                    autocomplete="off"
                >
            </div>
        </div>

        <div class="laporan-table-wrapper">
            <table class="laporan-table peminjaman-table">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th>Member</th>
                        <th>Buku</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Tanggal Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody class="laporan-table-body">
                    <?php $__empty_1 = true; $__currentLoopData = $peminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr
                            class="laporan-row"
                            data-search="
                                <?php echo e($item->user?->nis ?? ''); ?>

                                <?php echo e($item->user?->name ?? ''); ?>

                                <?php echo e($item->buku?->judul ?? ''); ?>

                                <?php echo e($item->status); ?>

                            "
                        >
                            <td class="text-center row-number">
                                <?php echo e($loop->iteration); ?>

                            </td>

                            <td>
                                <strong>
                                    <?php echo e($item->user?->name ?? '-'); ?>

                                </strong>

                                <?php if($item->user?->nis): ?>
                                    <small class="laporan-subtext">
                                        <?php echo e($item->user->nis); ?>

                                    </small>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php echo e($item->buku?->judul ?? '-'); ?>

                            </td>

                            <td class="text-center">
                                <?php echo e($item->pengajuan?->tanggal_pengajuan?->format('d/m/Y') ?? '-'); ?>

                            </td>

                            <td class="text-center">
                                <?php echo e($item->tanggal_pinjam?->format('d/m/Y') ?? '-'); ?>

                            </td>

                            <td class="text-center">
                                <?php echo e($item->tanggal_jatuh_tempo?->format('d/m/Y') ?? '-'); ?>

                            </td>

                            <td class="text-center">
                                <?php if($item->status === 'dikembalikan'): ?>
                                    <span class="laporan-status selesai">
                                        Selesai
                                    </span>
                                <?php elseif($item->status === 'terlambat'): ?>
                                    <span class="laporan-status terlambat">
                                        Terlambat
                                    </span>
                                <?php else: ?>
                                    <span class="laporan-status dipinjam">
                                        Dipinjam
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr class="database-empty-row">
                            <td colspan="7">
                                <div class="laporan-empty">
                                    <i class="bi bi-inbox"></i>
                                    <strong>Belum ada data peminjaman</strong>
                                    <span>Data peminjaman belum tersedia.</span>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="laporan-table-footer">
            <div class="laporan-info">
                Belum ada data peminjaman
            </div>

            <div class="laporan-pagination"></div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/admin/laporan/peminjaman.blade.php ENDPATH**/ ?>
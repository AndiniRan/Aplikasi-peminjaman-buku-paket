

<?php $__env->startSection('title', 'Laporan Member'); ?>
<?php $__env->startSection('page-title', 'Laporan Member'); ?>

<?php echo app('Illuminate\Foundation\Vite')([
    'resources/css/adminLaporan.css',
    'resources/js/adminLaporan.js'
]); ?>

<?php $__env->startSection('content'); ?>

<div class="laporan-page">
    <div class="laporan-card">

        <div class="laporan-title">
            <h2>Laporan Member</h2>
            <p>Daftar seluruh member siswa dan guru perpustakaan</p>
        </div>

        <form
            action="<?php echo e(route('admin.laporan.member')); ?>"
            method="GET"
            class="laporan-filter-form"
        >
            <div class="laporan-filter-group">
                <label>Kelas</label>

                <div class="laporan-filter-select">
                    <input
                        type="hidden"
                        name="kelas"
                        value="<?php echo e(request('kelas')); ?>"
                        class="laporan-filter-input"
                    >

                    <button
                        type="button"
                        class="laporan-filter-button"
                    >
                        <span class="laporan-filter-text">
                            <?php echo e(request('kelas') ?: 'Semua Kelas'); ?>

                        </span>

                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="laporan-filter-options">
                        <button type="button" data-value="">
                            Semua Kelas
                        </button>

                        <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button
                                type="button"
                                data-value="<?php echo e($item); ?>"
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'active' => request('kelas') === $item
                                ]); ?>"
                            >
                                <?php echo e($item); ?>

                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <div class="laporan-filter-group">
                <label>Status</label>

                <div class="laporan-filter-select">
                    <input
                        type="hidden"
                        name="status"
                        value="<?php echo e(request('status')); ?>"
                        class="laporan-filter-input"
                    >

                    <button
                        type="button"
                        class="laporan-filter-button"
                    >
                        <span class="laporan-filter-text">
                            <?php if(request('status') === 'aktif'): ?>
                                Aktif
                            <?php elseif(request('status') === 'nonaktif'): ?>
                                Nonaktif
                            <?php else: ?>
                                Semua Status
                            <?php endif; ?>
                        </span>

                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="laporan-filter-options">
                        <button type="button" data-value="">
                            Semua Status
                        </button>

                        <button
                            type="button"
                            data-value="aktif"
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'active' => request('status') === 'aktif'
                            ]); ?>"
                        >
                            Aktif
                        </button>

                        <button
                            type="button"
                            data-value="nonaktif"
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'active' => request('status') === 'nonaktif'
                            ]); ?>"
                        >
                            Nonaktif
                        </button>
                    </div>
                </div>
            </div>

            <div class="laporan-filter-actions">
                <button type="submit" class="laporan-btn-filter">
                    <i class="bi bi-search"></i>
                    Tampilkan
                </button>

                <a
                    href="<?php echo e(route('admin.laporan.member')); ?>"
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
                            href="<?php echo e(route('admin.laporan.member.export.excel', request()->query())); ?>"
                            data-export-link
                        >
                            <i class="bi bi-file-earmark-excel"></i>
                            Excel
                        </a>

                        <a
                            href="<?php echo e(route('admin.laporan.member.export.pdf', request()->query())); ?>"
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
                    placeholder="Cari member..."
                    value="<?php echo e(request('search')); ?>"
                    autocomplete="off"
                >
            </div>
        </div>

        <div class="laporan-table-wrapper">
            <table class="laporan-table member-table">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th>NIS</th>
                        <th>Nama Lengkap</th>
                        <th>Role</th>
                        <th>Kelas</th>
                        <th>Email</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody class="laporan-table-body">
                    <?php $__empty_1 = true; $__currentLoopData = $member; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr
                            class="laporan-row"
                            data-search="
                                <?php echo e($item->nis); ?>

                                <?php echo e($item->name); ?>

                                <?php echo e($item->role); ?>

                                <?php echo e($item->kelas); ?>

                                <?php echo e($item->email); ?>

                                <?php echo e($item->status); ?>

                            "
                        >
                            <td class="text-center row-number">
                                <?php echo e($loop->iteration); ?>

                            </td>

                            <td>
                                <?php echo e($item->nis ?? '-'); ?>

                            </td>

                            <td>
                                <strong><?php echo e($item->name); ?></strong>
                            </td>

                            <td class="text-center">
                                <?php echo e(ucfirst($item->role)); ?>

                            </td>

                            <td class="text-center">
                                <?php echo e($item->kelas ?? '-'); ?>

                            </td>

                            <td>
                                <?php echo e($item->email ?? '-'); ?>

                            </td>

                            <td class="text-center">
                                <?php if($item->status === 'aktif'): ?>
                                    <span class="laporan-status aktif">
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="laporan-status nonaktif">
                                        Nonaktif
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr class="database-empty-row">
                            <td colspan="7">
                                <div class="laporan-empty">
                                    <i class="bi bi-inbox"></i>
                                    <strong>Belum ada data member</strong>
                                    <span>Data member belum tersedia.</span>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="laporan-table-footer">
            <div class="laporan-info">
                Belum ada data member
            </div>

            <div class="laporan-pagination"></div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/admin/laporan/member.blade.php ENDPATH**/ ?>
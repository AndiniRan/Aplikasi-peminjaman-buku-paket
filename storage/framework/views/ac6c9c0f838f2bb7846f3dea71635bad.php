

<?php $__env->startSection('title', 'Pengajuan Saya'); ?>
<?php $__env->startSection('page-title', 'Pengajuan Saya'); ?>

<?php echo app('Illuminate\Foundation\Vite')([
    'resources/css/memberPengajuan.css',
    'resources/js/memberPengajuan.js'
]); ?>

<?php $__env->startSection('content'); ?>
<div class="pengajuan-page">

    <div class="pengajuan-card">

        <div class="pengajuan-title">
            <h2>Daftar pengajuan peminjaman saya</h2>
        </div>

        <?php if(session('success')): ?>
            <div class="pengajuan-alert success">
                <i class="bi bi-check-circle"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="pengajuan-alert error">
                <i class="bi bi-exclamation-circle"></i>
                <span><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?>

        <form
            action="<?php echo e(route('member.pengajuan.index')); ?>"
            method="GET"
            class="pengajuan-filter-form"
            id="pengajuanStatusForm"
        >

            <div class="pengajuan-filter-group">
                <label>Status</label>

                <div
                    class="pengajuan-filter-select"
                    id="statusFilterDropdown"
                >
                    <input
                        type="hidden"
                        name="status"
                        id="statusFilterInput"
                        value="<?php echo e($statusFilter ?? ''); ?>"
                    >

                    <button
                        type="button"
                        class="pengajuan-filter-button"
                        id="statusFilterButton"
                    >
                        <span id="statusFilterText">
                            Semua
                        </span>

                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="pengajuan-filter-options">
                        <button type="button" data-value="">Semua</button>
                        <button type="button" data-value="menunggu">Menunggu</button>
                        <button type="button" data-value="siap">Siap</button>
                        <button type="button" data-value="disetujui">Disetujui</button>
                        <button type="button" data-value="ditolak">Ditolak</button>
                    </div>
                </div>
            </div>

            <div class="pengajuan-filter-actions">
                <a
                    href="<?php echo e(route('member.pengajuan.index')); ?>"
                    class="pengajuan-btn-refresh"
                >
                    <i class="bi bi-arrow-clockwise"></i>
                    Refresh
                </a>
            </div>

        </form>

        <div class="pengajuan-control-row">

            <div class="pengajuan-show-control">
                <span>Show</span>

                <div
                    class="entries-filter-select"
                    id="entriesDropdown"
                >
                    <button
                        type="button"
                        class="entries-filter-button"
                        id="entriesButton"
                    >
                        <span id="entriesValue">10</span>
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

            <div class="pengajuan-search">
                <i class="bi bi-search"></i>

                <input
                    type="text"
                    id="pengajuanSearch"
                    placeholder="Cari pengajuan..."
                    autocomplete="off"
                >
            </div>

        </div>

        <div class="pengajuan-table-wrapper">

            <table class="pengajuan-table">

                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-tanggal">Tanggal Pengajuan</th>
                        <th class="col-tanggal">Tanggal Disiapkan</th>
                        <th class="col-tanggal">Batas Pengambilan</th>
                        <th class="col-tanggal">Tanggal Diambil</th>
                        <th class="col-nama">Nama Lengkap</th>
                        <th class="col-kelas">Kelas</th>
                        <th class="col-buku">Judul Buku</th>
                        <th class="col-status">Status</th>
                        <th class="col-aksi">Aksi</th>
                    </tr>
                </thead>

                <tbody id="pengajuanTableBody">

                    <?php $__empty_1 = true; $__currentLoopData = $pengajuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr
                            class="pengajuan-row"

                            data-search="
                                <?php echo e($item->user->name ?? ''); ?>

                                <?php echo e($item->user->kelas ?? ''); ?>

                                <?php echo e($item->buku->judul ?? ''); ?>

                                <?php echo e($item->status); ?>

                            "

                            data-id="<?php echo e($item->pengajuan_id); ?>"
                            data-nama="<?php echo e($item->user->name ?? '-'); ?>"
                            data-kelas="<?php echo e($item->user->kelas ?? '-'); ?>"

                            data-buku="<?php echo e($item->buku->judul ?? '-'); ?>"
                            data-penerbit="<?php echo e($item->buku->penerbit ?? '-'); ?>"
                            data-pengarang="<?php echo e($item->buku->pengarang ?? '-'); ?>"

                            data-kategori="<?php echo e($item->buku?->kategori?->nama_kategori ?? '-'); ?>"

                            <?php if($item->buku && $item->buku->sampul): ?>
                                data-cover="<?php echo e(asset('storage/' . $item->buku->sampul)); ?>"
                            <?php else: ?>
                                data-cover=""
                            <?php endif; ?>

                            data-status="<?php echo e($item->status); ?>"

                            data-tanggal-pengajuan="<?php echo e($item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y H:i') : '-'); ?>"
                            data-tanggal-disiapkan="<?php echo e($item->tanggal_disiapkan ? $item->tanggal_disiapkan->format('d/m/Y H:i') : ''); ?>"
                            data-batas-pengambilan="<?php echo e($item->batas_pengambilan ? $item->batas_pengambilan->format('d/m/Y H:i') : ''); ?>"
                            data-tanggal-diambil="<?php echo e($item->tanggal_diambil ? $item->tanggal_diambil->format('d/m/Y H:i') : ''); ?>"

                            data-alasan="<?php echo e($item->alasan_ditolak ?? ''); ?>"
                        >

                            <td class="text-center row-number">
                                <?php echo e($loop->iteration); ?>

                            </td>

                            <td class="text-center">
                                <?php echo e($item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y') : '-'); ?>

                            </td>

                            <td class="text-center">
                                <?php echo e($item->tanggal_disiapkan ? $item->tanggal_disiapkan->format('d/m/Y') : '-'); ?>

                            </td>

                            <td class="text-center">
                                <?php echo e($item->batas_pengambilan ? $item->batas_pengambilan->format('d/m/Y') : '-'); ?>

                            </td>

                            <td class="text-center">
                                <?php echo e($item->tanggal_diambil ? $item->tanggal_diambil->format('d/m/Y') : '-'); ?>

                            </td>

                            <td class="pengajuan-name-cell">
                                <?php echo e($item->user->name ?? '-'); ?>

                            </td>

                            <td class="text-center">
                                <?php echo e($item->user->kelas ?? '-'); ?>

                            </td>

                            <td class="pengajuan-book-cell">
                                <?php echo e($item->buku->judul ?? '-'); ?>

                            </td>

                            <td class="text-center">

                                <?php if($item->status === 'menunggu'): ?>
                                    <span class="pengajuan-status menunggu">
                                        Menunggu
                                    </span>
                                <?php elseif($item->status === 'siap'): ?>
                                    <span class="pengajuan-status siap">
                                        Siap
                                    </span>
                                <?php elseif($item->status === 'disetujui'): ?>
                                    <span class="pengajuan-status disetujui">
                                        Disetujui
                                    </span>
                                <?php elseif($item->status === 'ditolak'): ?>
                                    <span class="pengajuan-status ditolak">
                                        Ditolak
                                    </span>
                                <?php endif; ?>

                            </td>

                            <td>
                                <div class="pengajuan-actions">

                                    <button
                                        type="button"
                                        class="pengajuan-action-btn view btn-detail-pengajuan"
                                        title="Detail"
                                    >
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                </div>
                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr id="pengajuan-empty-row">
                            <td colspan="10">

                                <div class="pengajuan-empty">
                                    <i class="bi bi-inbox"></i>

                                    <strong>
                                        Belum ada data pengajuan peminjaman
                                    </strong>

                                    <span>
                                        Kamu belum mengajukan peminjaman buku
                                    </span>
                                </div>

                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

        <div class="pengajuan-table-footer">

            <div
                class="pengajuan-info"
                id="pengajuanInfo"
            >
                Belum ada data pengajuan
            </div>

            <div
                class="pengajuan-pagination"
                id="pengajuanPagination"
            ></div>

        </div>

    </div>

</div>



<div
    class="pengajuan-modal"
    id="pengajuanModal"
    aria-hidden="true"
>

    <div class="pengajuan-modal-overlay"></div>

    <div class="pengajuan-modal-dialog">

        <div class="pengajuan-modal-header">

            <h3>
                Detail Pengajuan Peminjaman
            </h3>

            <button
                type="button"
                class="pengajuan-modal-close"
                id="closePengajuanModal"
            >
                <i class="bi bi-x-lg"></i>
            </button>

        </div>


        <div class="pengajuan-modal-body">

            <div class="pengajuan-detail-layout">

                
                <div class="pengajuan-book-preview">

                    <h4 class="pengajuan-book-label">
                        Buku
                    </h4>

                    <div class="pengajuan-cover-wrapper">

                        <img
                            src=""
                            alt="Sampul Buku"
                            id="detailCover"
                        >

                        <div
                            class="pengajuan-cover-placeholder"
                            id="detailCoverPlaceholder"
                        >
                            <i class="bi bi-book"></i>
                        </div>

                    </div>

                    <h4 id="detailJudulBuku">
                        -
                    </h4>

                    <div class="pengajuan-book-meta">

                        <div>
                            <span>Penerbit</span>
                            <b>:</b>
                            <span id="detailPenerbit">-</span>
                        </div>

                        <div>
                            <span>Kategori</span>
                            <b>:</b>
                            <span id="detailKategori">-</span>
                        </div>

                    </div>

                    <span
                        id="detailPengarang"
                        hidden
                    ></span>

                </div>


                
                <div class="pengajuan-detail-content">

                    <div class="pengajuan-detail-list">

                        <div class="pengajuan-detail-row">
                            <i class="bi bi-calendar3"></i>

                            <span class="pengajuan-detail-label">
                                Tanggal Pengajuan
                            </span>

                            <span class="pengajuan-detail-separator">
                                :
                            </span>

                            <span
                                class="pengajuan-detail-value"
                                id="detailTanggalPengajuan"
                            >
                                -
                            </span>
                        </div>


                        <div
                            class="pengajuan-detail-row"
                            id="rowTanggalDisiapkan"
                        >
                            <i class="bi bi-calendar3"></i>

                            <span class="pengajuan-detail-label">
                                Tanggal Disiapkan
                            </span>

                            <span class="pengajuan-detail-separator">
                                :
                            </span>

                            <span
                                class="pengajuan-detail-value"
                                id="detailTanggalDisiapkan"
                            >
                                -
                            </span>
                        </div>


                        <div
                            class="pengajuan-detail-row"
                            id="rowBatasPengambilan"
                        >
                            <i class="bi bi-calendar3"></i>

                            <span class="pengajuan-detail-label">
                                Batas Pengambilan
                            </span>

                            <span class="pengajuan-detail-separator">
                                :
                            </span>

                            <span
                                class="pengajuan-detail-value"
                                id="detailBatasPengambilan"
                            >
                                -
                            </span>
                        </div>


                        <div
                            class="pengajuan-detail-row"
                            id="rowTanggalDiambil"
                        >
                            <i class="bi bi-calendar3"></i>

                            <span class="pengajuan-detail-label">
                                Tanggal Diambil
                            </span>

                            <span class="pengajuan-detail-separator">
                                :
                            </span>

                            <span
                                class="pengajuan-detail-value"
                                id="detailTanggalDiambil"
                            >
                                -
                            </span>
                        </div>


                        <div class="pengajuan-detail-row">
                            <i class="bi bi-person"></i>

                            <span class="pengajuan-detail-label">
                                Nama Lengkap
                            </span>

                            <span class="pengajuan-detail-separator">
                                :
                            </span>

                            <span
                                class="pengajuan-detail-value"
                                id="detailNama"
                            >
                                -
                            </span>
                        </div>


                        <div class="pengajuan-detail-row">
                            <i class="bi bi-star-fill"></i>

                            <span class="pengajuan-detail-label">
                                Kelas
                            </span>

                            <span class="pengajuan-detail-separator">
                                :
                            </span>

                            <span
                                class="pengajuan-detail-value"
                                id="detailKelas"
                            >
                                -
                            </span>
                        </div>


                        <div class="pengajuan-detail-row">
                            <i class="bi bi-bullseye"></i>

                            <span class="pengajuan-detail-label">
                                Status
                            </span>

                            <span class="pengajuan-detail-separator">
                                :
                            </span>

                            <span class="pengajuan-detail-value">

                                <span
                                    class="pengajuan-status"
                                    id="detailStatus"
                                >
                                    -
                                </span>

                            </span>
                        </div>

                    </div>


                    
                    <div
                        class="pengajuan-state-box"
                        id="sectionMenunggu"
                    >
                        <div class="pengajuan-state-title">
                            <i class="bi bi-clock"></i>

                            <span>
                                Menunggu Konfirmasi
                            </span>
                        </div>

                        <p>
                            Pengajuan ini menunggu konfirmasi dari petugas
                        </p>
                    </div>


                    
                    <div
                        class="pengajuan-state-box"
                        id="sectionSiap"
                    >
                        <div class="pengajuan-state-title">
                            <i class="bi bi-check-circle"></i>

                            <span>
                                Buku Siap Diambil
                            </span>
                        </div>

                        <p>
                            Pengajuan telah dikonfirmasi.<br>
                            Silahkan ambil buku di perpustakaan
                        </p>
                    </div>


                    
                    <div
                        class="pengajuan-state-box"
                        id="sectionDisetujui"
                    >
                        <div class="pengajuan-state-title">
                            <i class="bi bi-check-circle-fill"></i>

                            <span>
                                Pengajuan Disetujui
                            </span>
                        </div>

                        <p>
                            Pengajuan ini telah disetujui
                        </p>
                    </div>


                    
                    <div
                        class="pengajuan-state-box"
                        id="sectionDitolak"
                    >
                        <div class="pengajuan-reject-title">
                            <i class="bi bi-exclamation-circle-fill"></i>

                            <span>
                                Alasan Ditolak
                            </span>
                        </div>

                        <div
                            class="pengajuan-reject-result"
                            id="detailAlasanDitolak"
                        >
                            -
                        </div>
                    </div>

                </div>

            </div>

        </div>


        <div class="pengajuan-modal-footer">

            <button
                type="button"
                class="pengajuan-modal-btn close"
                id="closePengajuanModalFooter"
            >
                Tutup
            </button>

        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/member/pengajuan/index.blade.php ENDPATH**/ ?>
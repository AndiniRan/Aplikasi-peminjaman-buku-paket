<aside class="admin-sidebar" id="adminSidebar">

    <!-- BRAND -->
    <div class="sidebar-brand">
        <img src="<?php echo e(asset('images/logo-perpustakaan.png')); ?>" alt="Logo Perpustakaan" class="sidebar-logo">

        <div class="sidebar-brand-text">
            <strong>Perpustakaan</strong>
            <span>Peminjaman Buku Paket</span>
        </div>
    </div>
    <div class="sidebar-divider"></div>

    <!-- ADMIN MENU -->
    <nav class="sidebar-navigation">
        <?php if(Auth::user()->role === 'admin'): ?>
            <!--  -->
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-house-door-fill"></i>
                <span>Dashboard</span>
            </a>

            <!--  -->
            <a href="<?php echo e(route('admin.kategori.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.kategori.*') ? 'active' : ''); ?>">
                <i class="bi bi-diagram-3"></i>
                <span>Kelola Kategori</span>
            </a>

            <!--  -->
            <a href="<?php echo e(route('admin.buku.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.buku.*') ? 'active' : ''); ?>">
                <i class="bi bi-book"></i>
                <span>Katalog Buku</span>
            </a>

            <!-- MEMBER -->
            <div class="sidebar-dropdown <?php echo e(request()->routeIs('admin.member.*') ? 'open' : ''); ?>">
                <button type="button" class="sidebar-link sidebar-dropdown-toggle">
                    <span class="sidebar-link-left">
                        <i class="bi bi-people-fill"></i>
                        <span>Kelola Member</span>
                    </span>

                    <i class="bi bi-chevron-down sidebar-chevron"></i>
                </button>

                <div class="sidebar-submenu">
                    <a href="<?php echo e(route('admin.member.guru.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.member.guru.*') ? 'active' : ''); ?>">
                        <i class="bi bi-person-badge"></i>
                        <span>Data Guru</span>
                    </a>

                    <a href="<?php echo e(route('admin.member.siswa.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.member.siswa.*') ? 'active' : ''); ?>">
                        <i class="bi bi-person-vcard"></i>
                        <span>Data Siswa</span>
                    </a>
                </div>
            </div>

            <!-- PENGAJUAN -->
            <a href="<?php echo e(route('admin.pengajuan.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.pengajuan.*') ? 'active' : ''); ?>">
                <i class="bi bi-arrows-fullscreen"></i>
                <span>Pengajuan Pinjaman</span>
            </a>

            <!-- TRANSAKSI -->
            <div class="sidebar-dropdown <?php echo e(request()->routeIs('admin.transaksi.*') ? 'open' : ''); ?>">
                <button type="button" class="sidebar-link sidebar-dropdown-toggle">
                    <span class="sidebar-link-left">
                        <i class="bi bi-arrow-left-right"></i>
                        <span>Transaksi</span>
                    </span>

                    <i class="bi bi-chevron-down sidebar-chevron"></i>
                </button>

                <div class="sidebar-submenu">
                    <a href="<?php echo e(route('admin.transaksi.peminjaman.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.transaksi.peminjaman.*') ? 'active' : ''); ?>">
                        <i class="bi bi-box-arrow-up-right"></i>
                        <span>Peminjaman</span>
                    </a>

                    <a href="<?php echo e(route('admin.transaksi.pengembalian.create')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.transaksi.pengembalian.*') ? 'active' : ''); ?>">
                        <i class="bi bi-box-arrow-in-down-left"></i>
                        <span>Pengembalian</span>
                    </a>
                </div>
            </div>

            <!-- LAPORAN -->
            <div class="sidebar-dropdown <?php echo e(request()->routeIs('admin.laporan.*') ? 'open' : ''); ?>">
                <button type="button" class="sidebar-link sidebar-dropdown-toggle">
                    <span class="sidebar-link-left">
                        <i class="bi bi-pie-chart"></i>
                        <span>Laporan</span>
                    </span>

                    <i class="bi bi-chevron-down sidebar-chevron"></i>
                </button>

                <div class="sidebar-submenu">
                    <a href="<?php echo e(route('admin.laporan.peminjaman')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.laporan.peminjaman') ? 'active' : ''); ?>">
                        <i class="bi bi-journal-arrow-up"></i>
                        <span>Peminjaman</span>
                    </a>

                    <a href="<?php echo e(route('admin.laporan.pengembalian')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.laporan.pengembalian') ? 'active' : ''); ?>">
                        <i class="bi bi-journal-arrow-down"></i>
                        <span>Pengembalian</span>
                    </a>

                    <a href="<?php echo e(route('admin.laporan.buku')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.laporan.buku') ? 'active' : ''); ?>">
                        <i class="bi bi-book"></i>
                        <span>Buku</span>
                    </a>

                    <a href="<?php echo e(route('admin.laporan.member')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.laporan.member') ? 'active' : ''); ?>">
                        <i class="bi bi-people"></i>
                        <span>Member</span>
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- MEMBER (GURU & SISWA) MENU -->
        <?php if(in_array(Auth::user()->role, ['guru', 'siswa'])): ?>
            <a href="#" class="sidebar-link">
                <i class="bi bi-house-door-fill"></i>
                <span>Dashboard</span>
            </a>

            <a href="#" class="sidebar-link">
                <i class="bi bi-book"></i>
                <span>Katalog Buku</span>
            </a>

            <a href="#" class="sidebar-link">
                <i class="bi bi-arrows-fullscreen"></i>
                <span>Pengajuan Pinjaman</span>
            </a>

            <a href="#" class="sidebar-link">
                <i class="bi bi-journal-check"></i>
                <span>Peminjaman Saya</span>
            </a>

            <a href="#" class="sidebar-link">
                <i class="bi bi-clock-history"></i>
                <span>Riwayat Peminjaman</span>
            </a>
        <?php endif; ?>

        <!--  -->
        <a  href="<?php echo e(route('admin.profile.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.profile.*') ? 'active' : ''); ?>">
            <i class="bi bi-person"></i>
            <span>Profil</span>
        </a>
    </nav>

    <!-- LOGOUT -->
    <div class="sidebar-footer">
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="sidebar-link sidebar-logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>
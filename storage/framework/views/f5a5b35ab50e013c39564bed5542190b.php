<header class="admin-topbar">
    <!-- LEFT -->
    <div class="topbar-left">
        <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>

        <h1 class="topbar-page-title">
            <?php echo $__env->yieldContent('page-title', 'Dashboard'); ?>
        </h1>
    </div>

    <!-- PROFILE -->
    <div class="topbar-profile-wrapper">
        <button type="button" class="topbar-profile" id="topbarProfileButton">

            <div class="topbar-avatar">
                <?php if(Auth::user()->foto): ?>
                    <img src="<?php echo e(asset('storage/' . Auth::user()->foto)); ?>" alt="Foto <?php echo e(Auth::user()->name); ?>">
                <?php else: ?>
                    <div class="topbar-avatar-default">
                        <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                    </div>
                <?php endif; ?>
            </div>

            <div class="topbar-profile-text">
                <strong><?php echo e(Auth::user()->name); ?></strong>
                <span><?php echo e(ucfirst(Auth::user()->role)); ?></span>
            </div>

            <i class="bi bi-chevron-down topbar-chevron"></i>
        </button>

        <!-- DROPDOWN -->
        <div class="profile-dropdown" id="profileDropdown">
            <a  href="<?php echo e(route('admin.profile.index')); ?>">
                <i class="bi bi-person"></i>
                <span>Profil</span>
            </a>

            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>

                <button type="submit">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</header><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/layouts/topbar.blade.php ENDPATH**/ ?>
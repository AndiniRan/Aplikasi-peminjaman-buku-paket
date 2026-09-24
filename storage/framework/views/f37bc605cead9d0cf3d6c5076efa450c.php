<nav x-data="{ open: false }" class="library-navbar">
    <div class="library-container">
        <div class="navbar-inner">
            <!--  -->
            <div class="navbar-left">
                <div class="library-logo-wrapper">
                    <a href="<?php echo e(url('/')); ?>" class="library-logo">
                        <div class="logo-book">
                            <img src="<?php echo e(asset('images/logo-perpustakaan.png')); ?>" alt="Logo Perpustakaan">
                        </div>
                        <span>Perpustakaan</span>
                    </a>
                </div>
            </div>

            <!--  -->
            <?php if(request()->routeIs('login') === false): ?>
                <div class="library-login-wrapper">
                    <a href="<?php echo e(route('login')); ?>" class="login-button">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Login</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>
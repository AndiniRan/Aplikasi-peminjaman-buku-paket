<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e($title ?? 'Perpustakaan SMPN 69 Jakarta'); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/landing.css', 'resources/js/landing.js']); ?>
    </head>

    <body>
        <div class="min-h-screen">
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <!-- Page Heading -->
            <?php if (! empty(trim($__env->yieldContent('header')))): ?>
                <header class="page-header">
                    <div class="library-container">
                        <?php echo $__env->yieldContent('header'); ?>
                    </div>
                </header>
            <?php endif; ?>
            <!-- Page Content -->
            <main>
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </body>
</html><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/layouts/app.blade.php ENDPATH**/ ?>
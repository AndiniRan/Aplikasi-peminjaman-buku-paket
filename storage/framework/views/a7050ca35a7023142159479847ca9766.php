<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>
        <?php echo $__env->yieldContent('title', 'Perpustakaan SMPN 69 Jakarta'); ?>
    </title>

    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">


    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">


    
    <?php echo app('Illuminate\Foundation\Vite')([
        'resources/css/adminSidebar.css',
        'resources/css/adminTopbar.css',
        'resources/css/adminDashboard.css'
    ]); ?>
</head>

<body>
    <div class="dashboard-layout">
        
        <?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <div class="dashboard-main">
            
            <?php echo $__env->make('layouts.topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <main class="dashboard-content">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    
    <?php echo app('Illuminate\Foundation\Vite')([
        'resources/js/adminSidebar.js',
        'resources/js/adminTopbar.js',
        'resources/js/adminDashboard.js'
    ]); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/layouts/dashboard.blade.php ENDPATH**/ ?>
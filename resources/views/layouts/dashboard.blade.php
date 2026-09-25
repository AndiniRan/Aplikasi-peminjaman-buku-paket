<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Perpustakaan SMPN 69 Jakarta')
    </title>

    {{-- FONT --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">


    {{-- BOOTSTRAP ICONS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">


    {{-- CSS GLOBAL DASHBOARD --}}
    @vite([
        'resources/css/adminSidebar.css',
        'resources/css/adminTopbar.css'
    ])
</head>

<body>
    <div class="dashboard-layout">
        {{-- SIDEBAR --}}
        @include('layouts.sidebar')

        {{-- BAGIAN KANAN --}}
        <div class="dashboard-main">
            {{-- TOPBAR --}}
            @include('layouts.topbar')

            {{-- CONTENT --}}
            <main class="dashboard-content">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- CHART JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- JS DASHBOARD --}}
    @vite([
        'resources/js/adminSidebar.js',
        'resources/js/adminTopbar.js'
    ])
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Perpustakaan SMPN 69 Jakarta' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/landing.css', 'resources/js/landing.js'])
    </head>

    <body>
        <div class="min-h-screen">
            @include('layouts.navigation')
            <!-- Page Heading -->
            @hasSection('header')
                <header class="page-header">
                    <div class="library-container">
                        @yield('header')
                    </div>
                </header>
            @endif
            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
    </body>
</html>
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',

                'resources/css/landing.css',
                'resources/js/landing.js',

                'resources/css/landingKatalog.css',
                'resources/js/landingKatalog.js',

                'resources/css/login.css',

                'resources/css/adminDashboard.css',
                'resources/css/adminSidebar.css',
                'resources/css/adminTopbar.css',

                'resources/js/adminDashboard.js',
                'resources/js/adminSidebar.js',
                'resources/js/adminTopbar.js',

                'resources/css/adminKategori.css',
                'resources/js/adminKategori.js',

                'resources/css/adminBuku.css',
                'resources/js/adminBuku.js',

                'resources/css/adminMember.css',
                'resources/js/adminMember.js',

                'resources/css/adminPengajuan.css',
                'resources/js/adminPengajuan.js',

                'resources/css/adminTransaksi.css',
                'resources/js/adminTransaksi.js',
            ],

            refresh: true,
        }),
    ],
});
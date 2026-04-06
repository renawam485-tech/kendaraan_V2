import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',     // Mengizinkan akses dari jaringan mana pun
        port: 5173,           // Pastikan portnya sesuai
        strictPort: true,     // Memaksa menggunakan port 5173
        hmr: {
            host: 'localhost', // Hot Module Replacement tetap berjalan di local
        },
    },
});
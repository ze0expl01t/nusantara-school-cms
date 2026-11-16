import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import compression from 'vite-plugin-compression';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),

        // ═══ Gzip compression (.gz) ═══
        compression({
            algorithm: 'gzip',
            ext: '.gz',
            threshold: 1024,
            deleteOriginFile: false,
        }),

        // ═══ Brotli compression (.br) ═══
        compression({
            algorithm: 'brotliCompress',
            ext: '.br',
            threshold: 1024,
            deleteOriginFile: false,
            compressionOptions: {
                level: 11,
            },
        }),
    ],

    build: {
        sourcemap: false,
        chunkSizeWarningLimit: 500,
        // ⚠️ JANGAN set minify — Rolldown sudah minify default dengan Oxc
    },
});

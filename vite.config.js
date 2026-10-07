import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        manifest: true,
        cssCodeSplit: true,
        minify: 'esbuild',
        rollupOptions: {
            output: { manualChunks: { vendor: ['axios', 'bootstrap'] } }
        }
    },
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});

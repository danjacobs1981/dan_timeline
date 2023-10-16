import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        //assetsInlineLimit: 0
    },
    plugins: [
        laravel({
            input: [
                '/resources/js/*.js',
                '/resources/css/*.css'
            ],
            refresh: true,
        }),
    ],
});
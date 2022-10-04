import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/global.scss', /* imports modal.css */
                'resources/css/web.scss',
                'resources/css/timeline.scss',
                'resources/css/map.scss',
                'resources/css/comments.scss',
                'resources/css/filters.scss',
                'resources/css/portal.scss',
                'resources/js/global.js',
                'resources/js/web.js',
                'resources/js/timeline.js',
                'resources/js/map.js',
                'resources/js/comments.js',
                'resources/js/filters.js',
                'resources/js/portal.js',
                //'resources/js/modal.js'
            ],
            refresh: true,
        }),
    ],
});
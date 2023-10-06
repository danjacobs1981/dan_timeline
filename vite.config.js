import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        assetsInlineLimit: 0
    },
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
                'resources/css/portal/edit.scss',
                'resources/css/resource/form.scss',
                'resources/css/plugin/tagify.css',
                'resources/js/global.js', /* imports jquery & jquery-modal */
                'resources/js/web/scripts.js',
                'resources/js/timeline/scripts.js',
                'resources/js/timeline/map.js', /* imports jquery-resizable-dom */
                'resources/js/timeline/comments.js',
                'resources/js/timeline/filters.js',
                'resources/js/portal/scripts.js',
                'resources/js/portal/timeline/edit.js',
                'resources/js/portal/timeline/ajax/settings.js',
                'resources/js/portal/timeline/ajax/privacy.js',
                'resources/js/portal/timeline/ajax/privacy-share.js',
            ],
            refresh: true,
        }),
    ],
});
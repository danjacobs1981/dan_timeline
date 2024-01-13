import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        //assetsInlineLimit: 0
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
                'resources/css/portal/timeline/edit.scss',
                'resources/css/portal/timeline/event/create-edit.scss',
                'resources/css/portal/timeline/group/create-edit.scss',
                'resources/css/portal/timeline/tag/create-edit.scss',
                'resources/css/portal/timeline/source/create-edit.scss',
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
                'resources/js/portal/timeline/settings.js',
                'resources/js/portal/timeline/privacy.js',
                'resources/js/portal/timeline/privacy-share.js',
                'resources/js/portal/timeline/event/create-edit.js',
                'resources/js/portal/timeline/event/edit-date-location.js',
                'resources/js/portal/timeline/event/form-date-location.js',
                'resources/js/portal/timeline/event/delete.js',
                'resources/js/portal/timeline/group/create-edit.js',
                'resources/js/portal/timeline/group/delete.js',
                'resources/js/portal/timeline/tag/create-edit.js',
                'resources/js/portal/timeline/tag/delete.js',
                'resources/js/portal/timeline/source/create-edit.js',
                'resources/js/portal/timeline/source/delete.js',
            ],
            refresh: true,
        }),
    ],
});
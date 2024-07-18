import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/admin/scss/admin.scss',
                'resources/admin/js/admin.js',
                'resources/agency/scss/app.scss',
                'resources/agency/js/app.js',
            ],
            refresh: true,
        }),
    ]
});

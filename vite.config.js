import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/vendor/filament-grapesjs-v3/css/grapes.min.css',
                'resources/vendor/filament-grapesjs-v3/js/grapes.min.js'
            ],
            refresh: true,
        }),
    ],
});

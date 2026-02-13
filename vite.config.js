import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/inline-editor.css',
                'resources/js/inline-editor.js',
                'resources/js/load-saved-content.js',
            ],
            refresh: true,
        }),
    ],
});

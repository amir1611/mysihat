import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        // Legacy pages reference images already stored in public/build/assets.
        // Do not clear those files when generating Vite's hashed bundles.
        emptyOutDir: false,
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

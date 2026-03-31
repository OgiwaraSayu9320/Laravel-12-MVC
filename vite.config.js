import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
// import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({

            input: [
                // Cho Web (User)
                'resources/css/app.css',
                'resources/js/app.js',

                // Cho Admin
                'resources/css/admin.css',
                'resources/js/admin.js',],
            refresh: true,
        }),
        // tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});

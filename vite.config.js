import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/presentacion.css',
                'resources/js/presentacion.js',
                'resources/css/podio.css',
                'resources/js/podio.js',
                'resources/css/resultados.css',
                'resources/js/resultados.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});

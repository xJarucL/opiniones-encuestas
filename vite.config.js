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
                'resources/js/presentacion.css',
                'resources/css/podio.css',
                'resources/css/resultados.css',
                'resources/js/resultados.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});

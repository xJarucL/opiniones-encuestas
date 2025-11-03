import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            // input: ['resources/css/app.css', 'resources/js/app.js'],
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/presentacion.css',
                'resources/js/presentacion.js',
                'resources/css/podio.css',
                'resources/js/podio.js',
                'resources/css/resultados.css',
                'resources/js/resultados.js',
                'resources/css/presentaciontwo.css',
                'resources/js/presentaciontwo.js',
                'resources/css/resultadostwo.css',
                'resources/js/resultadostwo.js',
                'resources/js/presentacionone.js',
                'resources/js/podiotwo.js',
                'resources/css/podiotwo.css',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});

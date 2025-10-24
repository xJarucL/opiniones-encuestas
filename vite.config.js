import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
<<<<<<< HEAD
            input: ['resources/css/app.css', 'resources/js/app.js'],
=======
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
>>>>>>> 8a973229b19be6e721f08ee229379f47b2942e90
            refresh: true,
        }),
        tailwindcss(),
    ],
});

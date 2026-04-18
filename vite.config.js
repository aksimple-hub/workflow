import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0', // Permite conexiones externas (desde fuera de Docker)
        hmr: {
            host: 'localhost', // Le dice al navegador dónde buscar los cambios
        },
        watch: {
            usePolling: true, // Crucial en Windows/Docker para detectar cambios en archivos
        },
    },
});

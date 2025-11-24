// vite.config.js

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/VehicleInstantSearch.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '127.0.0.1', // 🔑 FIX: Forces Vite to use IPv4 only, solving CORS/connection issues.
        proxy: {
            '/api': 'http://127.0.0.1:8000',
        },
        hmr: {
            host: 'localhost', // Ensures HMR uses a simple hostname for reliability
            protocol: 'ws',
        },
        watch: {
            usePolling: true,
            interval: 100,
        },
    },
});

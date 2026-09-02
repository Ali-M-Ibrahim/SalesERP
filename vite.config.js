import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),

        VitePWA({
            registerType: 'autoUpdate',

            includeAssets: [
                'favicon.ico',
                'apple-touch-icon.png',
            ],

            manifest: {
                name: 'Sales ERP',
                short_name: 'Sales ERP',

                description:
                    'Customer and sales management application',

                theme_color: '#1E4B43',

                background_color: '#F5F4EF',

                display: 'standalone',

                start_url: '/',

                scope: '/',

                icons: [
                    {
                        src: '/pwa/pwa-192x192.png',
                        sizes: '192x192',
                        type: 'image/png',
                    },
                    {
                        src: '/pwa/pwa-512x512.png',
                        sizes: '512x512',
                        type: 'image/png',
                    },
                    {
                        src: '/pwa/icon-512-maskable.png',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'maskable',
                    },
                ],
                screenshots: [
                    {
                        src: '/screenshots/desktop-dashboard.png',
                        sizes: '1280x720',
                        type: 'image/png',
                        form_factor: 'wide',
                        label: 'Sales ERP Dashboard',
                    },
                    {
                        src: '/screenshots/mobile-dashboard.png',
                        sizes: '390x844',
                        type: 'image/png',
                        label: 'Sales ERP Mobile Dashboard',
                    },
                ],
            },

            workbox: {
                cleanupOutdatedCaches: true,

                navigateFallback: null,
            },
        }),
    ],
});

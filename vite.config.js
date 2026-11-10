import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';
import path from 'node:path';

function inertiaDevSsr() {
    return {
        name: 'inertia-dev-ssr',
        configureServer(server) {
            server.middlewares.use('/__inertia_ssr', (req, res, next) => {
                if (req.method !== 'POST') {
                    next();
                    return;
                }

                let body = '';
                req.setEncoding('utf8');
                req.on('data', (chunk) => {
                    body += chunk;
                });
                req.on('end', async () => {
                    try {
                        const page = JSON.parse(body);
                        const mod = await server.ssrLoadModule('/resources/js/ssr.tsx');
                        const result = await mod.renderPage(page);
                        res.writeHead(200, { 'Content-Type': 'application/json' });
                        res.end(JSON.stringify(result));
                    } catch (error) {
                        const err = error instanceof Error ? error : new Error(String(error));
                        server.ssrFixStacktrace(err);
                        res.writeHead(500, { 'Content-Type': 'application/json' });
                        res.end(JSON.stringify({ error: err.message, stack: err.stack }));
                    }
                });
            });
        },
    };
}

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.tsx'],
            ssr: 'resources/js/ssr.tsx',
            refresh: true,
        }),
        react(),
        tailwindcss(),
        inertiaDevSsr(),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules/react') || id.includes('node_modules/react-dom') || id.includes('node_modules/@inertiajs')) {
                        return 'vendor-react';
                    }
                    if (id.includes('node_modules/motion') || id.includes('node_modules/animejs')) {
                        return 'vendor-motion';
                    }
                    if (id.includes('node_modules/lucide-react')) {
                        return 'vendor-icons';
                    }
                    if (id.includes('resources/js/themes/holding/')) {
                        return 'theme-holding';
                    }
                    if (id.includes('resources/js/themes/korin-berkat/')) {
                        return 'theme-korin-berkat';
                    }
                    if (id.includes('resources/js/themes/korin-bara/')) {
                        return 'theme-korin-bara';
                    }
                    if (id.includes('resources/js/themes/beramal/')) {
                        return 'theme-beramal';
                    }
                },
            },
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});

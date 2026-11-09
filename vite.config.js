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
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});

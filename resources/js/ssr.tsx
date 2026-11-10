import type { Page } from '@inertiajs/core';
import { createInertiaApp, type ResolvedComponent } from '@inertiajs/react';
import createServer from '@inertiajs/react/server';
import ReactDOMServer from 'react-dom/server';
import { setI18nContext } from '@/core/i18n';
import type { SharedPageProps } from '@/core/types';
import { preloadTheme } from '@/themes/registry';

const appName = import.meta.env.VITE_APP_NAME || 'PT Derco Bara Energy';

const pages = import.meta.glob<{ default: ResolvedComponent }>('./pages/**/*.tsx', {
    eager: true,
});

export async function renderPage(page: Page) {
    const initialProps = page.props as unknown as SharedPageProps;
    await preloadTheme(initialProps?.company?.theme ?? 'holding');

    return createInertiaApp({
        page,
        render: ReactDOMServer.renderToString,
        title: (title) => (title ? `${title} — ${appName}` : appName),
        resolve: (name) => {
            const pageModule = pages[`./pages/${name}.tsx`];
            if (!pageModule) {
                throw new Error(`Page not found: ./pages/${name}.tsx`);
            }

            return pageModule.default;
        },
        setup: ({ App, props }) => {
            if (initialProps?.locale && initialProps?.translations) {
                setI18nContext(initialProps.locale, initialProps.translations);
            }

            return <App {...props} />;
        },
    });
}

if (!import.meta.env.DEV) {
    createServer((page) => renderPage(page));
}

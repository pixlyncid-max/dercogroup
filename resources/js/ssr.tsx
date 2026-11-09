import type { Page } from '@inertiajs/core';
import { createInertiaApp, type ResolvedComponent } from '@inertiajs/react';
import createServer from '@inertiajs/react/server';
import ReactDOMServer from 'react-dom/server';
import { setI18nContext } from '@/core/i18n';
import type { SharedPageProps } from '@/core/types';

const appName = import.meta.env.VITE_APP_NAME || 'PT Derco Bara Energy';

const pages = import.meta.glob<{ default: ResolvedComponent }>('./pages/**/*.tsx', {
    eager: true,
});

export function renderPage(page: Page) {
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
            const initialProps = page.props as unknown as SharedPageProps;
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

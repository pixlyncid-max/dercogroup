import '../css/app.css';
import { createInertiaApp, type ResolvedComponent } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot, hydrateRoot } from 'react-dom/client';
import { setI18nContext } from '@/core/i18n';
import type { SharedPageProps } from '@/core/types';
import { preloadTheme } from '@/themes/registry';

const appName = import.meta.env.VITE_APP_NAME || 'PT Derco Bara Energy';

async function bootstrap() {
    const el = document.getElementById('app');
    if (el?.dataset.page) {
        try {
            const pageData = JSON.parse(el.dataset.page);
            const initialTheme = pageData?.props?.company?.theme;
            await preloadTheme(initialTheme);
        } catch {
            await preloadTheme('holding');
        }
    } else {
        await preloadTheme('holding');
    }

    await createInertiaApp({
        title: (title) => (title ? `${title} — ${appName}` : appName),
        resolve: (name) =>
            resolvePageComponent(
                `./pages/${name}.tsx`,
                import.meta.glob<ResolvedComponent>('./pages/**/*.tsx'),
            ),
        setup({ el, App, props }) {
            const initialProps = props.initialPage.props as unknown as SharedPageProps;
            if (initialProps?.locale && initialProps?.translations) {
                setI18nContext(initialProps.locale, initialProps.translations);
            }

            if (el.hasChildNodes()) {
                hydrateRoot(el, <App {...props} />);
                return;
            }

            createRoot(el).render(<App {...props} />);
        },
        progress: {
            color: 'var(--color-accent, currentColor)',
        },
    });
}

void bootstrap();

import { usePage } from '@inertiajs/react';
import { useLocale } from '../i18n';
import type { SharedPageProps } from '../types';

export interface UseProductUrlsOptions {
    productsPageUrl?: string | null;
    contactPageUrl?: string | null;
}

export interface UseProductUrlsResult {
    catalogUrl: string;
    catalogLabel: string;
    inquireUrl: string;
}

/**
 * Resolves localized catalog and inquiry URLs for product detail views across themes.
 */
export function useProductUrls({
    productsPageUrl,
    contactPageUrl,
}: UseProductUrlsOptions = {}): UseProductUrlsResult {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();

    const productsNavItem = props.navigation?.items?.find((item) => item.key === 'products');
    const catalogUrl = productsPageUrl ?? productsNavItem?.href ?? `/${props.locale ?? 'id'}`;
    const catalogLabel = productsNavItem?.label ?? t('products.all_products');

    const contactNavItem = props.navigation?.items?.find((item) => item.key === 'contact');
    const inquireUrl = contactPageUrl ?? contactNavItem?.href ?? catalogUrl;

    return {
        catalogUrl,
        catalogLabel,
        inquireUrl,
    };
}

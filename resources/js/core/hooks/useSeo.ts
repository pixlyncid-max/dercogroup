import { usePage } from '@inertiajs/react';
import type { Locale, SharedPageProps } from '@/core/types';

export interface UseSeoResult {
    title: string;
    description: string;
    canonicalUrl: string;
    alternateUrls: Partial<Record<Locale, string>>;
    isPreview: boolean;
}

export function useSeo(): UseSeoResult {
    const { props } = usePage<SharedPageProps>();
    const locale = props.locale ?? 'id';
    const companyName = props.company?.name ?? 'PT Derco Bara Energy';
    const pageTitle = props.page?.meta_title || props.page?.title || companyName;
    const description =
        props.page?.meta_description || props.company?.about_short || props.company?.tagline || '';
    const alternateUrls = props.navigation?.alternateUrls ?? {};
    const canonicalUrl = alternateUrls[locale] ?? `/${locale}`;
    const isPreview = Boolean(props.isPreview);

    return {
        title: pageTitle,
        description,
        canonicalUrl,
        alternateUrls,
        isPreview,
    };
}

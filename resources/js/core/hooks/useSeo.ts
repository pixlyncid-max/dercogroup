import { usePage } from '@inertiajs/react';
import type { Locale, SharedPageProps } from '@/core/types';

export interface UseSeoResult {
    title: string;
    description: string;
    canonicalUrl: string;
    alternateUrls: Partial<Record<Locale, string>>;
    isPreview: boolean;
    jsonLd: Record<string, unknown>;
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

    const contacts = props.contacts ?? props.company?.contacts ?? [];
    const primaryContact = contacts[0];
    const sameAs = primaryContact?.socials
        ? Object.values(primaryContact.socials).filter(Boolean)
        : [];

    const jsonLd: Record<string, unknown> = {
        '@context': 'https://schema.org',
        '@type': 'Organization',
        name: companyName,
        url: canonicalUrl,
        ...(description ? { description } : {}),
        ...(props.company?.logo_url ? { logo: props.company.logo_url } : {}),
        ...(primaryContact?.phone ? { telephone: primaryContact.phone } : {}),
        ...(primaryContact?.email ? { email: primaryContact.email } : {}),
        ...(primaryContact?.address
            ? {
                  address: {
                      '@type': 'PostalAddress',
                      streetAddress: primaryContact.address,
                      addressCountry: 'ID',
                  },
              }
            : {}),
        ...(sameAs.length > 0 ? { sameAs } : {}),
        ...(!props.company?.is_holding
            ? {
                  parentOrganization: {
                      '@type': 'Organization',
                      name: 'PT Derco Bara Energy',
                      url: props.navigation?.holdingUrl ?? `/${locale}`,
                  },
              }
            : {}),
    };

    return {
        title: pageTitle,
        description,
        canonicalUrl,
        alternateUrls,
        isPreview,
        jsonLd,
    };
}

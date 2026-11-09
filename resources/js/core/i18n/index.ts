import { usePage } from '@inertiajs/react';
import type { Locale, SharedPageProps, TranslationTree } from '@/core/types';

let activeLocale: Locale = 'id';
let activeTranslations: TranslationTree = {};

/**
 * Synchronize module-level i18n state for SSR and direct t() helper calls.
 */
export function setI18nContext(locale: Locale, translations: TranslationTree): void {
    activeLocale = locale;
    activeTranslations = translations;
}

/**
 * Resolve a dot-notated key from a translation dictionary with optional parameter interpolation.
 */
export function translateFromDictionary(
    dictionary: TranslationTree,
    key: string,
    replacements?: Record<string, string | number>,
): string {
    const segments = key.split('.');
    let current: string | TranslationTree | undefined = dictionary;

    for (const segment of segments) {
        if (typeof current !== 'object' || current === null || !(segment in current)) {
            current = undefined;
            break;
        }
        current = current[segment];
    }

    if (typeof current !== 'string') {
        return key;
    }

    if (!replacements) {
        return current;
    }

    return Object.entries(replacements).reduce<string>((acc, [placeholder, value]) => {
        return acc.replaceAll(`:${placeholder}`, String(value));
    }, current);
}

/**
 * Translate a static UI key using the active locale dictionary.
 */
export function t(key: string, replacements?: Record<string, string | number>): string {
    return translateFromDictionary(activeTranslations, key, replacements);
}

export interface UseLocaleResult {
    locale: Locale;
    locales: Locale[];
    alternateUrls: Partial<Record<Locale, string>>;
    t: (key: string, replacements?: Record<string, string | number>) => string;
    switchUrl: (targetLocale: Locale) => string;
}

/**
 * Hook providing active locale metadata, language switch URLs, and bound t() helper.
 */
export function useLocale(): UseLocaleResult {
    const { props } = usePage<SharedPageProps>();
    const locale: Locale = props.locale ?? activeLocale;
    const locales: Locale[] = props.locales ?? ['id', 'en'];
    const translations: TranslationTree = props.translations ?? activeTranslations;
    const alternateUrls = props.navigation?.alternateUrls ?? {};

    setI18nContext(locale, translations);

    const boundTranslate = (key: string, replacements?: Record<string, string | number>): string =>
        translateFromDictionary(translations, key, replacements);

    const switchUrl = (targetLocale: Locale): string => {
        return alternateUrls[targetLocale] ?? `/${targetLocale}`;
    };

    return {
        locale,
        locales,
        alternateUrls,
        t: boundTranslate,
        switchUrl,
    };
}

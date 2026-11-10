import type { ComponentType, ReactNode } from 'react';
import type {
    BlockTypeValue,
    CompanyThemeKey,
    ContactFormBlockData,
    ContactInfoBlockData,
    ProductData,
} from '@/core';

export interface ThemeRegistration {
    Layout: ComponentType<{ children: ReactNode }>;
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    blocks: Partial<Record<BlockTypeValue, ComponentType<{ data: any; isFirstBlock?: boolean }>>>;
    FallbackBlock: ComponentType<{ data: Record<string, unknown> }>;
    ContactSplit?: ComponentType<{
        infoData: ContactInfoBlockData;
        formData: ContactFormBlockData;
        hasHeroH1: boolean;
    }>;
    ProductDetail?: ComponentType<{
        product: ProductData;
        productsPageUrl?: string | null;
        contactPageUrl?: string | null;
    }>;
}

export const SUPPORTED_THEMES: readonly CompanyThemeKey[] = [
    'holding',
    'korin-berkat',
    'korin-bara',
    'beramal',
] as const;

export function isSupportedTheme(theme: string): theme is CompanyThemeKey {
    return (SUPPORTED_THEMES as readonly string[]).includes(theme);
}

const themeLoaders: Record<CompanyThemeKey, () => Promise<ThemeRegistration>> = {
    holding: () => import('./holding').then((m) => m.holdingTheme),
    'korin-berkat': () => import('./korin-berkat').then((m) => m.korinBerkatTheme),
    'korin-bara': () => import('./korin-bara').then((m) => m.korinBaraTheme),
    beramal: () => import('./beramal').then((m) => m.beramalTheme),
};

const loadedThemes = new Map<CompanyThemeKey, ThemeRegistration>();

export async function preloadTheme(theme?: string | null): Promise<ThemeRegistration> {
    const key: CompanyThemeKey = theme && isSupportedTheme(theme) ? theme : 'holding';
    const cached = loadedThemes.get(key);
    if (cached) {
        return cached;
    }

    const registration = await themeLoaders[key]();
    loadedThemes.set(key, registration);
    return registration;
}

export async function preloadAllThemes(): Promise<void> {
    await Promise.all(SUPPORTED_THEMES.map((theme) => preloadTheme(theme)));
}

export function getLoadedTheme(theme?: string | null): ThemeRegistration | null {
    const key: CompanyThemeKey = theme && isSupportedTheme(theme) ? theme : 'holding';
    return loadedThemes.get(key) ?? null;
}

export function resolveTheme(theme?: string | null): ThemeRegistration {
    const key: CompanyThemeKey = theme && isSupportedTheme(theme) ? theme : 'holding';
    const loaded = loadedThemes.get(key);
    if (loaded) {
        return loaded;
    }

    const fallback = loadedThemes.get('holding');
    if (fallback) {
        return fallback;
    }

    throw new Error(
        `Theme "${key}" has not been preloaded yet. Call await preloadTheme("${key}") before rendering.`,
    );
}

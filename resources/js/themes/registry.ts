import type { ComponentType, ReactNode } from 'react';
import type {
    BlockTypeValue,
    CompanyThemeKey,
    ContactFormBlockData,
    ContactInfoBlockData,
    ProductData,
} from '@/core';
import { holdingTheme } from './holding';
import { korinBerkatTheme } from './korin-berkat';

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

export const themeRegistry: Record<CompanyThemeKey, ThemeRegistration> = {
    holding: holdingTheme,
    'korin-berkat': korinBerkatTheme,
    'korin-bara': korinBerkatTheme,
    beramal: korinBerkatTheme,
};

export function isSupportedTheme(theme: string): theme is CompanyThemeKey {
    return (SUPPORTED_THEMES as readonly string[]).includes(theme);
}

export function resolveTheme(theme?: string | null): ThemeRegistration {
    if (theme && isSupportedTheme(theme)) {
        return themeRegistry[theme];
    }

    return holdingTheme;
}

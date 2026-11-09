import type { ComponentType, ReactNode } from 'react';
import type {
    BlockTypeValue,
    CompanyThemeKey,
    ContactFormBlockData,
    ContactInfoBlockData,
} from '@/core';
import { holdingTheme } from './holding';

export interface ThemeRegistration {
    Layout: ComponentType<{ children: ReactNode }>;
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    blocks: Partial<Record<BlockTypeValue, ComponentType<{ data: any }>>>;
    FallbackBlock: ComponentType<{ data: Record<string, unknown> }>;
    ContactSplit?: ComponentType<{
        infoData: ContactInfoBlockData;
        formData: ContactFormBlockData;
        hasHeroH1: boolean;
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
    'korin-berkat': holdingTheme,
    'korin-bara': holdingTheme,
    beramal: holdingTheme,
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

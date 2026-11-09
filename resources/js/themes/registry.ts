import type { CompanyThemeKey } from '@/core/types';

export const SUPPORTED_THEMES: readonly CompanyThemeKey[] = [
    'holding',
    'korin-berkat',
    'korin-bara',
    'beramal',
] as const;

export function isSupportedTheme(theme: string): theme is CompanyThemeKey {
    return (SUPPORTED_THEMES as readonly string[]).includes(theme);
}

export type Locale = 'id' | 'en';

export type CompanyThemeKey = 'holding' | 'korin-berkat' | 'korin-bara' | 'beramal';

export type CompanyStatusValue = 'draft' | 'published';

export interface SharedCompany {
    name: string;
    slug: string;
    theme: CompanyThemeKey;
    status: CompanyStatusValue;
    is_holding: boolean;
}

export interface NavigationItem {
    key: string;
    label: string;
    href: string;
    active?: boolean;
}

export interface SharedNavigation {
    items: NavigationItem[];
    alternateUrls: Partial<Record<Locale, string>>;
}

export type TranslationTree = {
    [key: string]: string | TranslationTree;
};

export interface SharedPageProps {
    locale: Locale;
    locales: Locale[];
    translations: TranslationTree;
    company: SharedCompany;
    navigation: SharedNavigation;
    [key: string]: unknown;
}

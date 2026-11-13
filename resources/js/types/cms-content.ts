/**
 * TypeScript types automatically synchronized with App\Domain\Page\Blocks (Revisi 6 — Tahap 2).
 * Do not manually alter core field signatures.
 */

export interface TranslatableString {
    id: string;
    en: string;
}

export interface HeaderBlockData {
    logo_path?: string | null;
    logo_alt?: TranslatableString;
    show_company_badge?: boolean;
    show_language_switch?: boolean;
    nav_items?: Array<{
        label: TranslatableString;
        link: string;
        is_visible?: boolean;
    }>;
}

export interface FooterBlockData {
    description: TranslatableString;
    copyright_text: TranslatableString;
    disclaimer?: TranslatableString;
    quick_links?: Array<{
        label: TranslatableString;
        link: string;
        is_visible?: boolean;
    }>;
}

export interface HeroBlockData {
    eyebrow?: TranslatableString;
    title: TranslatableString;
    description: TranslatableString;
    primary_cta_label?: TranslatableString;
    primary_cta_href?: string;
    secondary_cta_label?: TranslatableString;
    secondary_cta_href?: string;
    image?: string | null;
    image_alt?: TranslatableString;
    slides?: Array<{
        image: string;
        image_alt: TranslatableString;
    }>;
}

export interface SplitContentBlockData {
    eyebrow?: TranslatableString;
    title: TranslatableString;
    body: TranslatableString;
    image?: string | null;
    image_alt?: TranslatableString;
    image_position: 'left' | 'right';
    cta_label?: TranslatableString;
    cta_href?: string;
}

export interface StackedImagesBlockData {
    eyebrow?: TranslatableString;
    title: TranslatableString;
    lead: TranslatableString;
    body?: TranslatableString;
    primary_image?: string | null;
    primary_image_alt?: TranslatableString;
    secondary_image?: string | null;
    secondary_image_alt?: TranslatableString;
    cta_label?: TranslatableString;
    cta_href?: string;
}

export interface ColorBandBlockData {
    title: TranslatableString;
    description: TranslatableString;
    icon?: string;
    cta_label?: TranslatableString;
    cta_href?: string;
}

export interface MissionListBlockData {
    eyebrow?: TranslatableString;
    title: TranslatableString;
    vision?: TranslatableString;
    items?: Array<{
        title: TranslatableString;
        description?: TranslatableString;
    }>;
}

export interface RichTextBlockData {
    content: TranslatableString;
}

export interface CtaBannerBlockData {
    eyebrow?: TranslatableString;
    title: TranslatableString;
    subtitle?: TranslatableString;
    phone?: string;
    cta_label: TranslatableString;
    cta_href: string;
}

export interface TimelineBlockData {
    title: TranslatableString;
    orientation: 'vertical' | 'horizontal';
    items: Array<{
        period: string;
        title: TranslatableString;
        description: TranslatableString;
    }>;
}

export interface ValuesGridBlockData {
    title: TranslatableString;
    items: Array<{
        title: TranslatableString;
        description: TranslatableString;
        icon?: string;
    }>;
}

export interface SubsidiaryGridBlockData {
    title: TranslatableString;
    layout_variant?: 'cards' | 'grid';
}

export interface StatsBlockData {
    title: TranslatableString;
}

export interface ProductListBlockData {
    title: TranslatableString;
    limit?: number;
}

export interface OperationListBlockData {
    title: TranslatableString;
}

export interface ContactInfoBlockData {
    title: TranslatableString;
}

export interface ContactFormBlockData {
    title: TranslatableString;
}

export interface CmsSection<T = Record<string, unknown>> {
    id?: number;
    section_key: string;
    type: string;
    data: T;
    is_visible: boolean;
    sort_order: number;
}

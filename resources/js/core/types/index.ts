export type Locale = 'id' | 'en';

export type CompanyThemeKey = 'holding' | 'korin-berkat' | 'korin-bara' | 'beramal';

export type CompanyStatusValue = 'draft' | 'published';

export type PageKeyValue =
    'home' | 'about' | 'business' | 'products' | 'operations' | 'compliance' | 'contact';

export type BlockTypeValue =
    | 'hero'
    | 'split_content'
    | 'stacked_images'
    | 'color_band'
    | 'mission_list'
    | 'rich_text'
    | 'cta_banner'
    | 'timeline'
    | 'values_grid'
    | 'subsidiary_grid'
    | 'stats'
    | 'product_list'
    | 'operation_list'
    | 'certification_list'
    | 'contact_info'
    | 'contact_form';

export type OperationTypeValue = 'mine' | 'stockpile' | 'port' | 'fleet' | 'facility';

export type CertificationCategoryValue = 'certificate' | 'permit' | 'policy' | 'report';

export type ContactTypeValue = 'head_office' | 'site_office' | 'port_office' | 'general';

export interface CompanyStatData {
    id: number;
    value: number;
    prefix?: string | null;
    suffix?: string | null;
    unit: string;
    label: string;
    sort_order: number;
}

export interface CompanyContactData {
    id: number;
    type: ContactTypeValue;
    type_label: string;
    label: string;
    address: string;
    phone?: string | null;
    email?: string | null;
    whatsapp?: string | null;
    operating_hours?: string | null;
    socials: Record<string, string>;
    latitude?: number | null;
    longitude?: number | null;
}

export interface CompanyLogoData {
    light: string;
    dark: string;
    compact?: string | null;
    width: number;
    height: number;
    alt: string;
}

export interface SharedCompany {
    id?: number;
    name: string;
    slug: string;
    theme: CompanyThemeKey;
    status: CompanyStatusValue;
    is_holding: boolean;
    tagline?: string;
    about_short?: string;
    logo_url?: string | null;
    logo_alt?: string;
    logo?: CompanyLogoData | null;
    featured_image_url?: string | null;
    featured_image_srcset?: string | null;
    featured_image_alt?: string;
    url?: string;
    stats?: CompanyStatData[];
    contacts?: CompanyContactData[];
}

export interface PageData {
    id: number;
    key: PageKeyValue;
    title: string;
    slug: string;
    meta_title: string;
    meta_description: string;
    show_in_nav: boolean;
    is_published: boolean;
}

export interface PageBlockData<TData = Record<string, unknown>> {
    id: number;
    type: BlockTypeValue;
    section_key?: string;
    sort_order: number;
    data: TData;
}

export interface HeroSlideData {
    image: string;
    image_srcset?: string | null;
    image_alt?: string;
}

export interface HeroBlockData {
    eyebrow?: string;
    title: string;
    title_emphasis?: string;
    description: string;
    primary_cta_label?: string;
    primary_cta_href?: string | null;
    secondary_cta_label?: string;
    secondary_cta_href?: string | null;
    image?: string | null;
    image_srcset?: string | null;
    image_alt?: string;
    slides?: HeroSlideData[];
}

export interface SubsidiaryGridBlockData {
    eyebrow?: string;
    title: string;
    description?: string;
    layout_variant?: 'cards' | 'alternating' | 'structure';
    cta_label?: string;
    cta_href?: string | null;
}

export interface StackedImagesBlockData {
    eyebrow?: string;
    title: string;
    lead: string;
    body?: string;
    mirrored?: boolean;
    cta_label?: string;
    cta_href?: string | null;
    primary_image?: string | null;
    primary_image_srcset?: string | null;
    primary_image_alt?: string;
    secondary_image?: string | null;
    secondary_image_srcset?: string | null;
    secondary_image_alt?: string;
}

export interface ColorBandBlockData {
    title: string;
    description: string;
    icon_name?: string;
    cta_label?: string;
    cta_href?: string | null;
    image?: string | null;
    image_srcset?: string | null;
    image_alt?: string;
}

export interface MissionListItemData {
    title: string;
    description?: string;
    icon_name?: string;
}

export interface MissionListBlockData {
    eyebrow?: string;
    title: string;
    vision?: string;
    body?: string;
    cta_label?: string;
    cta_href?: string | null;
    primary_image?: string | null;
    primary_image_srcset?: string | null;
    primary_image_alt?: string;
    secondary_image?: string | null;
    secondary_image_srcset?: string | null;
    secondary_image_alt?: string;
    items: MissionListItemData[];
}

export interface CtaBannerBlockData {
    eyebrow?: string;
    title: string;
    subtitle?: string;
    phone?: string;
    cta_label: string;
    cta_href: string;
}

export interface TimelineItemData {
    period: string;
    title: string;
    description: string;
    image?: string | null;
    image_srcset?: string | null;
    image_alt?: string;
}

export interface TimelineBlockData {
    eyebrow?: string;
    title: string;
    description?: string;
    orientation?: 'vertical' | 'horizontal';
    items: TimelineItemData[];
}

export interface ValuesGridItemData {
    title: string;
    description: string;
    icon_name?: string;
}

export interface ValuesGridBlockData {
    eyebrow?: string;
    title: string;
    description?: string;
    items: ValuesGridItemData[];
}

export interface SplitContentBlockData {
    eyebrow?: string;
    title: string;
    lead?: string;
    body: string;
    image_position?: 'left' | 'right';
    cta_label?: string;
    cta_href?: string | null;
    image?: string | null;
    image_srcset?: string | null;
    image_alt?: string;
}

export interface RichTextBlockData {
    eyebrow?: string;
    title?: string;
    content: string;
}

export interface StatsBlockData {
    eyebrow?: string;
    title: string;
    description?: string;
}

export interface ProductListBlockData {
    eyebrow?: string;
    title: string;
    description?: string;
    limit?: number;
    show_download_button?: boolean;
}

export interface OperationListBlockData {
    eyebrow?: string;
    title: string;
    description?: string;
    show_map?: boolean;
}

export interface CertificationListBlockData {
    eyebrow?: string;
    title: string;
    description?: string;
    group_by_category?: boolean;
}

export interface ContactInfoBlockData {
    eyebrow?: string;
    title: string;
    description?: string;
    show_map?: boolean;
}

export interface ContactFormBlockData {
    eyebrow?: string;
    title: string;
    description?: string;
    submit_label?: string;
}

export interface ProductSpecData {
    id: number;
    code?: string | null;
    label: string;
    value: string;
    unit?: string | null;
    is_highlight: boolean;
}

export interface ProductData {
    id: number;
    slug: string;
    name: string;
    category_label: string;
    summary: string;
    description: string;
    image_url?: string | null;
    image_alt: string;
    url?: string | null;
    document_url?: string | null;
    specs: ProductSpecData[];
}

export interface OperationData {
    id: number;
    type: OperationTypeValue;
    type_label: string;
    name: string;
    location_label: string;
    description: string;
    latitude?: number | null;
    longitude?: number | null;
    image_url?: string | null;
    image_alt: string;
    sort_order: number;
}

export interface CertificationData {
    id: number;
    category: CertificationCategoryValue;
    category_label: string;
    name: string;
    issuer: string;
    number?: string | null;
    issued_at?: string | null;
    expires_at?: string | null;
    is_expired: boolean;
    file_url?: string | null;
    sort_order: number;
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
    holdingUrl?: string;
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
    contactSubmitUrl?: string;
    flash?: {
        contactSuccess?: boolean;
    };
    isPreview?: boolean;
    page?: PageData;
    blocks?: PageBlockData[];
    product?: ProductData | null;
    productsPageUrl?: string | null;
    contactPageUrl?: string | null;
    subsidiaries?: SharedCompany[];
    stats?: CompanyStatData[];
    products?: ProductData[];
    operations?: OperationData[];
    certifications?: CertificationData[];
    contacts?: CompanyContactData[];
    [key: string]: unknown;
}

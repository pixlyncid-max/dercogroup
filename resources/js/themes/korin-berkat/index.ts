import type { ComponentType } from 'react';
import type { BlockTypeValue } from '@/core';
import { EditorialHeader } from './EditorialHeader';
import { GoldRule } from './GoldRule';
import { Layout } from './Layout';
import { ProductDetailView } from './ProductDetailView';
import { CertificationListBlock } from './blocks/CertificationListBlock';
import { ContactFormBlock } from './blocks/ContactFormBlock';
import { ContactInfoBlock } from './blocks/ContactInfoBlock';
import { KorinBerkatContactSplit } from './blocks/ContactSplitBlock';
import { CtaBannerBlock } from './blocks/CtaBannerBlock';
import { FallbackBlock } from './blocks/FallbackBlock';
import { HeroBlock } from './blocks/HeroBlock';
import { MissionListBlock } from './blocks/MissionListBlock';
import { OperationListBlock } from './blocks/OperationListBlock';
import { ProductListBlock } from './blocks/ProductListBlock';
import { RichTextBlock } from './blocks/RichTextBlock';
import { SplitContentBlock } from './blocks/SplitContentBlock';
import { StatsBlock } from './blocks/StatsBlock';
import { TimelineBlock } from './blocks/TimelineBlock';
import { ValuesGridBlock } from './blocks/ValuesGridBlock';

export const korinBerkatBlocks: Partial<
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    Record<BlockTypeValue, ComponentType<{ data: any; isFirstBlock?: boolean }>>
> = {
    hero: HeroBlock,
    stats: StatsBlock,
    product_list: ProductListBlock,
    operation_list: OperationListBlock,
    certification_list: CertificationListBlock,
    cta_banner: CtaBannerBlock,
    split_content: SplitContentBlock,
    timeline: TimelineBlock,
    mission_list: MissionListBlock,
    values_grid: ValuesGridBlock,
    rich_text: RichTextBlock,
    contact_info: ContactInfoBlock,
    contact_form: ContactFormBlock,
};

export const korinBerkatTheme = {
    Layout,
    blocks: korinBerkatBlocks,
    FallbackBlock,
    ContactSplit: KorinBerkatContactSplit,
    ProductDetail: ProductDetailView,
};

export { EditorialHeader, GoldRule, KorinBerkatContactSplit, Layout, ProductDetailView };

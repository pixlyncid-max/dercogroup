import type { ComponentType } from 'react';
import type { BlockTypeValue } from '@/core';
import { CalmHeader } from './CalmHeader';
import { Layout } from './Layout';
import { ProductDetailView } from './ProductDetailView';
import { WaveDivider } from './WaveDivider';
import { WorkflowStepper } from './WorkflowStepper';
import { CertificationListBlock } from './blocks/CertificationListBlock';
import { ContactFormBlock } from './blocks/ContactFormBlock';
import { ContactInfoBlock } from './blocks/ContactInfoBlock';
import { BeramalContactSplit } from './blocks/ContactSplitBlock';
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

export const beramalBlocks: Partial<
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

export const beramalTheme = {
    Layout,
    blocks: beramalBlocks,
    FallbackBlock,
    ContactSplit: BeramalContactSplit,
    ProductDetail: ProductDetailView,
};

export { BeramalContactSplit, CalmHeader, Layout, ProductDetailView, WaveDivider, WorkflowStepper };

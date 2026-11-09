import type { ComponentType } from 'react';
import type { BlockTypeValue } from '@/core';
import { ColorBand } from './ColorBand';
import { EyebrowLine } from './EyebrowLine';
import { Layout } from './Layout';
import { StackedImages } from './StackedImages';
import { ColorBandBlock } from './blocks/ColorBandBlock';
import { ContactFormBlock } from './blocks/ContactFormBlock';
import { ContactInfoBlock } from './blocks/ContactInfoBlock';
import { HoldingContactSplit } from './blocks/ContactSplitBlock';
import { CtaBannerBlock } from './blocks/CtaBannerBlock';
import { FallbackBlock } from './blocks/FallbackBlock';
import { HeroBlock } from './blocks/HeroBlock';
import { MissionListBlock } from './blocks/MissionListBlock';
import { RichTextBlock } from './blocks/RichTextBlock';
import { SplitContentBlock } from './blocks/SplitContentBlock';
import { StackedImagesBlock } from './blocks/StackedImagesBlock';
import { StatsBlock } from './blocks/StatsBlock';
import { SubsidiaryGridBlock } from './blocks/SubsidiaryGridBlock';
import { TimelineBlock } from './blocks/TimelineBlock';
import { ValuesGridBlock } from './blocks/ValuesGridBlock';

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export const holdingBlocks: Partial<Record<BlockTypeValue, ComponentType<{ data: any }>>> = {
    hero: HeroBlock,
    subsidiary_grid: SubsidiaryGridBlock,
    stacked_images: StackedImagesBlock,
    color_band: ColorBandBlock,
    mission_list: MissionListBlock,
    cta_banner: CtaBannerBlock,
    contact_info: ContactInfoBlock,
    contact_form: ContactFormBlock,
    split_content: SplitContentBlock,
    rich_text: RichTextBlock,
    timeline: TimelineBlock,
    values_grid: ValuesGridBlock,
    stats: StatsBlock,
};

export const holdingTheme = {
    Layout,
    blocks: holdingBlocks,
    FallbackBlock,
    ContactSplit: HoldingContactSplit,
};

export { ColorBand, EyebrowLine, HoldingContactSplit, Layout, StackedImages };

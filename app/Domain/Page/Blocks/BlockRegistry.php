<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;
use InvalidArgumentException;

class BlockRegistry
{
    /**
     * Map of all 16 BlockType cases to their concrete Block schema classes.
     *
     * @return array<string, class-string<Block>>
     */
    public static function all(): array
    {
        return [
            BlockType::Header->value => HeaderBlock::class,
            BlockType::Footer->value => FooterBlock::class,
            BlockType::Hero->value => HeroBlock::class,
            BlockType::SplitContent->value => SplitContentBlock::class,
            BlockType::StackedImages->value => StackedImagesBlock::class,
            BlockType::ColorBand->value => ColorBandBlock::class,
            BlockType::MissionList->value => MissionListBlock::class,
            BlockType::RichText->value => RichTextBlock::class,
            BlockType::CtaBanner->value => CtaBannerBlock::class,
            BlockType::Timeline->value => TimelineBlock::class,
            BlockType::ValuesGrid->value => ValuesGridBlock::class,
            BlockType::SubsidiaryGrid->value => SubsidiaryGridBlock::class,
            BlockType::Stats->value => StatsBlock::class,
            BlockType::ProductList->value => ProductListBlock::class,
            BlockType::OperationList->value => OperationListBlock::class,
            BlockType::CertificationList->value => CertificationListBlock::class,
            BlockType::ContactInfo->value => ContactInfoBlock::class,
            BlockType::ContactForm->value => ContactFormBlock::class,
        ];
    }

    /**
     * Resolve the concrete Block class for a given BlockType.
     *
     * @return class-string<Block>
     */
    public static function for(BlockType|string $type): string
    {
        $key = $type instanceof BlockType ? $type->value : $type;
        $map = self::all();

        if (! isset($map[$key])) {
            throw new InvalidArgumentException("Unsupported block type [{$key}].");
        }

        return $map[$key];
    }
}

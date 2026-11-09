<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;

class RichTextBlock extends Block
{
    public static function type(): BlockType
    {
        return BlockType::RichText;
    }

    public static function fields(): array
    {
        return [
            ['name' => 'eyebrow', 'type' => 'translatable_text', 'label' => 'Eyebrow'],
            ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Judul Section'],
            ['name' => 'content', 'type' => 'translatable_richtext', 'label' => 'Isi Konten', 'required' => true],
        ];
    }
}

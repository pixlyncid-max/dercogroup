<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;

class SplitContentBlock extends Block
{
    public static function type(): BlockType
    {
        return BlockType::SplitContent;
    }

    public static function fields(): array
    {
        return [
            ['name' => 'eyebrow', 'type' => 'translatable_text', 'label' => 'Eyebrow'],
            ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Judul Section', 'required' => true],
            ['name' => 'lead', 'type' => 'translatable_textarea', 'label' => 'Paragraf Pembuka'],
            ['name' => 'body', 'type' => 'translatable_textarea', 'label' => 'Isi Narasi', 'required' => true],
            [
                'name' => 'image_position',
                'type' => 'select',
                'label' => 'Posisi Gambar',
                'options' => ['left' => 'Kiri', 'right' => 'Kanan'],
                'default' => 'left',
            ],
            ['name' => 'cta_label', 'type' => 'translatable_text', 'label' => 'Label Tombol'],
            ['name' => 'cta_href', 'type' => 'link', 'label' => 'Tautan Tombol'],
            ['name' => 'image', 'type' => 'image', 'label' => 'Gambar'],
        ];
    }
}

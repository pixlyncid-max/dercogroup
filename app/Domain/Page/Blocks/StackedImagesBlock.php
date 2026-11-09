<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;

class StackedImagesBlock extends Block
{
    public static function type(): BlockType
    {
        return BlockType::StackedImages;
    }

    public static function fields(): array
    {
        return [
            ['name' => 'eyebrow', 'type' => 'translatable_text', 'label' => 'Eyebrow'],
            ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Judul Section', 'required' => true],
            ['name' => 'lead', 'type' => 'translatable_textarea', 'label' => 'Paragraf Pembuka Tebal', 'required' => true],
            ['name' => 'body', 'type' => 'translatable_textarea', 'label' => 'Paragraf Isi'],
            ['name' => 'mirrored', 'type' => 'boolean', 'label' => 'Cermin Susunan Gambar', 'default' => false],
            ['name' => 'cta_label', 'type' => 'translatable_text', 'label' => 'Label Tombol'],
            ['name' => 'cta_href', 'type' => 'text', 'label' => 'Tautan Tombol'],
            ['name' => 'primary_image', 'type' => 'image', 'label' => 'Gambar Utama (Atas)'],
            ['name' => 'secondary_image', 'type' => 'image', 'label' => 'Gambar Kedua (Offset Bawah)'],
        ];
    }
}

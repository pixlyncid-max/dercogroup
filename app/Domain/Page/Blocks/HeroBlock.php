<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;

class HeroBlock extends Block
{
    public static function type(): BlockType
    {
        return BlockType::Hero;
    }

    public static function fields(): array
    {
        return [
            ['name' => 'eyebrow', 'type' => 'translatable_text', 'label' => 'Eyebrow'],
            ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Judul Utama', 'required' => true],
            ['name' => 'title_emphasis', 'type' => 'translatable_text', 'label' => 'Penekanan Judul (Baris Kedua / Italic)'],
            ['name' => 'description', 'type' => 'translatable_textarea', 'label' => 'Deskripsi Singkat', 'required' => true],
            ['name' => 'primary_cta_label', 'type' => 'translatable_text', 'label' => 'Label Tombol Utama'],
            ['name' => 'primary_cta_href', 'type' => 'link', 'label' => 'Tautan Tombol Utama'],
            ['name' => 'secondary_cta_label', 'type' => 'translatable_text', 'label' => 'Label Tombol Sekunder'],
            ['name' => 'secondary_cta_href', 'type' => 'link', 'label' => 'Tautan Tombol Sekunder'],
            ['name' => 'image', 'type' => 'image', 'label' => 'Gambar Hero (Statis)'],
            [
                'name' => 'slides',
                'type' => 'repeater',
                'label' => 'Slides Carousel Hero (3–5 Gambar Bergantian)',
                'min' => 3,
                'max' => 5,
                'fields' => [
                    [
                        'name' => 'image',
                        'type' => 'image',
                        'label' => 'Gambar Slide',
                        'required' => true,
                        'require_alt_both' => true,
                    ],
                ],
            ],
        ];
    }
}

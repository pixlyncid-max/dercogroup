<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;

class SubsidiaryGridBlock extends Block
{
    public static function type(): BlockType
    {
        return BlockType::SubsidiaryGrid;
    }

    public static function fields(): array
    {
        return [
            ['name' => 'eyebrow', 'type' => 'translatable_text', 'label' => 'Eyebrow'],
            ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Judul Section', 'required' => true],
            ['name' => 'description', 'type' => 'translatable_textarea', 'label' => 'Deskripsi Pengantar'],
            [
                'name' => 'layout_variant',
                'type' => 'select',
                'label' => 'Gaya Tampilan',
                'options' => [
                    'cards' => 'Grid Kartu (Beranda)',
                    'alternating' => 'Blok Bergantian Kiri-Kanan (Halaman Bisnis Kami)',
                    'structure' => 'Diagram Struktur Grup (Halaman Tentang Kami)',
                ],
                'default' => 'cards',
            ],
            ['name' => 'cta_label', 'type' => 'translatable_text', 'label' => 'Label Tombol Bawah'],
            ['name' => 'cta_href', 'type' => 'link', 'label' => 'Tautan Tombol Bawah'],
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;

class ValuesGridBlock extends Block
{
    public static function type(): BlockType
    {
        return BlockType::ValuesGrid;
    }

    public static function fields(): array
    {
        return [
            ['name' => 'eyebrow', 'type' => 'translatable_text', 'label' => 'Eyebrow'],
            ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Judul Nilai Perusahaan', 'required' => true],
            ['name' => 'description', 'type' => 'translatable_textarea', 'label' => 'Deskripsi Singkat'],
            [
                'name' => 'items',
                'type' => 'repeater',
                'label' => 'Daftar Nilai',
                'required' => true,
                'min' => 1,
                'fields' => [
                    ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Nama Nilai', 'required' => true],
                    ['name' => 'description', 'type' => 'translatable_textarea', 'label' => 'Penjelasan', 'required' => true],
                    ['name' => 'icon_name', 'type' => 'text', 'label' => 'Nama Ikon (Lucide)', 'default' => 'compass'],
                ],
            ],
        ];
    }
}

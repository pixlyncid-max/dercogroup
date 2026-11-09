<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;

class TimelineBlock extends Block
{
    public static function type(): BlockType
    {
        return BlockType::Timeline;
    }

    public static function fields(): array
    {
        return [
            ['name' => 'eyebrow', 'type' => 'translatable_text', 'label' => 'Eyebrow'],
            ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Judul Timeline', 'required' => true],
            ['name' => 'description', 'type' => 'translatable_textarea', 'label' => 'Deskripsi Singkat'],
            [
                'name' => 'orientation',
                'type' => 'select',
                'label' => 'Orientasi',
                'options' => ['vertical' => 'Vertikal', 'horizontal' => 'Horizontal'],
                'default' => 'vertical',
            ],
            [
                'name' => 'items',
                'type' => 'repeater',
                'label' => 'Tahapan / Peristiwa',
                'required' => true,
                'min' => 1,
                'fields' => [
                    ['name' => 'period', 'type' => 'text', 'label' => 'Tahun / Kode Tahap', 'required' => true],
                    ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Judul Peristiwa / Tahap', 'required' => true],
                    ['name' => 'description', 'type' => 'translatable_textarea', 'label' => 'Deskripsi', 'required' => true],
                    ['name' => 'image', 'type' => 'image', 'label' => 'Gambar Tahap (Opsional)'],
                ],
            ],
        ];
    }
}

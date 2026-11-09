<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;

class OperationListBlock extends Block
{
    public static function type(): BlockType
    {
        return BlockType::OperationList;
    }

    public static function fields(): array
    {
        return [
            ['name' => 'eyebrow', 'type' => 'translatable_text', 'label' => 'Eyebrow'],
            ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Judul Section Operasi', 'required' => true],
            ['name' => 'description', 'type' => 'translatable_textarea', 'label' => 'Deskripsi Pengantar'],
            ['name' => 'show_map', 'type' => 'boolean', 'label' => 'Tampilkan Visual Peta Koordinat', 'default' => true],
        ];
    }
}

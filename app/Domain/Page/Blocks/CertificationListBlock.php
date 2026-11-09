<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;

class CertificationListBlock extends Block
{
    public static function type(): BlockType
    {
        return BlockType::CertificationList;
    }

    public static function fields(): array
    {
        return [
            ['name' => 'eyebrow', 'type' => 'translatable_text', 'label' => 'Eyebrow'],
            ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Judul Section Sertifikasi', 'required' => true],
            ['name' => 'description', 'type' => 'translatable_textarea', 'label' => 'Deskripsi Pengantar'],
            ['name' => 'group_by_category', 'type' => 'boolean', 'label' => 'Kelompokkan per Kategori', 'default' => true],
        ];
    }
}

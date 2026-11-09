<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;

class ContactInfoBlock extends Block
{
    public static function type(): BlockType
    {
        return BlockType::ContactInfo;
    }

    public static function fields(): array
    {
        return [
            ['name' => 'eyebrow', 'type' => 'translatable_text', 'label' => 'Eyebrow'],
            ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Judul Informasi Kontak', 'required' => true],
            ['name' => 'description', 'type' => 'translatable_textarea', 'label' => 'Deskripsi Pengantar'],
            ['name' => 'show_map', 'type' => 'boolean', 'label' => 'Tampilkan Peta Lokasi Statis', 'default' => true],
        ];
    }
}

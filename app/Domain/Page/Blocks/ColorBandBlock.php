<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;

class ColorBandBlock extends Block
{
    public static function type(): BlockType
    {
        return BlockType::ColorBand;
    }

    public static function fields(): array
    {
        return [
            ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Judul Pita', 'required' => true],
            ['name' => 'description', 'type' => 'translatable_textarea', 'label' => 'Kalimat Komitmen', 'required' => true],
            ['name' => 'icon_name', 'type' => 'text', 'label' => 'Nama Ikon (Lucide)', 'default' => 'shield-check'],
            ['name' => 'cta_label', 'type' => 'translatable_text', 'label' => 'Label Tombol'],
            ['name' => 'cta_href', 'type' => 'text', 'label' => 'Tautan Tombol'],
            ['name' => 'image', 'type' => 'image', 'label' => 'Gambar Samping'],
        ];
    }
}

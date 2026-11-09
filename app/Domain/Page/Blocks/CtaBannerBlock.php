<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;

class CtaBannerBlock extends Block
{
    public static function type(): BlockType
    {
        return BlockType::CtaBanner;
    }

    public static function fields(): array
    {
        return [
            ['name' => 'eyebrow', 'type' => 'translatable_text', 'label' => 'Eyebrow'],
            ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Judul CTA', 'required' => true],
            ['name' => 'subtitle', 'type' => 'translatable_textarea', 'label' => 'Subjudul / Keterangan'],
            ['name' => 'phone', 'type' => 'text', 'label' => 'Nomor Telepon Utama'],
            ['name' => 'cta_label', 'type' => 'translatable_text', 'label' => 'Label Tombol', 'required' => true],
            ['name' => 'cta_href', 'type' => 'text', 'label' => 'Tautan Tombol', 'required' => true],
        ];
    }
}

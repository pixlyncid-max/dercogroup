<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;

class MissionListBlock extends Block
{
    public static function type(): BlockType
    {
        return BlockType::MissionList;
    }

    public static function fields(): array
    {
        return [
            ['name' => 'eyebrow', 'type' => 'translatable_text', 'label' => 'Eyebrow'],
            ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Judul Visi & Misi', 'required' => true],
            ['name' => 'vision', 'type' => 'translatable_textarea', 'label' => 'Pernyataan Visi'],
            ['name' => 'body', 'type' => 'translatable_textarea', 'label' => 'Narasi Pendukung'],
            ['name' => 'cta_label', 'type' => 'translatable_text', 'label' => 'Label Tombol'],
            ['name' => 'cta_href', 'type' => 'text', 'label' => 'Tautan Tombol'],
            ['name' => 'primary_image', 'type' => 'image', 'label' => 'Gambar Utama'],
            ['name' => 'secondary_image', 'type' => 'image', 'label' => 'Gambar Kedua'],
            [
                'name' => 'items',
                'type' => 'repeater',
                'label' => 'Daftar Poin Misi',
                'required' => true,
                'min' => 1,
                'fields' => [
                    ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Poin Misi', 'required' => true],
                    ['name' => 'description', 'type' => 'translatable_textarea', 'label' => 'Penjelasan Singkat'],
                    ['name' => 'icon_name', 'type' => 'text', 'label' => 'Nama Ikon (Lucide)', 'default' => 'check-circle-2'],
                ],
            ],
        ];
    }
}

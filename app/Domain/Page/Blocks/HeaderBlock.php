<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;

class HeaderBlock extends Block
{
    public static function type(): BlockType
    {
        return BlockType::Header;
    }

    public static function isToggleable(): bool
    {
        return false;
    }

    public static function fields(): array
    {
        return [
            [
                'name' => 'logo_path',
                'type' => 'image',
                'label' => 'Logo Perusahaan',
                'help_text' => 'Unggah file logo resmi (PNG transparan, SVG, atau WebP, maks 2MB).',
                'required' => false,
            ],
            [
                'name' => 'show_company_badge',
                'type' => 'boolean',
                'label' => 'Tampilkan Lencana Entitas / Grup',
                'help_text' => 'Tampilkan tulisan Corporate Group atau nama anak perusahaan di bawah logo.',
                'default' => true,
            ],
            [
                'name' => 'show_language_switch',
                'type' => 'boolean',
                'label' => 'Tampilkan Tombol Bahasa (ID | EN)',
                'help_text' => 'Mengizinkan pengunjung beralih antara Bahasa Indonesia dan Bahasa Inggris.',
                'default' => true,
            ],
            [
                'name' => 'nav_items',
                'type' => 'repeater',
                'label' => 'Daftar Menu Navigasi Header',
                'fields' => [
                    [
                        'name' => 'label',
                        'type' => 'translatable_text',
                        'label' => 'Nama Menu',
                        'required' => true,
                    ],
                    [
                        'name' => 'link',
                        'type' => 'link',
                        'label' => 'Tautan Menu',
                        'required' => true,
                    ],
                    [
                        'name' => 'is_visible',
                        'type' => 'boolean',
                        'label' => 'Tampilkan Menu',
                        'default' => true,
                    ],
                ],
            ],
        ];
    }
}

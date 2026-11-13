<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;

class FooterBlock extends Block
{
    public static function type(): BlockType
    {
        return BlockType::Footer;
    }

    public static function isToggleable(): bool
    {
        return false;
    }

    public static function fields(): array
    {
        return [
            [
                'name' => 'description',
                'type' => 'translatable_textarea',
                'label' => 'Deskripsi Ringkas Footer',
                'help_text' => 'Teks profil singkat perusahaan yang tampil di bawah logo pada bagian footer.',
                'required' => true,
                'max_chars' => 250,
            ],
            [
                'name' => 'copyright_text',
                'type' => 'translatable_text',
                'label' => 'Teks Hak Cipta (Copyright)',
                'help_text' => 'Contoh: © 2026 PT Derco Bara Energy. Seluruh hak cipta dilindungi.',
                'required' => true,
                'max_chars' => 120,
            ],
            [
                'name' => 'disclaimer',
                'type' => 'translatable_text',
                'label' => 'Teks Penafian / Catatan Hukum (Opsional)',
                'help_text' => 'Catatan legal tambahan jika diperlukan.',
                'required' => false,
                'max_chars' => 200,
            ],
            [
                'name' => 'quick_links',
                'type' => 'repeater',
                'label' => 'Tautan Cepat Footer',
                'fields' => [
                    [
                        'name' => 'label',
                        'type' => 'translatable_text',
                        'label' => 'Nama Tautan',
                        'required' => true,
                    ],
                    [
                        'name' => 'link',
                        'type' => 'link',
                        'label' => 'Tautan Tujuan',
                        'required' => true,
                    ],
                    [
                        'name' => 'is_visible',
                        'type' => 'boolean',
                        'label' => 'Tampilkan',
                        'default' => true,
                    ],
                ],
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;

class ContactFormBlock extends Block
{
    public static function type(): BlockType
    {
        return BlockType::ContactForm;
    }

    public static function fields(): array
    {
        return [
            ['name' => 'eyebrow', 'type' => 'translatable_text', 'label' => 'Eyebrow'],
            ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Judul Formulir Kontak', 'required' => true],
            ['name' => 'description', 'type' => 'translatable_textarea', 'label' => 'Deskripsi Pengantar'],
            ['name' => 'submit_label', 'type' => 'translatable_text', 'label' => 'Label Tombol Kirim'],
        ];
    }
}

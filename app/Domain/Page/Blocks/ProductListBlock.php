<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;

class ProductListBlock extends Block
{
    public static function type(): BlockType
    {
        return BlockType::ProductList;
    }

    public static function fields(): array
    {
        return [
            ['name' => 'eyebrow', 'type' => 'translatable_text', 'label' => 'Eyebrow'],
            ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Judul Section Produk', 'required' => true],
            ['name' => 'description', 'type' => 'translatable_textarea', 'label' => 'Deskripsi Pengantar'],
            ['name' => 'limit', 'type' => 'number', 'label' => 'Batas Jumlah Produk (0 = Semua)', 'default' => 0],
            ['name' => 'show_download_button', 'type' => 'boolean', 'label' => 'Tampilkan Tombol Unduh Spesifikasi', 'default' => true],
            ['name' => 'cta_label', 'type' => 'translatable_text', 'label' => 'Label Tombol Selengkapnya'],
            ['name' => 'cta_href', 'type' => 'text', 'label' => 'Tautan Tombol Selengkapnya'],
        ];
    }
}

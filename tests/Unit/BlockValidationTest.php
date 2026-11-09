<?php

declare(strict_types=1);

use App\Domain\Page\Blocks\BlockRegistry;
use App\Domain\Page\Enums\BlockType;
use Illuminate\Validation\ValidationException;

it('validates all 16 block types against their single schema definition', function (BlockType $type, array $validData): void {
    $blockClass = BlockRegistry::for($type);

    $normalized = $blockClass::validateAndNormalize($validData);
    expect($normalized)->toBeArray();

    $localizedId = $blockClass::localizeData($normalized, 'id');
    expect($localizedId)->toBeArray();

    // Missing required title must fail validation
    expect(fn () => $blockClass::validateAndNormalize([]))
        ->toThrow(ValidationException::class);
})->with([
    'hero' => [
        BlockType::Hero,
        [
            'title' => ['id' => 'Judul Hero', 'en' => 'Hero Title'],
            'description' => ['id' => 'Deskripsi Hero', 'en' => 'Hero Description'],
        ],
    ],
    'split_content' => [
        BlockType::SplitContent,
        [
            'title' => ['id' => 'Judul Split', 'en' => 'Split Title'],
            'body' => ['id' => 'Isi Split', 'en' => 'Split Body'],
            'image_position' => 'left',
        ],
    ],
    'stacked_images' => [
        BlockType::StackedImages,
        [
            'title' => ['id' => 'Judul Stacked', 'en' => 'Stacked Title'],
            'lead' => ['id' => 'Lead Stacked', 'en' => 'Stacked Lead'],
        ],
    ],
    'color_band' => [
        BlockType::ColorBand,
        [
            'title' => ['id' => 'Judul Pita', 'en' => 'Band Title'],
            'description' => ['id' => 'Isi Pita', 'en' => 'Band Description'],
        ],
    ],
    'mission_list' => [
        BlockType::MissionList,
        [
            'title' => ['id' => 'Visi Misi', 'en' => 'Vision Mission'],
            'items' => [
                ['title' => ['id' => 'Misi 1', 'en' => 'Mission 1']],
            ],
        ],
    ],
    'rich_text' => [
        BlockType::RichText,
        [
            'content' => ['id' => '<p>Konten</p>', 'en' => '<p>Content</p>'],
        ],
    ],
    'cta_banner' => [
        BlockType::CtaBanner,
        [
            'title' => ['id' => 'Hubungi Kami', 'en' => 'Contact Us'],
            'cta_label' => ['id' => 'Kirim', 'en' => 'Send'],
            'cta_href' => '/id/kontak',
        ],
    ],
    'timeline' => [
        BlockType::Timeline,
        [
            'title' => ['id' => 'Sejarah', 'en' => 'History'],
            'orientation' => 'vertical',
            'items' => [
                [
                    'period' => '2020',
                    'title' => ['id' => 'Peristiwa', 'en' => 'Event'],
                    'description' => ['id' => 'Detail', 'en' => 'Details'],
                ],
            ],
        ],
    ],
    'values_grid' => [
        BlockType::ValuesGrid,
        [
            'title' => ['id' => 'Nilai', 'en' => 'Values'],
            'items' => [
                [
                    'title' => ['id' => 'Integritas', 'en' => 'Integrity'],
                    'description' => ['id' => 'Penjelasan', 'en' => 'Explanation'],
                ],
            ],
        ],
    ],
    'subsidiary_grid' => [
        BlockType::SubsidiaryGrid,
        [
            'title' => ['id' => 'Bisnis Kami', 'en' => 'Our Business'],
            'layout_variant' => 'cards',
        ],
    ],
    'stats' => [
        BlockType::Stats,
        [
            'title' => ['id' => 'Statistik', 'en' => 'Statistics'],
        ],
    ],
    'product_list' => [
        BlockType::ProductList,
        [
            'title' => ['id' => 'Produk', 'en' => 'Products'],
            'limit' => 3,
        ],
    ],
    'operation_list' => [
        BlockType::OperationList,
        [
            'title' => ['id' => 'Operasi', 'en' => 'Operations'],
        ],
    ],
    'certification_list' => [
        BlockType::CertificationList,
        [
            'title' => ['id' => 'Sertifikasi', 'en' => 'Certifications'],
        ],
    ],
    'contact_info' => [
        BlockType::ContactInfo,
        [
            'title' => ['id' => 'Info Kontak', 'en' => 'Contact Info'],
        ],
    ],
    'contact_form' => [
        BlockType::ContactForm,
        [
            'title' => ['id' => 'Form Kontak', 'en' => 'Contact Form'],
        ],
    ],
]);

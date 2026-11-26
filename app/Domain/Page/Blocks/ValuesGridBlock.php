<?php

declare(strict_types=1);

namespace App\Domain\Page\Blocks;

use App\Domain\Page\Enums\BlockType;

class ValuesGridBlock extends Block
{
    public static function type(): BlockType
    {
        return BlockType::ValuesGrid;
    }

    public static function fields(): array
    {
        return [
            ['name' => 'eyebrow', 'type' => 'translatable_text', 'label' => 'Label Kecil (Eyebrow)'],
            ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Judul Bagian (Nilai / Strategi Bisnis)', 'required' => true],
            ['name' => 'description', 'type' => 'translatable_textarea', 'label' => 'Deskripsi Singkat'],
            [
                'name' => 'items',
                'type' => 'repeater',
                'label' => 'Daftar Pilar / Nilai',
                'required' => true,
                'min' => 1,
                'fields' => [
                    ['name' => 'title', 'type' => 'translatable_text', 'label' => 'Judul Pilar / Nilai', 'required' => true],
                    ['name' => 'description', 'type' => 'translatable_textarea', 'label' => 'Penjelasan', 'required' => true],
                    [
                        'name' => 'icon_name',
                        'type' => 'select',
                        'label' => 'Ikon',
                        'default' => 'gem',
                        'options' => [
                            'gem' => 'Berlian (Kualitas Nikel)',
                            'handshake' => 'Jabat Tangan (Hubungan Pelanggan)',
                            'network' => 'Jaringan (Distribusi Pasar)',
                            'trending-up' => 'Grafik Naik (Efisiensi Operasional)',
                            'graduation-cap' => 'Toga (Kualitas SDM)',
                            'bar-chart-3' => 'Diagram Batang (Ekspansi Bisnis)',
                            'check-circle-2' => 'Centang (Kontrol Mutu)',
                            'file-check' => 'Dokumen (Transparansi)',
                            'shield' => 'Perisai (Keselamatan / Kepatuhan)',
                            'shield-check' => 'Perisai Centang (Akurasi Spesifikasi)',
                            'clock' => 'Jam (Disiplin Operasional)',
                            'scale' => 'Timbangan (Kepatuhan Hukum)',
                            'award' => 'Penghargaan (Keunggulan)',
                            'compass' => 'Kompas (Arah Strategis)',
                        ],
                    ],
                ],
            ],
        ];
    }
}

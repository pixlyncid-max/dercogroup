<?php

declare(strict_types=1);

namespace App\Domain\Page\Enums;

enum BlockType: string
{
    case Header = 'header';
    case Footer = 'footer';
    case Hero = 'hero';
    case SplitContent = 'split_content';
    case StackedImages = 'stacked_images';
    case ColorBand = 'color_band';
    case MissionList = 'mission_list';
    case RichText = 'rich_text';
    case CtaBanner = 'cta_banner';
    case Timeline = 'timeline';
    case ValuesGrid = 'values_grid';
    case SubsidiaryGrid = 'subsidiary_grid';
    case Stats = 'stats';
    case ProductList = 'product_list';
    case OperationList = 'operation_list';
    case CertificationList = 'certification_list';
    case ContactInfo = 'contact_info';
    case ContactForm = 'contact_form';

    public function label(): string
    {
        return match ($this) {
            self::Header => 'Header Website (Logo & Navigasi)',
            self::Footer => 'Footer Website (Identitas & Hak Cipta)',
            self::Hero => 'Hero Section',
            self::SplitContent => 'Split Content (Gambar & Teks)',
            self::StackedImages => 'Stacked Images Offset (Khas Holding)',
            self::ColorBand => 'Color Band / Pita Komitmen',
            self::MissionList => 'Visi & Misi (Mission List)',
            self::RichText => 'Rich Text / Narasi Panjang',
            self::CtaBanner => 'CTA Banner',
            self::Timeline => 'Timeline Sejarah / Proses',
            self::ValuesGrid => 'Values Grid / Kartu Nilai',
            self::SubsidiaryGrid => 'Grid Anak Usaha (Dinamis)',
            self::Stats => 'Statistik Perusahaan (Dinamis)',
            self::ProductList => 'Daftar Produk (Dinamis)',
            self::OperationList => 'Daftar Operasi (Dinamis)',
            self::CertificationList => 'Daftar Sertifikasi & Izin (Dinamis)',
            self::ContactInfo => 'Informasi Kontak (Dinamis)',
            self::ContactForm => 'Formulir Kontak (Dinamis)',
        };
    }

    public function isDynamic(): bool
    {
        return match ($this) {
            self::SubsidiaryGrid,
            self::Stats,
            self::ProductList,
            self::OperationList,
            self::CertificationList,
            self::ContactInfo,
            self::ContactForm => true,
            default => false,
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}

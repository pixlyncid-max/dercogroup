<?php

declare(strict_types=1);

namespace App\Domain\Page\Schema;

use App\Domain\Company\Enums\CompanyTheme;
use App\Domain\Company\Models\Company;
use App\Domain\Page\Blocks\BlockRegistry;
use App\Domain\Page\Enums\BlockType;
use App\Domain\Page\Enums\PageKey;

/**
 * Single source of truth for page section schemas, section order per page per theme,
 * layperson field definitions, validation rules, TypeScript types, and default content (Revisi 6 — Tahap 2).
 */
class SectionSchema
{
    /**
     * Normalize localized or alias page keys to Canonical PageKey values.
     */
    public static function normalizePageKey(string $key): string
    {
        return match ($key) {
            'beranda', 'home' => PageKey::Home->value,
            'tentang-kami', 'about' => PageKey::About->value,
            'bisnis-kami', 'business' => PageKey::Business->value,
            'produk', 'products' => PageKey::Products->value,
            'operasi', 'operations' => PageKey::Operations->value,
            'kontak', 'contact' => PageKey::Contact->value,
            'footer' => 'footer',
            default => $key,
        };
    }

    /**
     * Map of ordered sections per theme and page key.
     * Includes Header at top of Beranda and dedicated Footer pseudo-page.
     *
     * @return list<array{
     *     section_key: string,
     *     block_type: BlockType,
     *     label: string,
     *     help_text: string,
     *     is_company_scoped: bool,
     *     is_toggleable: bool,
     *     is_relational: bool
     * }>
     */
    public static function getSectionsForPage(CompanyTheme $theme, string $pageKey): array
    {
        $pageKey = self::normalizePageKey($pageKey);

        // 1. Footer pseudo-page
        if ($pageKey === 'footer') {
            return [
                [
                    'section_key' => 'footer',
                    'block_type' => BlockType::Footer,
                    'label' => 'Footer Website (Identitas & Hak Cipta)',
                    'help_text' => 'Perubahan di bagian footer ini tampil di seluruh halaman website perusahaan.',
                    'is_company_scoped' => true,
                    'is_toggleable' => false,
                    'is_relational' => false,
                ],
            ];
        }

        // 2. Sections per theme and page
        return match ($theme) {
            CompanyTheme::Holding => self::getHoldingSections($pageKey),
            CompanyTheme::KorinBerkat => self::getKorinBerkatSections($pageKey),
            CompanyTheme::KorinBara => self::getKorinBaraSections($pageKey),
            CompanyTheme::Beramal => self::getBeramalSections($pageKey),
        };
    }

    /**
     * @return list<array{section_key: string, block_type: BlockType, label: string, help_text: string, is_company_scoped: bool, is_toggleable: bool, is_relational: bool}>
     */
    private static function getHoldingSections(string $pageKey): array
    {
        return match ($pageKey) {
            PageKey::Home->value => [
                [
                    'section_key' => 'header',
                    'block_type' => BlockType::Header,
                    'label' => 'Header Website (Logo & Menu)',
                    'help_text' => 'Perubahan ini tampil di semua halaman PT Derco Bara Energy.',
                    'is_company_scoped' => true,
                    'is_toggleable' => false,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'home_hero',
                    'block_type' => BlockType::Hero,
                    'label' => 'Banner Utama (Hero)',
                    'help_text' => 'Banner visual utama di bagian atas Beranda dengan judul besar dan tombol aksi.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'home_subsidiaries',
                    'block_type' => BlockType::SubsidiaryGrid,
                    'label' => 'Unit Bisnis / Anak Usaha',
                    'help_text' => 'Ringkasan kartu 3 anak perusahaan operasional.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => true,
                ],
                [
                    'section_key' => 'home_strategic_focus',
                    'block_type' => BlockType::StackedImages,
                    'label' => 'Fokus Strategis & Tata Kelola',
                    'help_text' => 'Narasi keunggulan operasional dengan 2 foto bertingkat (khas holding).',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'home_pillars',
                    'block_type' => BlockType::ColorBand,
                    'label' => 'Pita Komitmen Rantai Pasok',
                    'help_text' => 'Pita aksen penuh tentang keandalan pasokan energi.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'home_vision_mission',
                    'block_type' => BlockType::MissionList,
                    'label' => 'Visi & Misi Perusahaan',
                    'help_text' => 'Tata nilai dan pilar misi masa depan holding.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'home_cta',
                    'block_type' => BlockType::CtaBanner,
                    'label' => 'Banner Ajakan Kerjasama (CTA)',
                    'help_text' => 'Spanduk kontak cepat di bagian bawah halaman sebelum footer.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
            ],
            PageKey::About->value => [
                [
                    'section_key' => 'about_hero',
                    'block_type' => BlockType::Hero,
                    'label' => 'Banner Tentang Kami',
                    'help_text' => 'Judul dan slide foto konsesi penambangan.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'about_profile_history',
                    'block_type' => BlockType::StackedImages,
                    'label' => 'Jejak Langkah & Sejarah',
                    'help_text' => 'Perjalanan panjang komitmen pengelolaan energi.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'about_vision_mission',
                    'block_type' => BlockType::MissionList,
                    'label' => 'Visi & Misi Korporasi',
                    'help_text' => 'Pilar arah strategi korporasi.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'about_values',
                    'block_type' => BlockType::ValuesGrid,
                    'label' => 'Nilai Inti Korporasi',
                    'help_text' => 'Kartu nilai integritas, keselamatan, dan keberlanjutan.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'about_subsidiaries',
                    'block_type' => BlockType::SubsidiaryGrid,
                    'label' => 'Struktur Entitas Afiliasi',
                    'help_text' => 'Daftar grup anak perusahaan penunjang.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => true,
                ],
                [
                    'section_key' => 'about_cta',
                    'block_type' => BlockType::CtaBanner,
                    'label' => 'Banner Hubungi Manajemen',
                    'help_text' => 'Ajakan kemitraan investor & pemangku kepentingan.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
            ],
            PageKey::Business->value => [
                [
                    'section_key' => 'business_hero',
                    'block_type' => BlockType::Hero,
                    'label' => 'Banner Portofolio Bisnis',
                    'help_text' => 'Pengenalan ekosistem energi dari hulu ke hilir.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'business_subsidiaries_detail',
                    'block_type' => BlockType::SubsidiaryGrid,
                    'label' => 'Daftar Terperinci Anak Perusahaan',
                    'help_text' => 'Kartu komprehensif PT Korin Berkat Energy, PT Beramal Sukses Sejahtera, dan PT Korin Bara Energy.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => true,
                ],
            ],
            PageKey::Contact->value => [
                [
                    'section_key' => 'contact_info',
                    'block_type' => BlockType::ContactInfo,
                    'label' => 'Informasi Kontak & Peta Kantor',
                    'help_text' => 'Alamat kantor pusat, telepon, email, WhatsApp, dan titik koordinat peta.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => true,
                ],
                [
                    'section_key' => 'contact_form',
                    'block_type' => BlockType::ContactForm,
                    'label' => 'Formulir Kirim Pesan',
                    'help_text' => 'Formulir pesan masuk publik untuk pengunjung dan mitra.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => false,
                ],
            ],
            default => [],
        };
    }

    /**
     * @return list<array{section_key: string, block_type: BlockType, label: string, help_text: string, is_company_scoped: bool, is_toggleable: bool, is_relational: bool}>
     */
    private static function getKorinBerkatSections(string $pageKey): array
    {
        return match ($pageKey) {
            PageKey::Home->value => [
                [
                    'section_key' => 'header',
                    'block_type' => BlockType::Header,
                    'label' => 'Header Website (Logo & Menu)',
                    'help_text' => 'Perubahan ini tampil di semua halaman PT Korin Berkat Energy.',
                    'is_company_scoped' => true,
                    'is_toggleable' => false,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'home_hero',
                    'block_type' => BlockType::Hero,
                    'label' => 'Banner Utama (Hero)',
                    'help_text' => 'Banner produsen batubara kalori tinggi kualitas ekspor.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'home_stats',
                    'block_type' => BlockType::Stats,
                    'label' => 'Statistik Kapasitas Produksi',
                    'help_text' => 'Angka kunci kapasitas tahunan, cadangan, dan nilai kalori rata-rata.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => true,
                ],
                [
                    'section_key' => 'home_products',
                    'block_type' => BlockType::ProductList,
                    'label' => 'Katalog Produk Unggulan',
                    'help_text' => 'Pratinjau spesifikasi produk batubara termal.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => true,
                ],
                [
                    'section_key' => 'home_operations',
                    'block_type' => BlockType::OperationList,
                    'label' => 'Jejak Rantai Pasok Operasi',
                    'help_text' => 'Alur dari pit penambangan hingga tongkang transshipment.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => true,
                ],
                [
                    'section_key' => 'home_cta',
                    'block_type' => BlockType::CtaBanner,
                    'label' => 'Banner Permintaan Pasokan',
                    'help_text' => 'Ajakan kerjasama kontrak jangka panjang.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
            ],
            PageKey::About->value => [
                [
                    'section_key' => 'about_intro',
                    'block_type' => BlockType::SplitContent,
                    'label' => 'Profil Penambangan & IUP OP',
                    'help_text' => 'Pengenalan wilayah konsesi dan izin usaha pertambangan operasi produksi.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'about_mission',
                    'block_type' => BlockType::MissionList,
                    'label' => 'Misi & Tata Kelola Tambang',
                    'help_text' => 'Komitmen keselamatan kerja dan reklamasi lingkungan.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'about_values',
                    'block_type' => BlockType::ValuesGrid,
                    'label' => 'Nilai Perusahaan',
                    'help_text' => 'Integritas pasokan, ketepatan spesifikasi, dan keandalan kargo.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
            ],
            PageKey::Products->value => [
                [
                    'section_key' => 'products_catalog',
                    'block_type' => BlockType::ProductList,
                    'label' => 'Daftar Lengkap Spesifikasi Produk',
                    'help_text' => 'Dikelola melalui modul Produk (CV, Total Moisture, Ash, Sulfur).',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => true,
                ],
            ],
            PageKey::Operations->value => [
                [
                    'section_key' => 'operations_network',
                    'block_type' => BlockType::OperationList,
                    'label' => 'Jaringan Fasilitas & Titik Operasi',
                    'help_text' => 'Dikelola melalui modul Operasi (koordinat pit, jalan angkut, dan dermaga).',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => true,
                ],
            ],
            PageKey::Contact->value => [
                [
                    'section_key' => 'contact_info',
                    'block_type' => BlockType::ContactInfo,
                    'label' => 'Alamat Kantor Samarinda & Jakarta',
                    'help_text' => 'Alamat kantor operasional lapangan dan kantor perwakilan.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => true,
                ],
                [
                    'section_key' => 'contact_form',
                    'block_type' => BlockType::ContactForm,
                    'label' => 'Formulir Permintaan Penawaran Kargo',
                    'help_text' => 'Formulir permohonan spesifikasi dan alokasi kuota batubara.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => false,
                ],
            ],
            default => [],
        };
    }

    /**
     * @return list<array{section_key: string, block_type: BlockType, label: string, help_text: string, is_company_scoped: bool, is_toggleable: bool, is_relational: bool}>
     */
    private static function getKorinBaraSections(string $pageKey): array
    {
        return match ($pageKey) {
            PageKey::Home->value => [
                [
                    'section_key' => 'header',
                    'block_type' => BlockType::Header,
                    'label' => 'Header Website (Logo & Menu)',
                    'help_text' => 'Perubahan ini tampil di semua halaman PT Korin Bara Energy.',
                    'is_company_scoped' => true,
                    'is_toggleable' => false,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'home_hero',
                    'block_type' => BlockType::Hero,
                    'label' => 'Banner Utama (Hero)',
                    'help_text' => 'Banner batubara termal kalori sedang andal untuk industri domestik dan ekspor.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'home_values',
                    'block_type' => BlockType::ValuesGrid,
                    'label' => 'Strip Nilai Keunggulan',
                    'help_text' => 'Kartu 4 pilar nilai (Keselamatan, Lingkungan, Efisiensi, Kemitraan) yang menimpa tepi hero.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'home_about',
                    'block_type' => BlockType::SplitContent,
                    'label' => 'Tentang Perusahaan',
                    'help_text' => 'Ringkasan komitmen operasional penambangan batubara dengan foto udara tambang.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'home_stats',
                    'block_type' => BlockType::Stats,
                    'label' => 'Statistik Operasional Tambang',
                    'help_text' => 'Angka volume produksi dan ketepatan pengiriman.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => true,
                ],
                [
                    'section_key' => 'home_products',
                    'block_type' => BlockType::ProductList,
                    'label' => 'Katalog Komoditas Batubara',
                    'help_text' => 'Pratinjau produk batubara termal kalori sedang.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => true,
                ],
                [
                    'section_key' => 'home_operations',
                    'block_type' => BlockType::OperationList,
                    'label' => 'Infrastruktur Penambangan & Hauling',
                    'help_text' => 'Daftar fasilitas pit tambang dan jalan angkut khusus.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => true,
                ],
                [
                    'section_key' => 'home_cta',
                    'block_type' => BlockType::CtaBanner,
                    'label' => 'Banner Kontak Penjualan',
                    'help_text' => 'Ajakan kemitraan pasokan batubara.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
            ],
            PageKey::About->value => [
                [
                    'section_key' => 'about_intro',
                    'block_type' => BlockType::SplitContent,
                    'label' => 'Profil Konsesi & Kapabilitas',
                    'help_text' => 'Dedikasi pengelolaan tambang berstandar tinggi.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'about_timeline',
                    'block_type' => BlockType::Timeline,
                    'label' => 'Tahapan Operasional & Sejarah',
                    'help_text' => 'Garis waktu eksplorasi hingga tahap produksi penuh.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'about_mission',
                    'block_type' => BlockType::MissionList,
                    'label' => 'Misi Keselamatan & Reklamasi',
                    'help_text' => 'Komitmen penambangan ramah lingkungan.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'about_values',
                    'block_type' => BlockType::ValuesGrid,
                    'label' => 'Nilai Inti Korin Bara',
                    'help_text' => 'Prinsip kerja disiplin dan berintegritas.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
            ],
            PageKey::Products->value => [
                [
                    'section_key' => 'products_catalog',
                    'block_type' => BlockType::ProductList,
                    'label' => 'Daftar Produk Batubara',
                    'help_text' => 'Dikelola melalui modul Produk.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => true,
                ],
            ],
            PageKey::Operations->value => [
                [
                    'section_key' => 'operations_network',
                    'block_type' => BlockType::OperationList,
                    'label' => 'Jaringan Wilayah Penambangan',
                    'help_text' => 'Dikelola melalui modul Operasi.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => true,
                ],
            ],
            PageKey::Contact->value => [
                [
                    'section_key' => 'contact_info',
                    'block_type' => BlockType::ContactInfo,
                    'label' => 'Informasi Kantor Site & Jakarta',
                    'help_text' => 'Alamat dan kontak perwakilan.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => true,
                ],
                [
                    'section_key' => 'contact_form',
                    'block_type' => BlockType::ContactForm,
                    'label' => 'Formulir Permintaan Informasi',
                    'help_text' => 'Formulir pesan kontak bisnis.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => false,
                ],
            ],
            default => [],
        };
    }

    /**
     * @return list<array{section_key: string, block_type: BlockType, label: string, help_text: string, is_company_scoped: bool, is_toggleable: bool, is_relational: bool}>
     */
    private static function getBeramalSections(string $pageKey): array
    {
        return match ($pageKey) {
            PageKey::Home->value => [
                [
                    'section_key' => 'header',
                    'block_type' => BlockType::Header,
                    'label' => 'Header Website (Logo & Menu)',
                    'help_text' => 'Perubahan ini tampil di semua halaman PT Beramal Sukses Sejahtera.',
                    'is_company_scoped' => true,
                    'is_toggleable' => false,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'home_hero',
                    'block_type' => BlockType::Hero,
                    'label' => 'Banner Utama (Hero)',
                    'help_text' => 'Layanan perdagangan komoditas dan logistik maritim terintegrasi.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'home_stats',
                    'block_type' => BlockType::Stats,
                    'label' => 'Statistik Perdagangan & Armada',
                    'help_text' => 'Metrik armada tongkang dan volume komoditas terkirim.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => true,
                ],
                [
                    'section_key' => 'home_products',
                    'block_type' => BlockType::ProductList,
                    'label' => 'Layanan & Portofolio Komoditas',
                    'help_text' => 'Niaga batubara, bijih nikel, dan sewa armada maritim.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => true,
                ],
                [
                    'section_key' => 'home_cta',
                    'block_type' => BlockType::CtaBanner,
                    'label' => 'Banner Kontak Kerjasama Niaga',
                    'help_text' => 'Ajakan diskusi kebutuhan pengangkutan dan pasokan komoditas.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
            ],
            PageKey::About->value => [
                [
                    'section_key' => 'about_intro',
                    'block_type' => BlockType::SplitContent,
                    'label' => 'Profil Niaga & Jasa Maritim',
                    'help_text' => 'Pengenalan jaringan keagenan kapal dan logistik.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'about_mission',
                    'block_type' => BlockType::MissionList,
                    'label' => 'Misi Layanan Terpercaya',
                    'help_text' => 'Komitmen ketepatan waktu dan keamanan pengiriman laut.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
                [
                    'section_key' => 'about_values',
                    'block_type' => BlockType::ValuesGrid,
                    'label' => 'Nilai Pelayanan Beramal',
                    'help_text' => 'Prinsip amanah, profesionalisme, dan efisiensi logistik.',
                    'is_company_scoped' => false,
                    'is_toggleable' => true,
                    'is_relational' => false,
                ],
            ],
            PageKey::Products->value => [
                [
                    'section_key' => 'products_catalog',
                    'block_type' => BlockType::ProductList,
                    'label' => 'Daftar Layanan & Komoditas Niaga',
                    'help_text' => 'Dikelola melalui modul Layanan & Produk.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => true,
                ],
            ],
            PageKey::Operations->value => [
                [
                    'section_key' => 'operations_network',
                    'block_type' => BlockType::OperationList,
                    'label' => 'Depo Stockpile & Pangkalan Maritim',
                    'help_text' => 'Dikelola melalui modul Operasi.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => true,
                ],
            ],
            PageKey::Contact->value => [
                [
                    'section_key' => 'contact_info',
                    'block_type' => BlockType::ContactInfo,
                    'label' => 'Informasi Kantor & Hub Maritim',
                    'help_text' => 'Alamat kantor Jakarta dan operasional pelabuhan.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => true,
                ],
                [
                    'section_key' => 'contact_form',
                    'block_type' => BlockType::ContactForm,
                    'label' => 'Formulir Permintaan Layanan',
                    'help_text' => 'Formulir permintaan pengangkutan atau pasokan komoditas.',
                    'is_company_scoped' => false,
                    'is_toggleable' => false,
                    'is_relational' => false,
                ],
            ],
            default => [],
        };
    }

    /**
     * Get default values for a section matching the public website inventory.
     * Guaranteed non-empty for all active sections.
     *
     * @return array<string, mixed>
     */
    public static function getDefaultDataForSection(string $companySlug, string $pageKey, string $sectionKey, BlockType $blockType): array
    {
        $blockClass = BlockRegistry::for($blockType);
        $baseDefaults = $blockClass::defaultData();

        // Specific overrides from CMS inventory
        if ($sectionKey === 'header') {
            $baseDefaults['show_company_badge'] = true;
            $baseDefaults['show_language_switch'] = true;
        }

        if ($sectionKey === 'footer') {
            $baseDefaults['description'] = [
                'id' => 'Grup energi dan pertambangan terintegrasi dengan komitmen keberlanjutan dan keunggulan operasional.',
                'en' => 'Integrated energy and mining group committed to sustainability and operational excellence.',
            ];
            $baseDefaults['copyright_text'] = [
                'id' => '© 2026 PT Derco Bara Energy. Seluruh hak cipta dilindungi.',
                'en' => '© 2026 PT Derco Bara Energy. All rights reserved.',
            ];
        }

        return $baseDefaults;
    }

    /**
     * Generate Link Picker options (active pages of company, subsidiary websites, or external URL).
     *
     * @return array<string, array<string, string>>
     */
    public static function getLinkPickerOptions(Company $company): array
    {
        $internalPages = [];
        foreach ($company->theme->pageKeys() as $key) {
            $slug = $key->defaultSlugs($company->theme)['id'];
            $title = $key->defaultTitles($company->theme)['id'];
            $internalPages["page:{$key->value}"] = "{$title} (/{$slug})";
        }

        $subsidiaryLinks = [];
        $subsidiaries = Company::query()
            ->where('id', '!=', $company->id)
            ->where('status', 'published')
            ->get();

        foreach ($subsidiaries as $sub) {
            $subsidiaryLinks["/id/bisnis-kami/{$sub->slug}"] = "Website {$sub->name}";
        }

        return [
            'Halaman Perusahaan Ini' => $internalPages,
            'Entitas Perusahaan Lain' => $subsidiaryLinks,
        ];
    }
}

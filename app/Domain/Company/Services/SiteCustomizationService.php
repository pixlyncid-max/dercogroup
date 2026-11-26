<?php

declare(strict_types=1);

namespace App\Domain\Company\Services;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;

class SiteCustomizationService
{
    public const UI_TEXTS_SETTING_KEY = 'ui_text_overrides';

    public const SITE_MEDIA_SETTING_KEY = 'site_media_overrides';

    /**
     * @return array<string, array{label: string, description: string, default: string}>
     */
    public static function mediaSlots(): array
    {
        return [
            'hero_fallback' => [
                'label' => 'Gambar Latar Hero / Banner Default',
                'description' => 'Digunakan sebagai gambar latar hero atau banner utama jika blok belum memiliki foto khusus.',
                'default' => '/images/placeholders/hero-excavator-1280.webp',
            ],
            'product_fallback_1' => [
                'label' => 'Gambar Kartu Produk — Slot 1',
                'description' => 'Gambar bawaan untuk kartu produk pertama bila produk belum mengunggah foto sendiri.',
                'default' => '/images/placeholders/coal-stockpile-1280.webp',
            ],
            'product_fallback_2' => [
                'label' => 'Gambar Kartu Produk — Slot 2',
                'description' => 'Gambar bawaan untuk kartu produk kedua bila produk belum mengunggah foto sendiri.',
                'default' => '/images/placeholders/crushing-plant-1280.webp',
            ],
            'product_fallback_3' => [
                'label' => 'Gambar Kartu Produk — Slot 3',
                'description' => 'Gambar bawaan untuk kartu produk ketiga bila produk belum mengunggah foto sendiri.',
                'default' => '/images/placeholders/barge-jetty-1280.webp',
            ],
            'operation_fallback_mine' => [
                'label' => 'Gambar Operasi — Tambang / Pit',
                'description' => 'Foto default untuk titik operasi bertipe Tambang (Pit) pada halaman Operasi dan Beranda.',
                'default' => '/images/placeholders/pit-benches-1280.webp',
            ],
            'operation_fallback_stockpile' => [
                'label' => 'Gambar Operasi — Stockpile / ETO-EFO',
                'description' => 'Foto default untuk titik operasi bertipe Stockpile & Blending.',
                'default' => '/images/placeholders/aerial-pit-1280.webp',
            ],
            'operation_fallback_facility' => [
                'label' => 'Gambar Operasi — Fasilitas / Laboratorium',
                'description' => 'Foto default untuk titik operasi bertipe Fasilitas Operasional / Lab XRF.',
                'default' => '/images/placeholders/crushing-plant-1280.webp',
            ],
            'operation_fallback_port' => [
                'label' => 'Gambar Operasi — Pelabuhan & Jetty',
                'description' => 'Foto default untuk titik operasi bertipe Pelabuhan / Jetty Tongkang.',
                'default' => '/images/placeholders/barge-jetty-1280.webp',
            ],
            'operation_fallback_fleet' => [
                'label' => 'Gambar Operasi — Armada Angkutan',
                'description' => 'Foto default untuk titik operasi bertipe Armada Hauling / Tongkang.',
                'default' => '/images/placeholders/haul-trucks-1280.webp',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function resolveTranslations(?Company $company, string $locale): array
    {
        $base = Lang::get('ui', [], $locale);
        if (! is_array($base)) {
            $base = [];
        }

        if (! $company instanceof Company) {
            return $base;
        }

        $overrides = Setting::getValue(self::UI_TEXTS_SETTING_KEY, [], $company);
        if (! is_array($overrides)) {
            return $base;
        }

        $localeOverrides = $overrides[$locale] ?? [];
        if (! is_array($localeOverrides) || $localeOverrides === []) {
            return $base;
        }

        foreach ($localeOverrides as $dotKey => $value) {
            if (is_string($dotKey) && is_string($value) && trim($value) !== '') {
                Arr::set($base, $dotKey, $value);
            }
        }

        return $base;
    }

    /**
     * @return array<string, string>
     */
    public function getDefaultFlatTranslations(string $locale): array
    {
        $base = Lang::get('ui', [], $locale);
        if (! is_array($base)) {
            return [];
        }

        $flat = [];
        foreach (Arr::dot($base) as $key => $value) {
            if (is_string($value)) {
                $flat[$key] = $value;
            }
        }

        return $flat;
    }

    /**
     * @return array<string, string>
     */
    public function resolveSiteMedia(?Company $company): array
    {
        $resolved = [];
        $stored = $company instanceof Company
            ? Setting::getValue(self::SITE_MEDIA_SETTING_KEY, [], $company)
            : [];

        if (! is_array($stored)) {
            $stored = [];
        }

        foreach (array_keys(self::mediaSlots()) as $slot) {
            $rawPath = $stored[$slot] ?? null;
            if (! is_string($rawPath) || trim($rawPath) === '') {
                continue;
            }

            if (str_starts_with($rawPath, 'http://') || str_starts_with($rawPath, 'https://') || str_starts_with($rawPath, '/')) {
                $resolved[$slot] = str_starts_with($rawPath, '/storage/')
                    ? '/media/'.ltrim(substr($rawPath, 9), '/')
                    : $rawPath;
            } else {
                $resolved[$slot] = '/media/'.ltrim($rawPath, '/');
            }
        }

        return $resolved;
    }

    /**
     * Categorize a flat ui.php key into an admin-friendly Indonesian section label.
     */
    public static function groupLabelForKey(string $dotKey): string
    {
        $prefix = explode('.', $dotKey)[0] ?? '';

        return match ($prefix) {
            'nav', 'footer' => 'Navigasi & Footer',
            'hero', 'mission', 'subsidiaries', 'foundation' => 'Hero, Visi Misi & Profil',
            'products' => 'Produk & Spesifikasi',
            'operations' => 'Operasi, Alur Kerja & Nikel',
            'contact', 'compliance' => 'Kontak & Kepatuhan',
            default => 'Umum & Tombol',
        };
    }

    /**
     * Convert a technical dot-key into a plain Indonesian label for non-technical editors.
     */
    public static function humanLabelForKey(string $dotKey): string
    {
        $parts = explode('.', $dotKey);
        $dictionary = [
            'nav' => 'Navigasi',
            'footer' => 'Footer',
            'hero' => 'Banner Utama',
            'mission' => 'Visi & Misi',
            'products' => 'Produk',
            'operations' => 'Operasi',
            'nickel' => 'Nikel',
            'params' => 'Parameter',
            'contact' => 'Kontak',
            'compliance' => 'Kepatuhan',
            'subsidiaries' => 'Anak Perusahaan',
            'foundation' => 'Profil Induk',
            'flow_steps' => 'Alur Batu Bara',
            'calm_flow_steps' => 'Alur Operasi Nikel',
            'planning' => 'Tahap 1',
            'coordination' => 'Tahap 2',
            'verification' => 'Tahap 3',
            'handover' => 'Tahap 4',
            'title' => 'Judul',
            'heading' => 'Judul Bagian',
            'eyebrow' => 'Label Kecil',
            'description' => 'Deskripsi',
            'label' => 'Label',
            'value' => 'Nilai',
            'detail' => 'Keterangan',
            'code' => 'Nomor / Kode',
            'badge' => 'Lencana',
            'cta' => 'Tombol Aksi',
            'placeholder' => 'Teks Contoh',
        ];

        $readable = array_map(static function (string $part) use ($dictionary): string {
            if (isset($dictionary[$part])) {
                return $dictionary[$part];
            }

            return ucwords(str_replace('_', ' ', $part));
        }, $parts);

        return implode(' / ', $readable);
    }
}

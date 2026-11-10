<?php

declare(strict_types=1);

namespace App\Domain\Page\Enums;

use App\Domain\Company\Enums\CompanyTheme;

enum PageKey: string
{
    case Home = 'home';
    case About = 'about';
    case Business = 'business';
    case Products = 'products';
    case Operations = 'operations';
    case Compliance = 'compliance';
    case Contact = 'contact';

    /**
     * @return array{id: string, en: string}
     */
    public function defaultTitles(CompanyTheme|bool|null $themeOrHolding = true): array
    {
        $theme = $themeOrHolding instanceof CompanyTheme
            ? $themeOrHolding
            : ($themeOrHolding ? CompanyTheme::Holding : CompanyTheme::KorinBerkat);

        $usesHubungiKami = $theme === CompanyTheme::Holding || $theme === CompanyTheme::Beramal;

        return match ($this) {
            self::Home => ['id' => 'Beranda', 'en' => 'Home'],
            self::About => ['id' => 'Tentang Kami', 'en' => 'About Us'],
            self::Business => ['id' => 'Bisnis Kami', 'en' => 'Our Business'],
            self::Products => $theme === CompanyTheme::Beramal
                ? ['id' => 'Layanan & Produk', 'en' => 'Services & Products']
                : ['id' => 'Produk', 'en' => 'Products'],
            self::Operations => ['id' => 'Operasi', 'en' => 'Operations'],
            self::Compliance => ['id' => 'Mutu & Kepatuhan', 'en' => 'Quality & Compliance'],
            self::Contact => $usesHubungiKami
                ? ['id' => 'Hubungi Kami', 'en' => 'Contact Us']
                : ['id' => 'Kontak', 'en' => 'Contact'],
        };
    }

    /**
     * @return array{id: string, en: string}
     */
    public function defaultSlugs(CompanyTheme|bool|null $themeOrHolding = true): array
    {
        $theme = $themeOrHolding instanceof CompanyTheme
            ? $themeOrHolding
            : ($themeOrHolding ? CompanyTheme::Holding : CompanyTheme::KorinBerkat);

        $usesHubungiKami = $theme === CompanyTheme::Holding || $theme === CompanyTheme::Beramal;

        return match ($this) {
            self::Home => ['id' => '', 'en' => ''],
            self::About => ['id' => 'tentang-kami', 'en' => 'about-us'],
            self::Business => ['id' => 'bisnis-kami', 'en' => 'our-business'],
            self::Products => ['id' => 'produk', 'en' => 'products'],
            self::Operations => ['id' => 'operasi', 'en' => 'operations'],
            self::Compliance => ['id' => 'mutu-dan-kepatuhan', 'en' => 'quality-and-compliance'],
            self::Contact => $usesHubungiKami
                ? ['id' => 'hubungi-kami', 'en' => 'contact-us']
                : ['id' => 'kontak', 'en' => 'contact'],
        };
    }

    /**
     * @return list<self>
     */
    public static function holdingKeys(): array
    {
        return [
            self::Home,
            self::About,
            self::Business,
            self::Contact,
        ];
    }

    /**
     * @return list<self>
     */
    public static function subsidiaryKeys(): array
    {
        return [
            self::Home,
            self::About,
            self::Products,
            self::Operations,
            self::Compliance,
            self::Contact,
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $titles = $case->defaultTitles();
            $options[$case->value] = "{$titles['id']} ({$case->value})";
        }

        return $options;
    }
}

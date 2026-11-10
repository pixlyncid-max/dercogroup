<?php

declare(strict_types=1);

namespace App\Domain\Company\Enums;

use App\Domain\Page\Enums\PageKey;

enum CompanyTheme: string
{
    case Holding = 'holding';
    case KorinBerkat = 'korin-berkat';
    case KorinBara = 'korin-bara';
    case Beramal = 'beramal';

    public function label(): string
    {
        return match ($this) {
            self::Holding => 'Holding — Corporate Industrial',
            self::KorinBerkat => 'Korin Berkat — Editorial Index',
            self::KorinBara => 'Korin Bara — Field Rail',
            self::Beramal => 'Beramal Sukses — Calm Flow',
        };
    }

    /**
     * @return list<PageKey>
     */
    public function pageKeys(): array
    {
        return match ($this) {
            self::Holding => [
                PageKey::Home,
                PageKey::About,
                PageKey::Business,
                PageKey::Contact,
            ],
            self::KorinBerkat => [
                PageKey::Home,
                PageKey::About,
                PageKey::Products,
                PageKey::Operations,
                PageKey::Contact,
            ],
            self::Beramal => [
                PageKey::Home,
                PageKey::About,
                PageKey::Products,
                PageKey::Operations,
                PageKey::Compliance,
                PageKey::Contact,
            ],
            self::KorinBara => [
                PageKey::Home,
                PageKey::About,
                PageKey::Products,
                PageKey::Operations,
                PageKey::Compliance,
                PageKey::Contact,
            ],
        };
    }

    /**
     * @return array<string, array{is_published: bool, show_in_nav: bool}>
     */
    public function pageOverrides(): array
    {
        return match ($this) {
            self::Beramal => [
                PageKey::Compliance->value => [
                    'is_published' => false,
                    'show_in_nav' => false,
                ],
            ],
            default => [],
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

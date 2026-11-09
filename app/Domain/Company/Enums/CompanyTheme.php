<?php

declare(strict_types=1);

namespace App\Domain\Company\Enums;

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

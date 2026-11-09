<?php

declare(strict_types=1);

namespace App\Domain\Operation\Enums;

enum OperationType: string
{
    case Mine = 'mine';
    case Stockpile = 'stockpile';
    case Port = 'port';
    case Fleet = 'fleet';
    case Facility = 'facility';

    public function label(string $locale = 'id'): string
    {
        if ($locale === 'en') {
            return match ($this) {
                self::Mine => 'Mining Site',
                self::Stockpile => 'Stockpile & Crushing',
                self::Port => 'Jetty & Port Terminal',
                self::Fleet => 'Hauling & Barge Fleet',
                self::Facility => 'Operational Facility',
            };
        }

        return match ($this) {
            self::Mine => 'Tambang',
            self::Stockpile => 'Stockpile & Pengolahan',
            self::Port => 'Pelabuhan & Jetty',
            self::Fleet => 'Armada Angkutan',
            self::Facility => 'Fasilitas Operasional',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label('id');
        }

        return $options;
    }
}

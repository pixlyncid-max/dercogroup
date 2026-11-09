<?php

declare(strict_types=1);

namespace App\Domain\Compliance\Enums;

enum CertificationCategory: string
{
    case Certificate = 'certificate';
    case Permit = 'permit';
    case Policy = 'policy';
    case Report = 'report';

    public function label(string $locale = 'id'): string
    {
        if ($locale === 'en') {
            return match ($this) {
                self::Certificate => 'Quality & Safety Certificate',
                self::Permit => 'Mining & Operational Permit',
                self::Policy => 'Corporate Policy',
                self::Report => 'Compliance & Sustainability Report',
            };
        }

        return match ($this) {
            self::Certificate => 'Sertifikat Mutu & K3',
            self::Permit => 'Izin Usaha & Operasional',
            self::Policy => 'Kebijakan Perusahaan',
            self::Report => 'Laporan Kepatuhan & Lingkungan',
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

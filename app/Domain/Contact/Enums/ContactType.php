<?php

declare(strict_types=1);

namespace App\Domain\Contact\Enums;

enum ContactType: string
{
    case HeadOffice = 'head_office';
    case SiteOffice = 'site_office';
    case PortOffice = 'port_office';
    case General = 'general';

    public function label(string $locale = 'id'): string
    {
        if ($locale === 'en') {
            return match ($this) {
                self::HeadOffice => 'Head Office',
                self::SiteOffice => 'Site Operational Office',
                self::PortOffice => 'Port Office',
                self::General => 'General Inquiry',
            };
        }

        return match ($this) {
            self::HeadOffice => 'Kantor Pusat',
            self::SiteOffice => 'Kantor Operasional Lapangan',
            self::PortOffice => 'Kantor Pelabuhan',
            self::General => 'Kontak Umum',
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

<?php

declare(strict_types=1);

namespace App\Domain\Contact\Enums;

enum ContactMessageStatus: string
{
    case New = 'new';
    case Read = 'read';
    case Replied = 'replied';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Baru',
            self::Read => 'Dibaca',
            self::Replied => 'Dibalas',
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

<?php

declare(strict_types=1);

namespace App\Domain\Company\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'group',
        'value',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $record = self::query()->where('key', $key)->first();

        return $record !== null ? $record->value : $default;
    }

    public static function setValue(string $key, mixed $value, string $group = 'general'): self
    {
        return self::query()->updateOrCreate(
            ['key' => $key],
            ['group' => $group, 'value' => is_array($value) ? $value : ['value' => $value]],
        );
    }
}

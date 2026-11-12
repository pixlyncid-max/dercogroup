<?php

declare(strict_types=1);

namespace App\Domain\Company\Models;

use App\Domain\Company\Services\CompanySettingsReader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    protected $fillable = [
        'company_id',
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
            'company_id' => 'integer',
            'value' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public static function getValue(string $key, mixed $default = null, Company|int|null $company = null): mixed
    {
        return app(CompanySettingsReader::class)->get($key, $default, $company);
    }

    public static function setValue(string $key, mixed $value, string $group = 'general', Company|int|null $company = null): self
    {
        return app(CompanySettingsReader::class)->set($key, $value, $group, $company);
    }
}

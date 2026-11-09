<?php

declare(strict_types=1);

namespace App\Domain\Contact\Models;

use App\Domain\Company\Models\Company;
use App\Domain\Contact\Enums\ContactType;
use App\Support\HasFallbackTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyContact extends Model
{
    use HasFallbackTranslations;

    /**
     * @var list<string>
     */
    public array $translatable = [
        'label',
        'address',
        'operating_hours',
    ];

    protected $fillable = [
        'company_id',
        'type',
        'label',
        'address',
        'phone',
        'email',
        'notify_email',
        'whatsapp',
        'operating_hours',
        'socials',
        'latitude',
        'longitude',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => ContactType::class,
            'socials' => 'array',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Compliance\Models;

use App\Domain\Company\Models\Company;
use App\Domain\Compliance\Enums\CertificationCategory;
use App\Support\HasFallbackTranslations;
use App\Support\RecordsAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certification extends Model
{
    use HasFallbackTranslations;
    use RecordsAuditLog;

    /**
     * @var list<string>
     */
    public array $translatable = [
        'name',
        'issuer',
    ];

    protected $fillable = [
        'company_id',
        'category',
        'name',
        'issuer',
        'number',
        'issued_at',
        'expires_at',
        'file_path_id',
        'file_path_en',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'category' => CertificationCategory::class,
            'issued_at' => 'date',
            'expires_at' => 'date',
            'sort_order' => 'integer',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}

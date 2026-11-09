<?php

declare(strict_types=1);

namespace App\Domain\Company\Models;

use App\Support\HasFallbackTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyStat extends Model
{
    use HasFallbackTranslations;

    /**
     * @var list<string>
     */
    public array $translatable = [
        'unit',
        'label',
    ];

    protected $fillable = [
        'company_id',
        'value',
        'prefix',
        'suffix',
        'unit',
        'label',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'value' => 'float',
            'sort_order' => 'integer',
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

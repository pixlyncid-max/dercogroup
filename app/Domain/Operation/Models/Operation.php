<?php

declare(strict_types=1);

namespace App\Domain\Operation\Models;

use App\Domain\Company\Models\Company;
use App\Domain\Operation\Enums\OperationType;
use App\Support\HasFallbackTranslations;
use App\Support\HasWebpConversions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;

class Operation extends Model implements HasMedia
{
    use HasFallbackTranslations;
    use HasWebpConversions;

    /**
     * @var list<string>
     */
    public array $translatable = [
        'name',
        'location_label',
        'description',
        'image_alt',
    ];

    protected $fillable = [
        'company_id',
        'type',
        'name',
        'location_label',
        'description',
        'latitude',
        'longitude',
        'image_alt',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => OperationType::class,
            'latitude' => 'float',
            'longitude' => 'float',
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

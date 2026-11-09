<?php

declare(strict_types=1);

namespace App\Domain\Product\Models;

use App\Domain\Company\Models\Company;
use App\Support\HasFallbackTranslations;
use App\Support\HasWebpConversions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;

class Product extends Model implements HasMedia
{
    use HasFallbackTranslations;
    use HasWebpConversions;

    /**
     * @var list<string>
     */
    public array $translatable = [
        'name',
        'category_label',
        'summary',
        'description',
        'image_alt',
    ];

    protected $fillable = [
        'company_id',
        'slug',
        'name',
        'category_label',
        'summary',
        'description',
        'image_alt',
        'sort_order',
        'is_published',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Scope a query to only include published products of published companies (FR-VIS).
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->whereHas('company', fn (Builder $companyQuery): Builder => $companyQuery->published());
    }

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return HasMany<ProductSpec, $this>
     */
    public function specs(): HasMany
    {
        return $this->hasMany(ProductSpec::class)->orderBy('sort_order');
    }

    /**
     * @return HasMany<ProductSpec, $this>
     */
    public function highlightSpecs(): HasMany
    {
        return $this->specs()->where('is_highlight', true);
    }
}

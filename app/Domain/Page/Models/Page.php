<?php

declare(strict_types=1);

namespace App\Domain\Page\Models;

use App\Domain\Company\Models\Company;
use App\Domain\Page\Enums\PageKey;
use App\Support\HasFallbackTranslations;
use App\Support\RecordsAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasFallbackTranslations;
    use RecordsAuditLog;

    /**
     * @var list<string>
     */
    public array $translatable = [
        'title',
        'slug',
        'meta_title',
        'meta_description',
    ];

    protected $fillable = [
        'company_id',
        'key',
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'show_in_nav',
        'sort_order',
        'is_published',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'key' => PageKey::class,
            'show_in_nav' => 'boolean',
            'sort_order' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Scope a query to only include published pages belonging to published companies (FR-VIS).
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
     * @return HasMany<PageBlock, $this>
     */
    public function blocks(): HasMany
    {
        return $this->hasMany(PageBlock::class)->orderBy('sort_order');
    }

    /**
     * @return HasMany<PageBlock, $this>
     */
    public function visibleBlocks(): HasMany
    {
        return $this->blocks()->where('is_visible', true);
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Page\Models;

use App\Domain\Company\Models\Company;
use App\Domain\Page\Blocks\BlockRegistry;
use App\Domain\Page\Enums\BlockType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageBlock extends Model
{
    protected $fillable = [
        'company_id',
        'page_id',
        'type',
        'section_key',
        'data',
        'sort_order',
        'is_visible',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => BlockType::class,
            'data' => 'array',
            'sort_order' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $block): void {
            if ($block->company_id === null && $block->page_id !== null) {
                $block->company_id = $block->page?->company_id;
            }

            if ($block->section_key === null) {
                $block->section_key = $block->type instanceof BlockType ? $block->type->value : (string) $block->type;
            }

            $blockClass = BlockRegistry::for($block->type);
            $rawData = is_array($block->data) ? $block->data : [];
            $block->data = $blockClass::validateAndNormalize($rawData);
        });
    }

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return BelongsTo<Page, $this>
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeForCompany(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeForSection(Builder $query, string $sectionKey): Builder
    {
        return $query->where('section_key', $sectionKey);
    }
}

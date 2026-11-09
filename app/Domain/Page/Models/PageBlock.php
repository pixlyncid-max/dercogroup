<?php

declare(strict_types=1);

namespace App\Domain\Page\Models;

use App\Domain\Page\Blocks\BlockRegistry;
use App\Domain\Page\Enums\BlockType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageBlock extends Model
{
    protected $fillable = [
        'page_id',
        'type',
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
            $blockClass = BlockRegistry::for($block->type);
            $rawData = is_array($block->data) ? $block->data : [];
            $block->data = $blockClass::validateAndNormalize($rawData);
        });
    }

    /**
     * @return BelongsTo<Page, $this>
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}

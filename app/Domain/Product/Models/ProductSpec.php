<?php

declare(strict_types=1);

namespace App\Domain\Product\Models;

use App\Support\HasFallbackTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSpec extends Model
{
    use HasFallbackTranslations;

    /**
     * @var list<string>
     */
    public array $translatable = [
        'label',
    ];

    protected $fillable = [
        'product_id',
        'code',
        'label',
        'value',
        'unit',
        'is_highlight',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_highlight' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Product\Resources;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductSpec;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = App::getLocale();

        $specs = $this->relationLoaded('specs')
            ? $this->specs->map(fn (ProductSpec $spec): array => [
                'id' => $spec->id,
                'code' => $spec->code,
                'label' => $spec->translateField('label', $locale),
                'value' => $spec->value,
                'unit' => $spec->unit,
                'is_highlight' => $spec->is_highlight,
            ])->values()->all()
            : [];

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->translateField('name', $locale),
            'category_label' => $this->translateField('category_label', $locale),
            'summary' => $this->translateField('summary', $locale),
            'description' => $this->translateField('description', $locale),
            'image_url' => $this->getFirstMediaUrl('image') ?: null,
            'image_alt' => $this->translateField('image_alt', $locale) ?: $this->translateField('name', $locale),
            'specs' => $specs,
        ];
    }
}

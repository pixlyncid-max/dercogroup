<?php

declare(strict_types=1);

namespace App\Domain\Operation\Resources;

use App\Domain\Operation\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

/**
 * @mixin Operation
 */
class OperationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = App::getLocale();

        return [
            'id' => $this->id,
            'type' => $this->type->value,
            'type_label' => $this->type->label($locale),
            'name' => $this->translateField('name', $locale),
            'location_label' => $this->translateField('location_label', $locale),
            'description' => $this->translateField('description', $locale),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'image_url' => $this->getFirstMediaUrl('image') ?: null,
            'image_alt' => $this->translateField('image_alt', $locale) ?: $this->translateField('name', $locale),
            'sort_order' => $this->sort_order,
        ];
    }
}

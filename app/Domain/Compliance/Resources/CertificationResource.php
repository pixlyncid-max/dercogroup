<?php

declare(strict_types=1);

namespace App\Domain\Compliance\Resources;

use App\Domain\Compliance\Models\Certification;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin Certification
 */
class CertificationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = App::getLocale();
        $filePath = $locale === 'en' && $this->file_path_en
            ? $this->file_path_en
            : ($this->file_path_id ?: $this->file_path_en);

        return [
            'id' => $this->id,
            'category' => $this->category->value,
            'category_label' => $this->category->label($locale),
            'name' => $this->translateField('name', $locale),
            'issuer' => $this->translateField('issuer', $locale),
            'number' => $this->number,
            'issued_at' => $this->issued_at?->toDateString(),
            'expires_at' => $this->expires_at?->toDateString(),
            'is_expired' => $this->isExpired(),
            'file_url' => $filePath ? Storage::disk('public')->url($filePath) : null,
            'sort_order' => $this->sort_order,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Product\Resources;

use App\Domain\Company\Models\Company;
use App\Domain\Page\Actions\PageResolver;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductSpec;
use App\Support\SamplePdfGenerator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

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
        $allowDraftPreview = (bool) $request->attributes->get('allowDraftPreview', false);

        $company = $this->relationLoaded('company') ? $this->company : $this->company()->first();
        $isPreview = $allowDraftPreview && ($company instanceof Company && (! $company->isPublished() || ! $this->is_published));

        $url = $company instanceof Company
            ? app(PageResolver::class)->productUrl($company, $this->resource, $locale, $isPreview)
            : null;

        $name = $this->translateField('name', $locale);
        $categoryLabel = $this->translateField('category_label', $locale);

        $uploadedDocumentUrl = $this->getFirstMediaUrl('document') ?: null;
        $documentUrl = $uploadedDocumentUrl ?: Storage::disk('public')->url(
            SamplePdfGenerator::ensureDocument(
                "products/spec-{$this->slug}-{$locale}.pdf",
                "{$name} — Technical Specification Sheet",
                $categoryLabel ?: 'Derco Group Verified Coal Specification [PLACEHOLDER]',
            ),
        );

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
            'name' => $name,
            'category_label' => $categoryLabel,
            'summary' => $this->translateField('summary', $locale),
            'description' => $this->translateField('description', $locale),
            'image_url' => $this->getFirstMediaUrl('image') ?: null,
            'image_alt' => $this->translateField('image_alt', $locale) ?: $name,
            'url' => $url,
            'document_url' => $documentUrl,
            'specs' => $specs,
        ];
    }
}

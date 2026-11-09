<?php

declare(strict_types=1);

namespace App\Domain\Company\Resources;

use App\Domain\Company\Models\Company;
use App\Domain\Contact\Resources\CompanyContactResource;
use App\Domain\Page\Actions\PageResolver;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

/**
 * @mixin Company
 */
class CompanyResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = App::getLocale();
        $pageResolver = app(PageResolver::class);
        $businessSegment = $pageResolver->businessPrefix($locale);

        $url = $this->isHolding()
            ? "/{$locale}"
            : "/{$locale}/{$businessSegment}/{$this->slug}";

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'theme' => $this->theme->value,
            'status' => $this->status->value,
            'is_holding' => $this->isHolding(),
            'tagline' => $this->translateField('tagline', $locale),
            'about_short' => $this->translateField('about_short', $locale),
            'logo_url' => $this->getFirstMediaUrl('logo') ?: null,
            'logo_alt' => $this->translateField('logo_alt', $locale) ?: $this->name,
            'url' => $url,
            'stats' => $this->relationLoaded('stats')
                ? CompanyStatResource::collection($this->stats)->resolve()
                : [],
            'contacts' => $this->relationLoaded('contacts')
                ? CompanyContactResource::collection($this->contacts)->resolve()
                : [],
        ];
    }
}

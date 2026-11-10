<?php

declare(strict_types=1);

namespace App\Domain\Company\Resources;

use App\Domain\Company\Actions\ResolveCompanyLogo;
use App\Domain\Company\Models\Company;
use App\Domain\Contact\Resources\CompanyContactResource;
use App\Domain\Page\Actions\PageResolver;
use App\Support\ImageSource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

/**
 * @mixin Company
 */
class CompanyResource extends JsonResource
{
    private const DEFAULT_FEATURED_IMAGES = [
        'derco-bara-energy' => '/images/placeholders/hero-excavator-1280.webp',
        'korin-berkat-energy' => '/images/placeholders/coal-stockpile-1280.webp',
        'beramal-sukses-sejahtera' => '/images/placeholders/barge-jetty-1280.webp',
        'korin-bara-energy' => '/images/placeholders/pit-benches-1280.webp',
    ];

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

        $featuredPath = self::DEFAULT_FEATURED_IMAGES[$this->slug] ?? '/images/placeholders/coal-stockpile-1280.webp';
        $featuredSource = ImageSource::resolve($featuredPath);

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
            'logo' => app(ResolveCompanyLogo::class)->execute($this->resource),
            'featured_image_url' => $featuredSource['url'],
            'featured_image_srcset' => $featuredSource['srcset'],
            'featured_image_alt' => "{$this->name} [PLACEHOLDER]",
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

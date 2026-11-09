<?php

declare(strict_types=1);

namespace App\Domain\Page\Resources;

use App\Domain\Page\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

/**
 * @mixin Page
 */
class PageResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = App::getLocale();
        $title = $this->translateField('title', $locale);
        $rawMetaTitle = $this->translateField('meta_title', $locale);
        $defaultTitles = $this->key->defaultTitles();
        $defaultTitleForLocale = $defaultTitles[$locale] ?? $defaultTitles['id'];
        $companyName = $this->relationLoaded('company') && $this->company !== null
            ? $this->company->name
            : '';

        $autoDefaultMetaTitle = $companyName !== ''
            ? "{$defaultTitleForLocale} — {$companyName}"
            : $defaultTitleForLocale;

        $metaTitle = ($rawMetaTitle === '' || ($title !== $defaultTitleForLocale && $rawMetaTitle === $autoDefaultMetaTitle))
            ? ($companyName !== '' ? "{$title} — {$companyName}" : $title)
            : $rawMetaTitle;

        return [
            'id' => $this->id,
            'key' => $this->key->value,
            'title' => $title,
            'slug' => $this->translateField('slug', $locale),
            'meta_title' => $metaTitle,
            'meta_description' => $this->translateField('meta_description', $locale),
            'show_in_nav' => $this->show_in_nav,
            'is_published' => $this->is_published,
        ];
    }
}

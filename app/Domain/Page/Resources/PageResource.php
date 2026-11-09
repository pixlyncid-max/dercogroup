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

        return [
            'id' => $this->id,
            'key' => $this->key->value,
            'title' => $this->translateField('title', $locale),
            'slug' => $this->translateField('slug', $locale),
            'meta_title' => $this->translateField('meta_title', $locale) ?: $this->translateField('title', $locale),
            'meta_description' => $this->translateField('meta_description', $locale),
            'show_in_nav' => $this->show_in_nav,
            'is_published' => $this->is_published,
        ];
    }
}

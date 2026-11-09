<?php

declare(strict_types=1);

namespace App\Domain\Page\Resources;

use App\Domain\Company\Models\Company;
use App\Domain\Page\Actions\PageResolver;
use App\Domain\Page\Blocks\BlockRegistry;
use App\Domain\Page\Models\Page;
use App\Domain\Page\Models\PageBlock;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

/**
 * @mixin PageBlock
 */
class PageBlockResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = App::getLocale();
        $allowDraftPreview = (bool) $request->attributes->get('allowDraftPreview', false);
        $blockClass = BlockRegistry::for($this->type);
        $rawData = is_array($this->data) ? $this->data : [];

        $company = $this->relationLoaded('page') && $this->page instanceof Page && $this->page->relationLoaded('company')
            ? $this->page->company
            : $request->attributes->get('company');

        $linkResolver = $company instanceof Company
            ? fn (string $href): string => app(PageResolver::class)->resolveLink($company, $href, $locale, $allowDraftPreview)
            : null;

        return [
            'id' => $this->id,
            'type' => $this->type->value,
            'sort_order' => $this->sort_order,
            'data' => $blockClass::localizeData($rawData, $locale, $linkResolver),
        ];
    }
}

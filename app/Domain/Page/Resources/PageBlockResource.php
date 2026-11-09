<?php

declare(strict_types=1);

namespace App\Domain\Page\Resources;

use App\Domain\Page\Blocks\BlockRegistry;
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
        $blockClass = BlockRegistry::for($this->type);
        $rawData = is_array($this->data) ? $this->data : [];

        return [
            'id' => $this->id,
            'type' => $this->type->value,
            'sort_order' => $this->sort_order,
            'data' => $blockClass::localizeData($rawData, $locale),
        ];
    }
}

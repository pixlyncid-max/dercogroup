<?php

declare(strict_types=1);

namespace App\Domain\Company\Resources;

use App\Domain\Company\Models\CompanyStat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

/**
 * @mixin CompanyStat
 */
class CompanyStatResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = App::getLocale();

        return [
            'id' => $this->id,
            'value' => (float) $this->value,
            'prefix' => $this->prefix,
            'suffix' => $this->suffix,
            'unit' => $this->translateField('unit', $locale),
            'label' => $this->translateField('label', $locale),
            'sort_order' => $this->sort_order,
        ];
    }
}

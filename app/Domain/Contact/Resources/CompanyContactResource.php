<?php

declare(strict_types=1);

namespace App\Domain\Contact\Resources;

use App\Domain\Contact\Models\CompanyContact;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

/**
 * @mixin CompanyContact
 */
class CompanyContactResource extends JsonResource
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
            'label' => $this->translateField('label', $locale) ?: $this->type->label($locale),
            'address' => $this->translateField('address', $locale),
            'phone' => $this->phone,
            'email' => $this->email,
            'whatsapp' => $this->whatsapp,
            'operating_hours' => $this->translateField('operating_hours', $locale),
            'socials' => $this->socials ?? [],
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}

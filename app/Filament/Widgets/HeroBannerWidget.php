<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domain\Company\Models\Company;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;

class HeroBannerWidget extends Widget
{
    protected string $view = 'filament.widgets.hero-banner';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'lg' => 8,
    ];

    public function getCompany(): ?Company
    {
        $tenant = Filament::getTenant();

        if ($tenant instanceof Company) {
            return $tenant;
        }

        return Company::query()->whereNull('parent_id')->first();
    }

    public function getPublicUrl(): string
    {
        $company = $this->getCompany();
        if (! $company) {
            return url('/id');
        }

        if ($company->isHolding()) {
            return url('/id');
        }

        return url("/id/{$company->slug}");
    }
}

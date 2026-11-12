<?php

declare(strict_types=1);

namespace App\Domain\Company\Traits;

use App\Domain\Company\Models\Company;
use App\Domain\Page\Enums\PageKey;
use Filament\Facades\Filament;

trait HasAdaptiveTenantNavigation
{
    abstract public static function getRequiredPageKey(): PageKey;

    public static function canViewAny(): bool
    {
        $tenant = Filament::getTenant();

        if (! $tenant instanceof Company) {
            return false;
        }

        return $tenant->hasPage(static::getRequiredPageKey());
    }
}

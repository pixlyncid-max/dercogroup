<?php

declare(strict_types=1);

namespace App\Domain\Company\Actions;

use App\Domain\Company\Enums\CompanyStatus;
use App\Domain\Company\Models\Company;

class PublishKorinBaraAction
{
    /**
     * Publish PT Korin Bara Energy and ensure its pages and products are published.
     * Idempotent and safe to run multiple times.
     */
    public function execute(): void
    {
        $company = Company::query()->where('slug', 'korin-bara-energy')->first();
        if (! $company) {
            return;
        }

        // 1. Update company status to Published
        if ($company->status !== CompanyStatus::Published) {
            $company->update(['status' => CompanyStatus::Published]);
        }

        // 2. Ensure all pages are published and shown in navigation
        $company->pages()->update([
            'is_published' => true,
            'show_in_nav' => true,
        ]);

        // 3. Ensure all products are published
        $company->products()->update([
            'is_published' => true,
        ]);
    }
}

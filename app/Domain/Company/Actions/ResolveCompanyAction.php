<?php

declare(strict_types=1);

namespace App\Domain\Company\Actions;

use App\Domain\Company\Models\Company;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ResolveCompanyAction
{
    /**
     * Resolve the holding company (when $slug is null) or a subsidiary by slug.
     * Draft companies throw ModelNotFoundException unless $allowDraftPreview is true.
     *
     * @throws ModelNotFoundException
     */
    public function execute(?string $slug = null, bool $allowDraftPreview = false): Company
    {
        $query = Company::query();

        if (! $allowDraftPreview) {
            $query->published();
        }

        if ($slug === null || $slug === '') {
            /** @var Company $holding */
            $holding = $query->whereNull('parent_id')->firstOrFail();

            return $holding;
        }

        /** @var Company $subsidiary */
        $subsidiary = $query->where('slug', $slug)->firstOrFail();

        return $subsidiary;
    }
}

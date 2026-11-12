<?php

declare(strict_types=1);

namespace App\Domain\Company\Actions;

use App\Domain\Company\Enums\CompanyStatus;
use App\Domain\Company\Models\Company;
use Illuminate\Support\Collection;

class GetGroupSummaryAction
{
    /**
     * @return Collection<int, array{
     *     id: int,
     *     name: string,
     *     slug: string,
     *     theme: string,
     *     status: CompanyStatus,
     *     is_published: bool,
     *     is_holding: bool,
     *     new_messages_count: int,
     *     draft_pages_count: int,
     *     total_pages_count: int,
     *     switch_url: string
     * }>
     */
    public function execute(): Collection
    {
        $companies = Company::query()
            ->withCount([
                'contactMessages as new_messages_count' => fn ($query) => $query->where('status', 'new'),
                'pages as draft_pages_count' => fn ($query) => $query->where('is_published', false),
                'pages as total_pages_count',
            ])
            ->orderByRaw('parent_id IS NOT NULL')
            ->orderBy('sort_order')
            ->get();

        return $companies->map(fn (Company $company): array => [
            'id' => $company->id,
            'name' => $company->name,
            'slug' => $company->slug,
            'theme' => $company->theme->value,
            'status' => $company->status,
            'is_published' => $company->isPublished(),
            'is_holding' => $company->isHolding(),
            'new_messages_count' => (int) $company->new_messages_count,
            'draft_pages_count' => (int) $company->draft_pages_count,
            'total_pages_count' => (int) $company->total_pages_count,
            'switch_url' => url("/admin/{$company->slug}"),
        ]);
    }
}


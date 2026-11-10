<?php

declare(strict_types=1);

namespace App\Domain\Company\Actions;

use App\Domain\Company\Models\Company;
use App\Domain\Page\Models\Page;
use Illuminate\Support\Collection;

class CreateCompanyPages
{
    /**
     * Create the standard 4 (holding) or 6 (subsidiary) pages for a company.
     *
     * @return Collection<int, Page>
     */
    public function execute(Company $company): Collection
    {
        $keys = $company->theme->pageKeys();
        $overrides = $company->theme->pageOverrides();

        $pages = collect();

        foreach ($keys as $index => $pageKey) {
            $titles = $pageKey->defaultTitles($company->theme);
            $slugs = $pageKey->defaultSlugs($company->theme);

            $pageOverride = $overrides[$pageKey->value] ?? [];
            $isPublished = $pageOverride['is_published'] ?? true;
            $showInNav = $pageOverride['show_in_nav'] ?? true;

            $page = Page::query()->firstOrCreate(
                [
                    'company_id' => $company->id,
                    'key' => $pageKey->value,
                ],
                [
                    'title' => $titles,
                    'slug' => $slugs,
                    'meta_title' => [
                        'id' => "{$titles['id']} — {$company->name}",
                        'en' => "{$titles['en']} — {$company->name}",
                    ],
                    'meta_description' => [
                        'id' => $company->translateField('tagline', 'id') ?: "{$titles['id']} {$company->name}",
                        'en' => $company->translateField('tagline', 'en') ?: "{$titles['en']} {$company->name}",
                    ],
                    'show_in_nav' => $showInNav,
                    'sort_order' => $index + 1,
                    'is_published' => $isPublished,
                ],
            );

            $pages->push($page);
        }

        return $pages;
    }
}

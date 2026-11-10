<?php

declare(strict_types=1);

namespace App\Domain\Company\Actions;

use App\Domain\Company\Models\Company;
use App\Domain\Page\Enums\PageKey;
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
        $keys = $company->isHolding()
            ? PageKey::holdingKeys()
            : PageKey::subsidiaryKeys();

        $pages = collect();

        foreach ($keys as $index => $pageKey) {
            $titles = $pageKey->defaultTitles($company->isHolding());
            $slugs = $pageKey->defaultSlugs($company->isHolding());

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
                    'show_in_nav' => true,
                    'sort_order' => $index + 1,
                    'is_published' => true,
                ],
            );

            $pages->push($page);
        }

        return $pages;
    }
}

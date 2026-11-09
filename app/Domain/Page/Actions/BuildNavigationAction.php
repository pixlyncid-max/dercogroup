<?php

declare(strict_types=1);

namespace App\Domain\Page\Actions;

use App\Domain\Company\Models\Company;
use App\Domain\Page\Models\Page;

class BuildNavigationAction
{
    public function __construct(
        private readonly PageResolver $pageResolver,
    ) {}

    /**
     * Build navigation items, alternate language URLs, and holding back-link for the active company.
     *
     * @return array{
     *     items: list<array{key: string, label: string, href: string, active: bool}>,
     *     alternateUrls: array<string, string>,
     *     holdingUrl: string
     * }
     */
    public function execute(
        Company $company,
        ?Page $currentPage = null,
        string $locale = 'id',
        bool $preview = false,
    ): array {
        $pagesQuery = $company->pages()->where('show_in_nav', true);

        if (! $preview) {
            $pagesQuery->where('is_published', true);
        }

        $pages = $pagesQuery->get();

        $items = [];
        foreach ($pages as $page) {
            $items[] = [
                'key' => $page->key->value,
                'label' => $page->translateField('title', $locale),
                'href' => $this->pageResolver->pageUrl($company, $page, $locale, $preview),
                'active' => $currentPage !== null && $currentPage->id === $page->id,
            ];
        }

        $alternateUrls = $currentPage !== null
            ? $this->pageResolver->alternateUrls($company, $currentPage, $preview)
            : [
                'id' => '/id',
                'en' => '/en',
            ];

        return [
            'items' => $items,
            'alternateUrls' => $alternateUrls,
            'holdingUrl' => "/{$locale}",
        ];
    }
}

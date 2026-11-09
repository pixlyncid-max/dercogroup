<?php

declare(strict_types=1);

namespace App\Domain\Page\Actions;

use App\Domain\Company\Models\Company;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Page\Models\Page;
use App\Http\Middleware\SetLocale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Single centralized resolver for translated page slugs and canonical localized URLs.
 */
class PageResolver
{
    /**
     * Resolve a page for a company by its localized slug in the given locale.
     *
     * @throws ModelNotFoundException
     */
    public function resolveBySlug(
        Company $company,
        string $slug,
        string $locale,
        bool $allowDraftPreview = false,
    ): Page {
        if (! $allowDraftPreview && ! $company->isPublished()) {
            throw (new ModelNotFoundException)->setModel(Page::class);
        }

        $normalizedSlug = trim($slug, '/');

        if ($normalizedSlug === '') {
            return $this->resolveByKey($company, PageKey::Home, $allowDraftPreview);
        }

        $query = $this->basePageQuery($company, $allowDraftPreview);

        $pages = $query->get();

        /** @var Page|null $matched */
        $matched = $pages->first(function (Page $page) use ($normalizedSlug, $locale): bool {
            $localizedSlug = trim($page->translateField('slug', $locale), '/');

            return $localizedSlug !== '' && $localizedSlug === $normalizedSlug;
        });

        if ($matched === null) {
            throw (new ModelNotFoundException)->setModel(Page::class);
        }

        return $matched;
    }

    /**
     * Resolve a page for a company by its PageKey enum.
     *
     * @throws ModelNotFoundException
     */
    public function resolveByKey(
        Company $company,
        PageKey $key,
        bool $allowDraftPreview = false,
    ): Page {
        if (! $allowDraftPreview && ! $company->isPublished()) {
            throw (new ModelNotFoundException)->setModel(Page::class);
        }

        /** @var Page $page */
        $page = $this->basePageQuery($company, $allowDraftPreview)
            ->where('key', $key->value)
            ->firstOrFail();

        return $page;
    }

    /**
     * Resolve the localized "Our Business / Bisnis Kami" path segment from the holding company.
     */
    public function businessPrefix(string $locale): string
    {
        $holding = Company::query()->whereNull('parent_id')->first();

        if ($holding !== null) {
            /** @var Page|null $businessPage */
            $businessPage = $holding->pages()->where('key', PageKey::Business->value)->first();
            if ($businessPage !== null) {
                $slug = trim($businessPage->translateField('slug', $locale), '/');
                if ($slug !== '') {
                    return $slug;
                }
            }
        }

        $defaults = PageKey::Business->defaultSlugs();

        return $defaults[$locale] ?? $defaults['id'];
    }

    /**
     * Build the canonical relative URL for a company page in a specific locale.
     */
    public function pageUrl(
        Company $company,
        Page $page,
        string $locale,
        bool $preview = false,
    ): string {
        $pageSlug = trim($page->translateField('slug', $locale), '/');
        $isHome = $page->key === PageKey::Home || $pageSlug === '';

        if ($company->isHolding()) {
            $path = $isHome ? "/{$locale}" : "/{$locale}/{$pageSlug}";
        } else {
            $businessSegment = $this->businessPrefix($locale);
            $path = $isHome
                ? "/{$locale}/{$businessSegment}/{$company->slug}"
                : "/{$locale}/{$businessSegment}/{$company->slug}/{$pageSlug}";
        }

        return $preview ? "{$path}?preview=1" : $path;
    }

    /**
     * Generate alternate language URLs for a given company page.
     *
     * @return array<string, string>
     */
    public function alternateUrls(Company $company, Page $page, bool $preview = false): array
    {
        $urls = [];

        foreach (SetLocale::SUPPORTED_LOCALES as $locale) {
            $urls[$locale] = $this->pageUrl($company, $page, $locale, $preview);
        }

        return $urls;
    }

    /**
     * @return Builder<Page>
     */
    private function basePageQuery(Company $company, bool $allowDraftPreview): Builder
    {
        $query = Page::query()->where('company_id', $company->id);

        if (! $allowDraftPreview) {
            $query->where('is_published', true);
        }

        return $query;
    }
}

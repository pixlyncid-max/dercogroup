<?php

declare(strict_types=1);

namespace App\Domain\Page\Actions;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Resources\CompanyResource;
use App\Domain\Company\Resources\CompanyStatResource;
use App\Domain\Compliance\Resources\CertificationResource;
use App\Domain\Contact\Resources\CompanyContactResource;
use App\Domain\Operation\Resources\OperationResource;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Page\Models\PageBlock;
use App\Domain\Page\Resources\PageBlockResource;
use App\Domain\Page\Resources\PageResource;
use App\Domain\Product\Resources\ProductResource;

class GetCompanyPageAction
{
    public function __construct(
        private readonly PageResolver $pageResolver,
        private readonly BuildNavigationAction $buildNavigation,
    ) {}

    /**
     * Fetch and assemble a company page with its blocks, navigation, and dynamic domain entities.
     *
     * @return array<string, mixed>
     */
    public function execute(
        Company $company,
        PageKey|string $pageKeyOrSlug,
        string $locale = 'id',
        bool $allowDraftPreview = false,
    ): array {
        $page = $pageKeyOrSlug instanceof PageKey
            ? $this->pageResolver->resolveByKey($company, $pageKeyOrSlug, $allowDraftPreview)
            : $this->pageResolver->resolveBySlug($company, $pageKeyOrSlug, $locale, $allowDraftPreview);

        $page->setRelation('company', $company);
        $page->load('visibleBlocks');
        foreach ($page->visibleBlocks as $block) {
            $block->setRelation('page', $page);
        }

        $company->load([
            'stats',
            'operations',
            'certifications',
            'contacts',
        ]);

        $productsQuery = $company->products()->with('specs');
        if (! $allowDraftPreview) {
            $productsQuery->where('is_published', true);
        }
        $products = $productsQuery->get();
        foreach ($products as $product) {
            $product->setRelation('company', $company);
        }

        $subsidiaries = $company->isHolding()
            ? $company->publishedSubsidiaries()->with(['stats', 'contacts'])->get()
            : collect();

        $headerBlock = PageBlock::query()
            ->where('company_id', $company->id)
            ->where('section_key', 'header')
            ->first();

        $footerBlock = PageBlock::query()
            ->where('company_id', $company->id)
            ->where('section_key', 'footer')
            ->first();

        $isPreview = $allowDraftPreview && (! $company->isPublished() || ! $page->is_published);

        return [
            'locale' => $locale,
            'isPreview' => $isPreview,
            'company' => (new CompanyResource($company))->resolve(),
            'page' => (new PageResource($page))->resolve(),
            'blocks' => PageBlockResource::collection($page->visibleBlocks)->resolve(),
            'header' => $headerBlock ? (new PageBlockResource($headerBlock))->resolve() : null,
            'footer' => $footerBlock ? (new PageBlockResource($footerBlock))->resolve() : null,
            'navigation' => $this->buildNavigation->execute($company, $page, $locale, $isPreview),
            'subsidiaries' => CompanyResource::collection($subsidiaries)->resolve(),
            'stats' => CompanyStatResource::collection($company->stats)->resolve(),
            'products' => ProductResource::collection($products)->resolve(),
            'operations' => OperationResource::collection($company->operations)->resolve(),
            'certifications' => CertificationResource::collection($company->certifications)->resolve(),
            'contacts' => CompanyContactResource::collection($company->contacts)->resolve(),
        ];
    }
}

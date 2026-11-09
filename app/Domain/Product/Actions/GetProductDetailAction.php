<?php

declare(strict_types=1);

namespace App\Domain\Product\Actions;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Resources\CompanyResource;
use App\Domain\Company\Resources\CompanyStatResource;
use App\Domain\Contact\Resources\CompanyContactResource;
use App\Domain\Page\Actions\BuildNavigationAction;
use App\Domain\Page\Actions\PageResolver;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Page\Resources\PageResource;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Resources\ProductResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetProductDetailAction
{
    public function __construct(
        private readonly PageResolver $pageResolver,
        private readonly BuildNavigationAction $buildNavigation,
    ) {}

    /**
     * Resolve a published product for a subsidiary company and assemble its Inertia page payload.
     *
     * @return array<string, mixed>
     *
     * @throws ModelNotFoundException
     */
    public function execute(
        Company $company,
        string $productSlug,
        string $locale = 'id',
        bool $allowDraftPreview = false,
    ): array {
        if (! $allowDraftPreview && ! $company->isPublished()) {
            throw (new ModelNotFoundException)->setModel(Product::class);
        }

        $productsPage = $this->pageResolver->resolveByKey($company, PageKey::Products, $allowDraftPreview);
        $productsPage->setRelation('company', $company);

        $productQuery = $company->products()->with('specs')->where('slug', $productSlug);
        if (! $allowDraftPreview) {
            $productQuery->where('is_published', true);
        }

        /** @var Product $product */
        $product = $productQuery->firstOrFail();
        $product->setRelation('company', $company);

        $company->load([
            'stats',
            'contacts',
        ]);

        $allProductsQuery = $company->products()->with('specs');
        if (! $allowDraftPreview) {
            $allProductsQuery->where('is_published', true);
        }
        $allProducts = $allProductsQuery->get();
        foreach ($allProducts as $item) {
            $item->setRelation('company', $company);
        }

        $isPreview = $allowDraftPreview && (! $company->isPublished() || ! $productsPage->is_published || ! $product->is_published);

        $navigation = $this->buildNavigation->execute($company, $productsPage, $locale, $isPreview);
        $navigation['alternateUrls'] = $this->pageResolver->productAlternateUrls($company, $product, $isPreview);

        $pagePayload = (new PageResource($productsPage))->resolve();
        $productName = $product->translateField('name', $locale);
        $productSummary = $product->translateField('summary', $locale);

        $pagePayload['title'] = $productName;
        $pagePayload['meta_title'] = "{$productName} — {$company->name}";
        $pagePayload['meta_description'] = $productSummary ?: $pagePayload['meta_description'];

        return [
            'locale' => $locale,
            'isPreview' => $isPreview,
            'company' => (new CompanyResource($company))->resolve(),
            'page' => $pagePayload,
            'blocks' => [],
            'product' => (new ProductResource($product))->resolve(),
            'productsPageUrl' => $this->pageResolver->pageUrl($company, $productsPage, $locale, $isPreview),
            'contactPageUrl' => $this->pageResolver->resolveLink($company, 'page:contact', $locale, $isPreview),
            'navigation' => $navigation,
            'subsidiaries' => [],
            'stats' => CompanyStatResource::collection($company->stats)->resolve(),
            'products' => ProductResource::collection($allProducts)->resolve(),
            'operations' => [],
            'certifications' => [],
            'contacts' => CompanyContactResource::collection($company->contacts)->resolve(),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Company\Actions\ResolveCompanyAction;
use App\Domain\Company\Models\Company;
use App\Domain\Page\Actions\GetCompanyPageAction;
use App\Domain\Page\Actions\PageResolver;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Product\Actions\GetProductDetailAction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Single generic controller serving all 6 pages and product detail views for every subsidiary company.
 */
class SubsidiaryPageController extends Controller
{
    public function __construct(
        private readonly ResolveCompanyAction $resolveCompanyAction,
        private readonly PageResolver $pageResolver,
        private readonly GetCompanyPageAction $getCompanyPageAction,
        private readonly GetProductDetailAction $getProductDetailAction,
    ) {}

    public function home(
        Request $request,
        string $locale,
        string $businessSegment,
        string $company,
    ): Response {
        return $this->renderSubsidiaryPage($request, $businessSegment, $company, PageKey::Home);
    }

    public function show(
        Request $request,
        string $locale,
        string $businessSegment,
        string $company,
        string $pageSlug,
    ): Response {
        return $this->renderSubsidiaryPage($request, $businessSegment, $company, $pageSlug);
    }

    public function product(
        Request $request,
        string $locale,
        string $businessSegment,
        string $company,
        string $productsSegment,
        string $productSlug,
    ): Response {
        $activeLocale = App::getLocale();
        $allowDraftPreview = (bool) $request->attributes->get('allowDraftPreview', false);

        $this->assertValidBusinessSegment($businessSegment, $activeLocale);
        $resolvedCompany = $this->resolveSubsidiary($request, $company, $allowDraftPreview);
        $this->assertValidProductsSegment($resolvedCompany, $productsSegment, $activeLocale, $allowDraftPreview);

        $payload = $this->getProductDetailAction->execute(
            $resolvedCompany,
            $productSlug,
            $activeLocale,
            $allowDraftPreview,
        );

        return Inertia::render('ThemedPage', $payload);
    }

    private function renderSubsidiaryPage(
        Request $request,
        string $businessSegment,
        string $companySlug,
        PageKey|string $keyOrSlug,
    ): Response {
        $locale = App::getLocale();
        $allowDraftPreview = (bool) $request->attributes->get('allowDraftPreview', false);

        $this->assertValidBusinessSegment($businessSegment, $locale);
        $company = $this->resolveSubsidiary($request, $companySlug, $allowDraftPreview);

        $payload = $this->getCompanyPageAction->execute(
            $company,
            $keyOrSlug,
            $locale,
            $allowDraftPreview,
        );

        return Inertia::render('ThemedPage', $payload);
    }

    private function resolveSubsidiary(
        Request $request,
        string $companySlug,
        bool $allowDraftPreview,
    ): Company {
        /** @var Company|null $company */
        $company = $request->attributes->get('company');

        if (! $company instanceof Company || $company->slug !== $companySlug) {
            $company = $this->resolveCompanyAction->execute($companySlug, $allowDraftPreview);
            $request->attributes->set('company', $company);
        }

        if ($company->isHolding()) {
            throw (new ModelNotFoundException)->setModel(Company::class);
        }

        return $company;
    }

    private function assertValidBusinessSegment(string $businessSegment, string $locale): void
    {
        $expected = $this->pageResolver->businessPrefix($locale);

        if ($businessSegment !== $expected) {
            abort(404);
        }
    }

    private function assertValidProductsSegment(
        Company $company,
        string $productsSegment,
        string $locale,
        bool $allowDraftPreview,
    ): void {
        $expected = $this->pageResolver->productsPrefix($company, $locale, $allowDraftPreview);

        if ($productsSegment !== $expected) {
            abort(404);
        }
    }
}

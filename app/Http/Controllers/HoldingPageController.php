<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Company\Actions\ResolveCompanyAction;
use App\Domain\Company\Models\Company;
use App\Domain\Page\Actions\GetCompanyPageAction;
use App\Domain\Page\Enums\PageKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Inertia\Response;

class HoldingPageController extends Controller
{
    public function __construct(
        private readonly ResolveCompanyAction $resolveCompanyAction,
        private readonly GetCompanyPageAction $getCompanyPageAction,
    ) {}

    public function home(Request $request): Response
    {
        return $this->renderHoldingPage($request, PageKey::Home);
    }

    public function show(Request $request, string $locale, string $pageSlug): Response
    {
        return $this->renderHoldingPage($request, $pageSlug);
    }

    private function renderHoldingPage(Request $request, PageKey|string $keyOrSlug): Response
    {
        $locale = App::getLocale();
        $allowDraftPreview = (bool) $request->attributes->get('allowDraftPreview', false);

        /** @var Company|null $company */
        $company = $request->attributes->get('company');

        if (! $company instanceof Company && Schema::hasTable('companies') && Company::query()->exists()) {
            $company = $this->resolveCompanyAction->execute(null, $allowDraftPreview);
            $request->attributes->set('company', $company);
        }

        if (! $company instanceof Company) {
            return Inertia::render('Home');
        }

        $payload = $this->getCompanyPageAction->execute(
            $company,
            $keyOrSlug,
            $locale,
            $allowDraftPreview,
        );

        return Inertia::render('ThemedPage', $payload);
    }
}

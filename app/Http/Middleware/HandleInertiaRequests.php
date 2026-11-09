<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Resources\CompanyResource;
use App\Domain\Page\Actions\BuildNavigationAction;
use App\Domain\Page\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $locale = App::getLocale();
        $translations = Lang::get('ui', [], $locale);

        /** @var Company|null $company */
        $company = $request->attributes->get('company');
        /** @var Page|null $currentPage */
        $currentPage = $request->attributes->get('currentPage');
        $allowDraftPreview = (bool) $request->attributes->get('allowDraftPreview', false);

        $companyPayload = $company instanceof Company
            ? (new CompanyResource($company))->resolve()
            : [
                'name' => 'PT Derco Bara Energy',
                'slug' => 'derco-bara-energy',
                'theme' => 'holding',
                'status' => 'published',
                'is_holding' => true,
            ];

        $navigationPayload = $company instanceof Company
            ? app(BuildNavigationAction::class)->execute($company, $currentPage, $locale, $allowDraftPreview)
            : [
                'items' => [],
                'alternateUrls' => [
                    'id' => url('/id'),
                    'en' => url('/en'),
                ],
                'holdingUrl' => "/{$locale}",
            ];

        return [
            ...parent::share($request),
            'locale' => $locale,
            'locales' => SetLocale::SUPPORTED_LOCALES,
            'translations' => is_array($translations) ? $translations : [],
            'isPreview' => $allowDraftPreview,
            'company' => $companyPayload,
            'navigation' => $navigationPayload,
        ];
    }
}

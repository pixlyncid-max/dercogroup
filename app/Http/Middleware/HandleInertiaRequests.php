<?php

declare(strict_types=1);

namespace App\Http\Middleware;

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

        return [
            ...parent::share($request),
            'locale' => $locale,
            'locales' => SetLocale::SUPPORTED_LOCALES,
            'translations' => is_array($translations) ? $translations : [],
            'company' => [
                'name' => 'PT Derco Bara Energy',
                'slug' => 'derco-bara-energy',
                'theme' => 'holding',
                'status' => 'published',
                'is_holding' => true,
            ],
            'navigation' => [
                'items' => [],
                'alternateUrls' => [
                    'id' => url('/id'),
                    'en' => url('/en'),
                ],
            ],
        ];
    }
}

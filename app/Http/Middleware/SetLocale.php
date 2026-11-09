<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported application locales.
     *
     * @var list<string>
     */
    public const SUPPORTED_LOCALES = ['id', 'en'];

    public const DEFAULT_LOCALE = 'id';

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeLocale = $request->route('locale');
        $locale = is_string($routeLocale) && in_array($routeLocale, self::SUPPORTED_LOCALES, true)
            ? $routeLocale
            : self::DEFAULT_LOCALE;

        App::setLocale($locale);
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }

    /**
     * Detect preferred locale from Accept-Language header with fallback to DEFAULT_LOCALE.
     */
    public static function negotiateFromRequest(Request $request): string
    {
        $preferred = $request->getPreferredLanguage(self::SUPPORTED_LOCALES);

        return is_string($preferred) && in_array($preferred, self::SUPPORTED_LOCALES, true)
            ? $preferred
            : self::DEFAULT_LOCALE;
    }
}

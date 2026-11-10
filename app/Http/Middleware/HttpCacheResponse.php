<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpCacheResponse
{
    /**
     * Handle public page HTTP response caching (PRD 7.1).
     * Adds cache-control headers for public visitors and strict no-cache headers for preview mode.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        if (! $request->isMethod('GET')) {
            return $response;
        }

        $allowDraftPreview = (bool) $request->attributes->get('allowDraftPreview', false);

        if ($allowDraftPreview) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');

            return $response;
        }

        // Only cache successful public HTML/Inertia responses
        if ($response->getStatusCode() === 200) {
            $response->headers->set('Cache-Control', 'public, max-age=300, stale-while-revalidate=600');
        }

        return $response;
    }
}

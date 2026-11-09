<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domain\Company\Actions\ResolveCompanyAction;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ResolveCompany
{
    public function __construct(
        private readonly ResolveCompanyAction $resolveCompanyAction,
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $isAdmin = $user instanceof User && $user->hasAnyRole([User::ROLE_SUPER_ADMIN, User::ROLE_EDITOR]);
        $allowDraftPreview = $isAdmin && $request->boolean('preview');

        $request->attributes->set('allowDraftPreview', $allowDraftPreview);

        $companySlug = $request->route('company');
        $slug = is_string($companySlug) && $companySlug !== '' ? $companySlug : null;

        if ($slug !== null) {
            $company = $this->resolveCompanyAction->execute($slug, $allowDraftPreview);
            $request->attributes->set('company', $company);
        } else {
            try {
                if (Schema::hasTable('companies')) {
                    $holding = $this->resolveCompanyAction->execute(null, true);
                    $request->attributes->set('company', $holding);
                }
            } catch (Throwable) {
                // Fallback to default Inertia shared props when database is unseeded.
            }
        }

        $response = $next($request);

        if ($allowDraftPreview) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }
}

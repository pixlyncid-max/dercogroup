<?php

declare(strict_types=1);

use App\Domain\Company\Models\Company;
use App\Domain\Company\Resources\CompanyResource;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\HttpCacheResponse;
use App\Http\Middleware\ResolveCompany;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->web(append: [
            SecurityHeaders::class,
            SetLocale::class,
            ResolveCompany::class,
            HandleInertiaRequests::class,
            HttpCacheResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->isMethod('GET') && ! $request->expectsJson() && ! $request->is('admin*') && ! $request->is('api*')) {
                $status = 500;
                if ($e instanceof HttpExceptionInterface) {
                    $status = $e->getStatusCode();
                }

                if (in_array($status, [403, 404, 500, 503], true)) {
                    $locale = App::getLocale() ?: 'id';
                    $theme = (string) ($request->hasSession() ? ($request->session()->get('last_visited_theme') ?? 'holding') : 'holding');

                    $company = null;
                    try {
                        if (Schema::hasTable('companies')) {
                            $company = Company::query()->where('theme', $theme)->first();
                        }
                    } catch (Throwable) {
                        $company = null;
                    }
                    $companyPayload = $company instanceof Company
                        ? (new CompanyResource($company))->resolve()
                        : [
                            'name' => 'PT Derco Bara Energy',
                            'slug' => 'derco-bara-energy',
                            'theme' => $theme,
                            'status' => 'published',
                            'is_holding' => true,
                        ];

                    return Inertia::render('Error', [
                        'status' => $status,
                        'theme' => $theme,
                        'locale' => $locale,
                        'company' => $companyPayload,
                    ])->toResponse($request)->setStatusCode($status);
                }
            }

            return null;
        });
    })->create();

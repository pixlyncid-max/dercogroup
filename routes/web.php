<?php

declare(strict_types=1);

use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\HoldingPageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SubsidiaryPageController;
use App\Http\Middleware\ResolveCompany;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'redirectRoot'])->name('root');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::prefix('{locale}')
    ->whereIn('locale', SetLocale::SUPPORTED_LOCALES)
    ->middleware([SetLocale::class, ResolveCompany::class])
    ->group(function (): void {
        // 301 Redirects for legacy contact slugs
        Route::get('/kontak', fn (string $locale) => redirect($locale === 'en' ? '/en/contact-us' : '/id/hubungi-kami', 301));
        Route::get('/contact', fn (string $locale) => redirect($locale === 'en' ? '/en/contact-us' : '/id/hubungi-kami', 301));

        Route::get('/', [HoldingPageController::class, 'home'])->name('holding.home');

        Route::post('/contact-messages', [ContactMessageController::class, 'store'])
            ->middleware('throttle:contact-submissions')
            ->name('contact.store');

        Route::post('/{businessSegment}/{company}/contact-messages', [ContactMessageController::class, 'store'])
            ->whereIn('businessSegment', ['bisnis-kami', 'our-business'])
            ->middleware('throttle:contact-submissions')
            ->name('subsidiary.contact.store');

        Route::get('/{businessSegment}/{company}/{productsSegment}/{productSlug}', [SubsidiaryPageController::class, 'product'])
            ->whereIn('businessSegment', ['bisnis-kami', 'our-business'])
            ->name('subsidiary.product');

        Route::get('/{businessSegment}/{company}/{pageSlug}', [SubsidiaryPageController::class, 'show'])
            ->whereIn('businessSegment', ['bisnis-kami', 'our-business'])
            ->name('subsidiary.page');

        Route::get('/{businessSegment}/{company}', [SubsidiaryPageController::class, 'home'])
            ->whereIn('businessSegment', ['bisnis-kami', 'our-business'])
            ->name('subsidiary.home');

        Route::get('/{pageSlug}', [HoldingPageController::class, 'show'])->name('holding.page');
    });

<?php

declare(strict_types=1);

use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\HoldingPageController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\ResolveCompany;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'redirectRoot'])->name('root');

Route::prefix('{locale}')
    ->whereIn('locale', SetLocale::SUPPORTED_LOCALES)
    ->middleware([SetLocale::class, ResolveCompany::class])
    ->group(function (): void {
        Route::get('/', [HoldingPageController::class, 'home'])->name('holding.home');

        Route::post('/contact-messages', [ContactMessageController::class, 'store'])
            ->middleware('throttle:contact-submissions')
            ->name('contact.store');

        Route::post('/{businessSegment}/{company}/contact-messages', [ContactMessageController::class, 'store'])
            ->whereIn('businessSegment', ['bisnis-kami', 'our-business'])
            ->middleware('throttle:contact-submissions')
            ->name('subsidiary.contact.store');

        Route::get('/{pageSlug}', [HoldingPageController::class, 'show'])->name('holding.page');
    });

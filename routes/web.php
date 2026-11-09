<?php

declare(strict_types=1);

use App\Http\Controllers\HomeController;
use App\Http\Middleware\ResolveCompany;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'redirectRoot'])->name('root');

Route::prefix('{locale}')
    ->whereIn('locale', SetLocale::SUPPORTED_LOCALES)
    ->middleware([SetLocale::class, ResolveCompany::class])
    ->group(function (): void {
        Route::get('/', [HomeController::class, 'index'])->name('holding.home');
    });

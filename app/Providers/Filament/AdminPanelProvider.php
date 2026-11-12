<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\DashboardGreetingWidget;
use App\Filament\Widgets\HeroBannerWidget;
use App\Filament\Widgets\QuickActionsWidget;
use App\Filament\Widgets\RecentActivityWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\WebsiteOverviewWidget;
use App\Support\FilamentThemeResolver;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->theme(FilamentThemeResolver::make())
            ->login(Login::class)
            ->passwordReset()
            ->darkMode(false)
            ->brandName('Derco Bara Energy CMS')
            ->brandLogo(fn () => view('filament.brand'))
            ->brandLogoHeight('2.25rem')
            ->sidebarWidth('17.5rem')
            ->sidebarCollapsibleOnDesktop(false)
            ->collapsibleNavigationGroups(false)
            ->navigationGroups([
                NavigationGroup::make('Komunikasi & Pesan')->collapsible(false),
                NavigationGroup::make('Struktur & Halaman')->collapsible(false),
                NavigationGroup::make('Konten Operasional')->collapsible(false),
                NavigationGroup::make('Sistem & Hak Akses')->collapsible(false),
            ])
            ->maxContentWidth(Width::Full)
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn () => view('filament.head'),
            )
            ->renderHook(
                PanelsRenderHook::SIDEBAR_FOOTER,
                fn () => view('filament.sidebar-footer'),
            )
            ->colors([
                'primary' => [
                    50 => '#FDF9EF',
                    100 => '#FAF2DA',
                    200 => '#F4E3B4',
                    300 => '#EDD189',
                    400 => '#E5BE5E',
                    500 => '#EDB856',
                    600 => '#D49E35',
                    700 => '#AA7820',
                    800 => '#815616',
                    900 => '#5B3B0E',
                    950 => '#392305',
                ],
                'gray' => Color::Slate,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                DashboardGreetingWidget::class,
                StatsOverviewWidget::class,
                HeroBannerWidget::class,
                QuickActionsWidget::class,
                WebsiteOverviewWidget::class,
                RecentActivityWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

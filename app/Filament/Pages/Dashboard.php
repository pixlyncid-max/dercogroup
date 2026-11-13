<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardGreetingWidget;
use App\Filament\Widgets\HeroBannerWidget;
use App\Filament\Widgets\QuickActionsWidget;
use App\Filament\Widgets\RecentActivityWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\WebsiteOverviewWidget;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Schema;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    protected static bool $shouldRegisterNavigation = false;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(): void
    {
        $tenant = Filament::getTenant();
        if ($tenant && ! app()->runningUnitTests()) {
            redirect()->to(EditWebsitePage::getUrl(['tenant' => $tenant, 'pageKey' => 'beranda']));
        }
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'lg' => 12,
            ])
            ->components([
                Livewire::make(DashboardGreetingWidget::class)
                    ->columnSpan(['default' => 1, 'lg' => 12]),

                Livewire::make(StatsOverviewWidget::class)
                    ->columnSpan(['default' => 1, 'lg' => 12]),

                Livewire::make(HeroBannerWidget::class)
                    ->columnSpan(['default' => 1, 'lg' => 8]),

                Livewire::make(QuickActionsWidget::class)
                    ->columnSpan(['default' => 1, 'lg' => 4]),

                Livewire::make(WebsiteOverviewWidget::class)
                    ->columnSpan(['default' => 1, 'lg' => 7]),

                Livewire::make(RecentActivityWidget::class)
                    ->columnSpan(['default' => 1, 'lg' => 5]),
            ]);
    }

    public function getWidgets(): array
    {
        return [
            DashboardGreetingWidget::class,
            StatsOverviewWidget::class,
            HeroBannerWidget::class,
            QuickActionsWidget::class,
            WebsiteOverviewWidget::class,
            RecentActivityWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'sm' => 1,
            'md' => 1,
            'lg' => 12,
            'xl' => 12,
            '2xl' => 12,
        ];
    }
}

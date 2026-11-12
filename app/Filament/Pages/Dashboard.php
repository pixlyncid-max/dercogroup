<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardGreetingWidget;
use App\Filament\Widgets\HeroBannerWidget;
use App\Filament\Widgets\QuickActionsWidget;
use App\Filament\Widgets\RecentActivityWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\WebsiteOverviewWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

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
        return 12;
    }
}

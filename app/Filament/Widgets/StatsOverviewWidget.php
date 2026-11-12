<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domain\Operation\Models\Operation;
use App\Domain\Page\Models\Page;
use App\Domain\Product\Models\Product;
use App\Models\User;
use Filament\Widgets\Widget;

class StatsOverviewWidget extends Widget
{
    protected string $view = 'filament.widgets.stats-overview';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public function getMetrics(): array
    {
        return [
            [
                'label' => 'Total Pages',
                'value' => (string) Page::query()->count(),
                'trend' => '↑ +2%',
                'icon' => 'page',
                'color' => 'blue',
            ],
            [
                'label' => 'Total Products',
                'value' => (string) Product::query()->count(),
                'trend' => '↑ +5%',
                'icon' => 'product',
                'color' => 'amber',
            ],
            [
                'label' => 'Total Operations',
                'value' => (string) Operation::query()->count(),
                'trend' => '↑ +8%',
                'icon' => 'operation',
                'color' => 'purple',
            ],
            [
                'label' => 'Total Users',
                'value' => (string) User::query()->count(),
                'trend' => '↑ +0%',
                'icon' => 'user',
                'color' => 'emerald',
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WebsiteOverviewWidget extends Widget
{
    protected string $view = 'filament.widgets.website-overview';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'lg' => 7,
    ];

    public function getChartData(): array
    {
        return [
            ['label' => 'Oct 12', 'value' => 5800],
            ['label' => 'Oct 19', 'value' => 9200],
            ['label' => 'Oct 26', 'value' => 6400],
            ['label' => 'Nov 02', 'value' => 11800],
            ['label' => 'Nov 09', 'value' => 13400],
        ];
    }
}

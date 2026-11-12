<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class HeroBannerWidget extends Widget
{
    protected string $view = 'filament.widgets.hero-banner';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'lg' => 8,
    ];
}

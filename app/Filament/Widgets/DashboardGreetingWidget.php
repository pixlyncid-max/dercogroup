<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class DashboardGreetingWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-greeting';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public function getGreeting(): string
    {
        $hour = (int) Carbon::now()->format('H');

        if ($hour >= 5 && $hour < 12) {
            return 'Good Morning';
        }

        if ($hour >= 12 && $hour < 18) {
            return 'Good Afternoon';
        }

        return 'Good Evening';
    }

    public function getUserName(): string
    {
        return auth()->user()?->name ?? 'Administrator';
    }

    public function getFormattedDate(): string
    {
        return Carbon::now()->format('l, d F Y');
    }

    public function getFormattedTime(): string
    {
        return Carbon::now()->format('h:i A');
    }
}

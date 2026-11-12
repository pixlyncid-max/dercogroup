<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domain\Contact\Models\ContactMessage;
use App\Domain\Page\Models\Page;
use Filament\Widgets\Widget;

class RecentActivityWidget extends Widget
{
    protected string $view = 'filament.widgets.recent-activity';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = [
        'default' => 12,
        'lg' => 5,
    ];

    public function getActivities(): array
    {
        $activities = [];

        // 1. Recent contact inquiries
        $messages = ContactMessage::query()->latest()->take(2)->get();
        foreach ($messages as $msg) {
            $activities[] = [
                'title' => 'New inquiry received',
                'description' => "From {$msg->name} (".($msg->company?->name ?? 'General Inquiry').')',
                'time' => $msg->created_at?->diffForHumans() ?? 'Recently',
                'icon' => 'mail',
            ];
        }

        // 2. Recent page updates
        $pages = Page::query()->latest('updated_at')->take(2)->get();
        foreach ($pages as $p) {
            $activities[] = [
                'title' => 'Page updated',
                'description' => $p->translateField('title', 'id').' ('.($p->company?->name ?? 'Holding').')',
                'time' => $p->updated_at?->diffForHumans() ?? 'Recently',
                'icon' => 'page',
            ];
        }

        // Fallback default sample if empty
        if (empty($activities)) {
            $activities = [
                [
                    'title' => 'Content synchronized',
                    'description' => 'PT Derco Bara Energy group pages and subsidiaries published',
                    'time' => '1h ago',
                    'icon' => 'page',
                ],
                [
                    'title' => 'Branding verified',
                    'description' => 'Official logo variants for holding and subsidiaries active',
                    'time' => '3h ago',
                    'icon' => 'banner',
                ],
            ];
        }

        return $activities;
    }
}

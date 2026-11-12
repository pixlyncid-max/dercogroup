<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domain\Company\Models\Company;
use App\Domain\Contact\Models\ContactMessage;
use App\Domain\Page\Models\Page;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;

class RecentActivityWidget extends Widget
{
    protected string $view = 'filament.widgets.recent-activity';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'lg' => 5,
    ];

    public function getActivities(): array
    {
        $tenant = Filament::getTenant();
        $activities = [];

        // 1. Recent contact inquiries
        $messagesQuery = ContactMessage::query()->latest();
        if ($tenant instanceof Company) {
            $messagesQuery->where('company_id', $tenant->id);
        }
        $messages = $messagesQuery->take(2)->get();
        foreach ($messages as $msg) {
            $activities[] = [
                'title' => 'Pesan Masuk Baru',
                'description' => "Dari {$msg->name} (".($msg->subject ?: 'Pertanyaan').')',
                'time' => $msg->created_at?->diffForHumans() ?? 'Baru saja',
                'icon' => 'mail',
            ];
        }

        // 2. Recent page updates
        $pagesQuery = Page::query()->latest('updated_at');
        if ($tenant instanceof Company) {
            $pagesQuery->where('company_id', $tenant->id);
        }
        $pages = $pagesQuery->take(2)->get();
        foreach ($pages as $p) {
            $activities[] = [
                'title' => 'Halaman Diperbarui',
                'description' => $p->translateField('title', 'id').' ('.($p->company?->name ?? 'Holding').')',
                'time' => $p->updated_at?->diffForHumans() ?? 'Baru saja',
                'icon' => 'page',
            ];
        }

        // Fallback default sample if empty
        if (empty($activities)) {
            $activities = [
                [
                    'title' => 'Konten Tersinkronisasi',
                    'description' => 'Halaman grup PT Derco Bara Energy dan anak usaha aktif',
                    'time' => '1 jam lalu',
                    'icon' => 'page',
                ],
                [
                    'title' => 'Branding Terverifikasi',
                    'description' => 'Varian logo resmi holding dan anak perusahaan aktif',
                    'time' => '3 jam lalu',
                    'icon' => 'brand',
                ],
            ];
        }

        return $activities;
    }
}

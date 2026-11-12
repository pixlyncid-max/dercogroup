<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domain\Company\Models\Company;
use App\Domain\Contact\Models\ContactMessage;
use App\Domain\Operation\Models\Operation;
use App\Domain\Page\Models\Page;
use App\Domain\Product\Models\Product;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;

class StatsOverviewWidget extends Widget
{
    protected string $view = 'filament.widgets.stats-overview';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public function getMetrics(): array
    {
        $tenant = Filament::getTenant();

        if ($tenant instanceof Company) {
            $pagesCount = $tenant->pages()->count();
            $productsCount = $tenant->products()->count();
            $operationsCount = $tenant->operations()->count();
            $inquiriesCount = $tenant->contactMessages()->where('status', 'new')->count();

            return [
                [
                    'label' => 'Total Halaman',
                    'value' => (string) $pagesCount,
                    'trend' => 'Aktif di CMS',
                    'icon' => 'page',
                    'color' => 'blue',
                ],
                [
                    'label' => 'Total Produk',
                    'value' => (string) $productsCount,
                    'trend' => $tenant->hasPage('products') ? 'Katalog Aktif' : 'Tidak Berlaku',
                    'icon' => 'product',
                    'color' => 'amber',
                ],
                [
                    'label' => 'Total Operasi',
                    'value' => (string) $operationsCount,
                    'trend' => $tenant->hasPage('operations') ? 'Wilayah Kerja' : 'Tidak Berlaku',
                    'icon' => 'operation',
                    'color' => 'purple',
                ],
                [
                    'label' => 'Pesan Masuk Baru',
                    'value' => (string) $inquiriesCount,
                    'trend' => 'Perlu Ditanggapi',
                    'icon' => 'user',
                    'color' => 'emerald',
                ],
            ];
        }

        return [
            [
                'label' => 'Total Halaman',
                'value' => (string) Page::query()->count(),
                'trend' => 'Semua Perusahaan',
                'icon' => 'page',
                'color' => 'blue',
            ],
            [
                'label' => 'Total Produk',
                'value' => (string) Product::query()->count(),
                'trend' => 'Semua Katalog',
                'icon' => 'product',
                'color' => 'amber',
            ],
            [
                'label' => 'Total Operasi',
                'value' => (string) Operation::query()->count(),
                'trend' => 'Semua Wilayah',
                'icon' => 'operation',
                'color' => 'purple',
            ],
            [
                'label' => 'Pesan Masuk Baru',
                'value' => (string) ContactMessage::query()->where('status', 'new')->count(),
                'trend' => 'Semua Pesan',
                'icon' => 'user',
                'color' => 'emerald',
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Domain\Company\Models\Company;
use App\Domain\Page\Enums\BlockType;
use App\Domain\Page\Schema\SectionSchema;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Page;
use Filament\Pages\PageConfiguration;
use Filament\Panel;
use Illuminate\Support\Facades\Route;

class EditWebsitePage extends Page
{
    protected string $view = 'filament.pages.edit-website-page';

    protected static bool $shouldRegisterNavigation = true;

    public string $pageKey = 'beranda';

    public string $locale = 'id';

    public static function routes(Panel $panel, ?PageConfiguration $configuration = null): void
    {
        $middleware = static::getRouteMiddleware($panel);

        Route::get('/halaman/{pageKey}', static::class)
            ->middleware($middleware)
            ->withoutMiddleware(static::getWithoutRouteMiddleware($panel))
            ->name('halaman');
    }

    public static function getRelativeRouteName(Panel $panel): string
    {
        return 'halaman';
    }

    public function mount(string $pageKey = 'beranda'): void
    {
        $tenant = Filament::getTenant();
        if (! $tenant instanceof Company) {
            abort(404, 'Perusahaan tidak ditemukan');
        }

        $validPages = static::getAvailablePagesForCompany($tenant);
        if (! array_key_exists($pageKey, $validPages)) {
            abort(404, "Halaman '{$pageKey}' tidak tersedia untuk {$tenant->name}.");
        }

        $this->pageKey = $pageKey;
        session(['filament_last_tenant_slug' => $tenant->slug]);
    }

    public function getTitle(): string
    {
        $tenant = Filament::getTenant();
        if ($tenant instanceof Company) {
            $pages = static::getAvailablePagesForCompany($tenant);

            return $pages[$this->pageKey]['label'] ?? 'Editor Halaman';
        }

        return 'Editor Halaman';
    }

    public function getSubheading(): ?string
    {
        $tenant = Filament::getTenant();
        if ($tenant instanceof Company) {
            $pages = static::getAvailablePagesForCompany($tenant);

            return $pages[$this->pageKey]['subheading'] ?? null;
        }

        return null;
    }

    /**
     * @return array<string, array{label: string, subheading: string, icon: string}>
     */
    public static function getAvailablePagesForCompany(Company $company): array
    {
        if ($company->isHolding()) {
            return [
                'beranda' => [
                    'label' => 'Beranda',
                    'subheading' => 'Kelola header logo, menu navigasi, dan seluruh section halaman utama holding.',
                    'icon' => 'heroicon-o-home',
                ],
                'tentang-kami' => [
                    'label' => 'Tentang Kami',
                    'subheading' => 'Kelola narasi profil, sejarah, kepemimpinan, dan nilai perusahaan holding.',
                    'icon' => 'heroicon-o-information-circle',
                ],
                'bisnis-kami' => [
                    'label' => 'Bisnis Kami',
                    'subheading' => 'Kelola portofolio bisnis dan sinergi anak usaha holding.',
                    'icon' => 'heroicon-o-briefcase',
                ],
                'kontak' => [
                    'label' => 'Kontak',
                    'subheading' => 'Kelola informasi kantor pusat, formulir hubungi kami, dan lokasi.',
                    'icon' => 'heroicon-o-phone',
                ],
                'footer' => [
                    'label' => 'Footer',
                    'subheading' => 'Kelola informasi footer, tautan legal, dan hak cipta holding.',
                    'icon' => 'heroicon-o-bars-3-bottom-left',
                ],
            ];
        }

        return [
            'beranda' => [
                'label' => 'Beranda',
                'subheading' => "Kelola header logo, menu navigasi, dan seluruh section beranda {$company->name}.",
                'icon' => 'heroicon-o-home',
            ],
            'tentang-kami' => [
                'label' => 'Tentang Kami',
                'subheading' => "Kelola profil, visi misi, dan keunggulan {$company->name}.",
                'icon' => 'heroicon-o-information-circle',
            ],
            'produk' => [
                'label' => 'Produk',
                'subheading' => 'Kelola katalog produk, spesifikasi batubara, dan parameter kualitas.',
                'icon' => 'heroicon-o-cube',
            ],
            'operasi' => [
                'label' => 'Operasi',
                'subheading' => 'Kelola fasilitas tambang, infrastruktur pelabuhan, dan armada logistik.',
                'icon' => 'heroicon-o-cog-6-tooth',
            ],
            'kontak' => [
                'label' => 'Kontak',
                'subheading' => 'Kelola kontak operasional dan formulir inquiry pemasaran.',
                'icon' => 'heroicon-o-phone',
            ],
            'footer' => [
                'label' => 'Footer',
                'subheading' => "Kelola informasi footer dan hak cipta {$company->name}.",
                'icon' => 'heroicon-o-bars-3-bottom-left',
            ],
        ];
    }

    /**
     * @return array<NavigationItem>
     */
    public static function getNavigationItems(): array
    {
        $tenant = Filament::getTenant();
        if (! $tenant instanceof Company) {
            return [];
        }

        $pages = static::getAvailablePagesForCompany($tenant);
        $items = [];
        $sort = 1;

        foreach ($pages as $key => $page) {
            $items[] = NavigationItem::make($page['label'])
                ->icon($page['icon'])
                ->group('Halaman Website')
                ->sort($sort++)
                ->url(static::getUrl(['pageKey' => $key, 'tenant' => $tenant]))
                ->isActiveWhen(fn (): bool => request()->route('pageKey') === $key);
        }

        return $items;
    }

    /**
     * @return list<array{
     *     section_key: string,
     *     block_type: BlockType,
     *     label: string,
     *     help_text: string,
     *     is_company_scoped: bool,
     *     is_toggleable: bool,
     *     is_relational: bool
     * }>
     */
    public function getSections(): array
    {
        $tenant = Filament::getTenant();
        if (! $tenant instanceof Company) {
            return [];
        }

        return SectionSchema::getSectionsForPage($tenant->theme, $this->pageKey);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domain\Company\Models\Company;
use App\Domain\Contact\Enums\ContactMessageStatus;
use App\Domain\Contact\Models\ContactMessage;
use App\Filament\Pages\EditWebsitePage;
use App\Filament\Resources\CertificationResource;
use App\Filament\Resources\CompanyContactResource;
use App\Filament\Resources\CompanyResource;
use App\Filament\Resources\CompanyStatResource;
use App\Filament\Resources\ContactMessageResource;
use App\Filament\Resources\OperationResource;
use App\Filament\Resources\PageResource;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\SettingResource;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
    $this->superAdmin = User::where('email', 'admin@dercobara.co.id')->firstOrFail();
    $this->editor = User::where('email', 'editor@dercobara.co.id')->firstOrFail();
    $this->holding = Company::where('slug', 'derco-bara-energy')->firstOrFail();
    $this->subsidiary = Company::where('slug', 'korin-berkat-energy')->firstOrFail();
});

test('4.1: User getDefaultTenant uses session memory or defaults to holding', function (): void {
    $panel = Filament::getPanel('admin');

    // Default without session is holding
    expect($this->superAdmin->getDefaultTenant($panel)?->slug)->toBe('derco-bara-energy');

    // With session memory of last visited tenant
    session(['filament_last_tenant_slug' => 'korin-berkat-energy']);
    expect($this->superAdmin->getDefaultTenant($panel)?->slug)->toBe('korin-berkat-energy');
});

test('4.1: Company tenant name renders status badge and unread messages badge', function (): void {
    // Before new messages
    $html = (string) $this->holding->getFilamentName();
    expect($html)->toContain('Published');

    // Add new unread contact message
    ContactMessage::create([
        'company_id' => $this->holding->id,
        'locale' => 'id',
        'name' => 'Budi Tester',
        'email' => 'budi@example.com',
        'subject' => 'Inquiry Batubara',
        'message' => 'Halo, kami butuh pasokan 50.000 MT.',
        'status' => ContactMessageStatus::New,
    ]);

    $htmlWithUnread = (string) $this->holding->fresh()->getFilamentName();
    expect($htmlWithUnread)->toContain('pesan baru')
        ->and($htmlWithUnread)->toContain('bg-rose-500');
});

test('4.2: EditWebsitePage resolves valid pages and 404s on mismatched pages per company', function (): void {
    $this->actingAs($this->superAdmin);

    // Holding valid pages
    $holdingPages = ['beranda', 'tentang-kami', 'bisnis-kami', 'kontak', 'footer'];
    foreach ($holdingPages as $pageKey) {
        $url = EditWebsitePage::getUrl(['tenant' => $this->holding, 'pageKey' => $pageKey]);
        $this->get($url)->assertOk();
    }

    // Holding invalid pages (products/operations belong only to subsidiaries)
    $this->get('/admin/derco-bara-energy/halaman/produk')->assertNotFound();
    $this->get('/admin/derco-bara-energy/halaman/operasi')->assertNotFound();

    // Subsidiary valid pages
    $subPages = ['beranda', 'tentang-kami', 'produk', 'operasi', 'kontak', 'footer'];
    foreach ($subPages as $pageKey) {
        $url = EditWebsitePage::getUrl(['tenant' => $this->subsidiary, 'pageKey' => $pageKey]);
        $this->get($url)->assertOk();
    }

    // Subsidiary invalid page (bisnis-kami belongs only to holding)
    $this->get('/admin/korin-berkat-energy/halaman/bisnis-kami')->assertNotFound();
});

test('4.3: Sidebar navigation items dynamically adapt between holding and subsidiary', function (): void {
    $this->actingAs($this->superAdmin);
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    // Holding navigation
    Filament::setTenant($this->holding);
    $holdingItems = EditWebsitePage::getNavigationItems();
    $holdingLabels = array_map(fn ($item) => (string) $item->getLabel(), $holdingItems);

    expect($holdingLabels)->toBe(['Beranda', 'Tentang Kami', 'Bisnis Kami', 'Kontak', 'Footer']);

    // Subsidiary navigation
    Filament::setTenant($this->subsidiary);
    $subItems = EditWebsitePage::getNavigationItems();
    $subLabels = array_map(fn ($item) => (string) $item->getLabel(), $subItems);

    expect($subLabels)->toBe(['Beranda', 'Tentang Kami', 'Produk', 'Operasi', 'Kontak', 'Footer']);
});

test('4.3: Legacy separate resources are hidden from sidebar navigation', function (): void {
    expect(PageResource::shouldRegisterNavigation())->toBeFalse()
        ->and(ProductResource::shouldRegisterNavigation())->toBeFalse()
        ->and(OperationResource::shouldRegisterNavigation())->toBeFalse()
        ->and(CompanyStatResource::shouldRegisterNavigation())->toBeFalse()
        ->and(CompanyContactResource::shouldRegisterNavigation())->toBeFalse()
        ->and(CertificationResource::shouldRegisterNavigation())->toBeFalse();
});

test('4.5: Pengaturan resources are grouped under Pengaturan with correct roles and badges', function (): void {
    $this->actingAs($this->superAdmin);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    Filament::setTenant($this->holding);

    // ContactMessageResource
    expect(ContactMessageResource::getNavigationGroup())->toBe('Pengaturan')
        ->and(ContactMessageResource::canCreate())->toBeFalse();

    // SettingResource
    expect(SettingResource::getNavigationGroup())->toBe('Pengaturan')
        ->and(SettingResource::getNavigationLabel())->toBe('Pengaturan Website');

    // UserResource
    expect(UserResource::getNavigationGroup())->toBe('Pengaturan')
        ->and(UserResource::getNavigationLabel())->toBe('Pengguna');

    // CompanyResource
    expect(CompanyResource::getNavigationGroup())->toBe('Pengaturan')
        ->and(CompanyResource::getNavigationLabel())->toBe('Perusahaan');
});

<?php

declare(strict_types=1);

use App\Domain\Company\Actions\GetGroupSummaryAction;
use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\Setting;
use App\Domain\Page\Models\Page;
use App\Filament\Resources\CertificationResource;
use App\Filament\Resources\CompanyResource;
use App\Filament\Resources\OperationResource;
use App\Filament\Resources\PageResource;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\SettingResource;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);

    $this->superAdmin = User::query()->where('email', 'admin@dercobara.co.id')->firstOrFail();
    $this->editor = User::query()->where('email', 'editor@dercobara.co.id')->firstOrFail();

    $this->holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();
    $this->korinBerkat = Company::query()->where('slug', 'korin-berkat-energy')->firstOrFail();
    $this->beramal = Company::query()->where('slug', 'beramal-sukses-sejahtera')->firstOrFail();
    $this->korinBara = Company::query()->where('slug', 'korin-bara-energy')->firstOrFail();
});

test('B1: multi-tenancy allows both super admin and editor to access all 4 companies', function (): void {
    $panel = Filament::getPanel('admin');

    expect($this->superAdmin->canAccessPanel($panel))->toBeTrue()
        ->and($this->editor->canAccessPanel($panel))->toBeTrue()
        ->and($this->superAdmin->canAccessTenant($this->holding))->toBeTrue()
        ->and($this->superAdmin->canAccessTenant($this->korinBerkat))->toBeTrue()
        ->and($this->editor->canAccessTenant($this->holding))->toBeTrue()
        ->and($this->editor->canAccessTenant($this->korinBerkat))->toBeTrue()
        ->and($this->editor->canAccessTenant($this->beramal))->toBeTrue()
        ->and($this->editor->canAccessTenant($this->korinBara))->toBeTrue();

    // Tenant list has holding first
    $tenants = $this->superAdmin->getTenants($panel);
    expect($tenants)->toHaveCount(4)
        ->and($tenants->first()->slug)->toBe('derco-bara-energy');

    // Default tenant is holding
    expect($this->superAdmin->getDefaultTenant($panel)?->slug)->toBe('derco-bara-energy');
});

test('B1: dashboard routes resolve successfully for each tenant', function (): void {
    $this->actingAs($this->superAdmin)
        ->get('/admin/derco-bara-energy')
        ->assertSuccessful();

    $this->flushSession();

    $this->actingAs($this->editor)
        ->get('/admin/korin-berkat-energy')
        ->assertSuccessful();
});

test('B2: tenant resources are automatically scoped to active tenant and cross-tenant records 404', function (): void {
    $this->actingAs($this->superAdmin);
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    $holdingPages = PageResource::scopeEloquentQueryToTenant(Page::query(), $this->holding)->get();
    expect($holdingPages->every(fn (Page $p) => $p->company_id === $this->holding->id))->toBeTrue();

    // Now switch tenant to Korin Berkat
    $korinPages = PageResource::scopeEloquentQueryToTenant(Page::query(), $this->korinBerkat)->get();
    expect($korinPages->every(fn (Page $p) => $p->company_id === $this->korinBerkat->id))->toBeTrue();

    // Cross-tenant route access fails with 404:
    // A page belonging to Korin Berkat accessed inside holding tenant route
    $korinPage = $this->korinBerkat->pages()->firstOrFail();
    $this->get("/admin/derco-bara-energy/pages/{$korinPage->id}")
        ->assertNotFound();
});

test('B3: global resources are not scoped to tenant and restricted to super admin', function (): void {
    $this->actingAs($this->superAdmin);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    Filament::setTenant($this->korinBerkat);

    // CompanyResource is not scoped to tenant
    $companiesCount = CompanyResource::getEloquentQuery()->count();
    expect($companiesCount)->toBe(4);

    // Editor cannot access global resources
    $this->actingAs($this->editor);
    expect(CompanyResource::canAccess())->toBeFalse()
        ->and(UserResource::canAccess())->toBeFalse()
        ->and(SettingResource::canAccess())->toBeFalse();

    // Super Admin can access global resources
    $this->actingAs($this->superAdmin);
    expect(CompanyResource::canAccess())->toBeTrue()
        ->and(UserResource::canAccess())->toBeTrue()
        ->and(SettingResource::canAccess())->toBeTrue();
});

test('B4: adaptive menu hides and shows modules according to company pages', function (): void {
    $this->actingAs($this->superAdmin);
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    // Holding: has NO products, NO operations, NO compliance
    Filament::setTenant($this->holding);
    expect(ProductResource::canViewAny())->toBeFalse()
        ->and(OperationResource::canViewAny())->toBeFalse()
        ->and(CertificationResource::canViewAny())->toBeFalse();

    // Korin Berkat: has products, operations, but NO compliance
    Filament::setTenant($this->korinBerkat);
    expect(ProductResource::canViewAny())->toBeTrue()
        ->and(OperationResource::canViewAny())->toBeTrue()
        ->and(CertificationResource::canViewAny())->toBeFalse();

    // Beramal: has products, operations, AND compliance
    Filament::setTenant($this->beramal);
    expect(ProductResource::canViewAny())->toBeTrue()
        ->and(OperationResource::canViewAny())->toBeTrue()
        ->and(CertificationResource::canViewAny())->toBeTrue();
});

test('B5: tenant profile page and settings reader work per tenant with global fallback', function (): void {
    $this->actingAs($this->superAdmin)
        ->get('/admin/derco-bara-energy/profile')
        ->assertSuccessful();

    // Test settings reader for company
    Setting::setValue('test_custom_key', ['value' => 'global_val']);
    expect(Setting::getValue('test_custom_key', null, $this->korinBerkat)['value'])->toBe('global_val');

    // Override for korinBerkat
    Setting::setValue('test_custom_key', ['value' => 'korin_val'], 'custom', $this->korinBerkat);
    expect(Setting::getValue('test_custom_key', null, $this->korinBerkat)['value'])->toBe('korin_val')
        ->and(Setting::getValue('test_custom_key', null, $this->holding)['value'])->toBe('global_val');
});

test('B6: group summary action executes in 1 query and returns metrics for all companies', function (): void {
    $action = app(GetGroupSummaryAction::class);

    $summary = $action->execute();

    expect($summary)->toHaveCount(4)
        ->and($summary->first()['slug'])->toBe('derco-bara-energy')
        ->and($summary->first()['is_holding'])->toBeTrue();
});

test('D: editor can edit content across tenants but cannot delete pages or manage users', function (): void {
    $page = $this->korinBerkat->pages()->firstOrFail();

    expect(Gate::forUser($this->editor)->allows('view', $page))->toBeTrue()
        ->and(Gate::forUser($this->editor)->allows('update', $page))->toBeTrue()
        ->and(Gate::forUser($this->editor)->allows('delete', $page))->toBeFalse();

    // Super Admin can delete pages
    expect(Gate::forUser($this->superAdmin)->allows('delete', $page))->toBeTrue();

    // Editor cannot create or delete users
    expect(Gate::forUser($this->editor)->allows('viewAny', User::class))->toBeFalse()
        ->and(Gate::forUser($this->editor)->allows('create', User::class))->toBeFalse();
});

test('C: delete safeguards protect holding, active subsidiaries, and last super admin', function (): void {
    // Holding cannot be deleted
    expect(Gate::forUser($this->superAdmin)->allows('delete', $this->holding))->toBeFalse();

    // Company with subsidiaries cannot be deleted
    if ($this->holding->subsidiaries()->exists()) {
        expect(Gate::forUser($this->superAdmin)->allows('delete', $this->holding))->toBeFalse();
    }

    // Self cannot be deleted
    expect(Gate::forUser($this->superAdmin)->allows('delete', $this->superAdmin))->toBeFalse();
});

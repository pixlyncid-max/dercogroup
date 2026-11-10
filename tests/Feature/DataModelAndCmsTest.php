<?php

declare(strict_types=1);

use App\Domain\Company\Actions\ResolveCompanyAction;
use App\Domain\Company\Enums\CompanyStatus;
use App\Domain\Company\Models\Company;
use App\Domain\Page\Actions\GetCompanyPageAction;
use App\Domain\Page\Actions\PageResolver;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Page\Models\Page;
use App\Domain\Product\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
});

it('seeds holding and 3 subsidiaries with 21 total pages and hides draft company from public queries', function (): void {
    expect(Company::query()->count())->toBe(4)
        ->and(Page::query()->count())->toBe(21)
        ->and(Product::query()->count())->toBe(9);

    // Public scopes exclude PT Korin Bara Energy (draft)
    $publishedSlugs = Company::query()->published()->pluck('slug')->all();
    expect($publishedSlugs)
        ->toContain('derco-bara-energy', 'korin-berkat-energy', 'beramal-sukses-sejahtera')
        ->not->toContain('korin-bara-energy');

    // Draft company's 6 pages, deleted Korin Berkat compliance, and unpublished Beramal compliance
    // leave 14 published pages (Holding: 4, Korin Berkat: 5, Beramal: 5)
    expect(Page::query()->published()->count())->toBe(14)
        ->and(Product::query()->published()->count())->toBe(6);

    // ResolveCompanyAction rejects draft company for public visitors
    $resolver = app(ResolveCompanyAction::class);
    expect(fn () => $resolver->execute('korin-bara-energy', false))
        ->toThrow(ModelNotFoundException::class);

    // ResolveCompanyAction allows draft company in preview mode
    $previewCompany = $resolver->execute('korin-bara-energy', true);
    expect($previewCompany->slug)->toBe('korin-bara-energy')
        ->and($previewCompany->status)->toBe(CompanyStatus::Draft);
});

it('immediately reflects status change of Korin Bara in public queries without code changes', function (): void {
    $holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();
    $korinBara = Company::query()->where('slug', 'korin-bara-energy')->firstOrFail();

    expect($holding->publishedSubsidiaries()->count())->toBe(2);

    $korinBara->update(['status' => CompanyStatus::Published]);

    // 14 previously published + 6 from Korin Bara = 20 published pages
    expect($holding->publishedSubsidiaries()->count())->toBe(3)
        ->and(Page::query()->published()->count())->toBe(20)
        ->and(Product::query()->published()->count())->toBe(9);
});

it('resolves translated slugs in both Indonesian and English via PageResolver', function (): void {
    $pageResolver = app(PageResolver::class);
    $holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();
    $berkat = Company::query()->where('slug', 'korin-berkat-energy')->firstOrFail();

    $aboutId = $pageResolver->resolveBySlug($holding, 'tentang-kami', 'id');
    $aboutEn = $pageResolver->resolveBySlug($holding, 'about-us', 'en');
    expect($aboutId->id)->toBe($aboutEn->id)
        ->and($aboutId->key)->toBe(PageKey::About);

    $productsId = $pageResolver->resolveBySlug($berkat, 'produk', 'id');
    $productsEn = $pageResolver->resolveBySlug($berkat, 'products', 'en');
    expect($productsId->id)->toBe($productsEn->id)
        ->and($productsId->key)->toBe(PageKey::Products);

    $alternates = $pageResolver->alternateUrls($berkat, $productsId);
    expect($alternates)->toEqual([
        'id' => '/id/bisnis-kami/korin-berkat-energy/produk',
        'en' => '/en/our-business/korin-berkat-energy/products',
    ]);

    $previewUrl = $pageResolver->pageUrl($berkat, $productsId, 'id', true);
    expect($previewUrl)->toBe('/id/bisnis-kami/korin-berkat-energy/produk?preview=1');
});

it('assembles complete page payload via GetCompanyPageAction', function (): void {
    $holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();
    $action = app(GetCompanyPageAction::class);

    $payload = $action->execute($holding, PageKey::Home, 'id');

    expect($payload['company']['slug'])->toBe('derco-bara-energy')
        ->and($payload['page']['key'])->toBe('home')
        ->and($payload['blocks'])->toHaveCount(6)
        ->and($payload['subsidiaries'])->toHaveCount(2);
});

it('allows Super Admin to access all Filament CMS resources', function (): void {
    $superAdmin = User::query()->where('email', 'admin@dercobara.co.id')->firstOrFail();

    $this->actingAs($superAdmin)
        ->get('/admin/companies')
        ->assertOk();

    $this->actingAs($superAdmin)
        ->get('/admin/users')
        ->assertOk();

    $this->actingAs($superAdmin)
        ->get('/admin/settings')
        ->assertOk();
});

it('allows Editor to access content resources while restricting Users and Settings in Filament CMS', function (): void {
    $editor = User::query()->where('email', 'editor@dercobara.co.id')->firstOrFail();

    $this->actingAs($editor)
        ->get('/admin/companies')
        ->assertOk();

    $this->actingAs($editor)
        ->get('/admin/users')
        ->assertForbidden();

    $this->actingAs($editor)
        ->get('/admin/settings')
        ->assertForbidden();
});

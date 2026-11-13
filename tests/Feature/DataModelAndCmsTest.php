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

it('seeds holding and 3 subsidiaries with 19 total pages and published Korin Bara', function (): void {
    expect(Company::query()->count())->toBe(4)
        ->and(Page::query()->count())->toBe(19)
        ->and(Product::query()->count())->toBe(9);

    $publishedSlugs = Company::query()->published()->pluck('slug')->all();
    expect($publishedSlugs)
        ->toContain('derco-bara-energy', 'korin-berkat-energy', 'beramal-sukses-sejahtera', 'korin-bara-energy');

    expect(Page::query()->published()->count())->toBe(19)
        ->and(Product::query()->published()->count())->toBe(9);
});

it('hides draft companies from public queries and allows preview mode (FR-VIS)', function (): void {
    $holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();
    $draftCompany = Company::factory()->draft()->subsidiary($holding)->create([
        'slug' => 'draft-exploration-energy',
        'name' => 'PT Draft Exploration Energy',
    ]);

    $publishedSlugs = Company::query()->published()->pluck('slug')->all();
    expect($publishedSlugs)->not->toContain('draft-exploration-energy');

    $resolver = app(ResolveCompanyAction::class);
    expect(fn () => $resolver->execute('draft-exploration-energy', false))
        ->toThrow(ModelNotFoundException::class);

    $previewCompany = $resolver->execute('draft-exploration-energy', true);
    expect($previewCompany->slug)->toBe('draft-exploration-energy')
        ->and($previewCompany->status)->toBe(CompanyStatus::Draft);

    // Status change to published reflects immediately
    $draftCompany->update(['status' => CompanyStatus::Published]);
    expect($holding->publishedSubsidiaries()->pluck('slug'))->toContain('draft-exploration-energy');
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
        ->and($payload['subsidiaries'])->toHaveCount(3);
});

it('allows Super Admin to access all Filament CMS resources', function (): void {
    $superAdmin = User::query()->where('email', 'admin@dercobara.co.id')->firstOrFail();

    $this->actingAs($superAdmin)
        ->get('/admin/derco-bara-energy/companies')
        ->assertOk();

    $this->actingAs($superAdmin)
        ->get('/admin/derco-bara-energy/users')
        ->assertOk();

    $this->actingAs($superAdmin)
        ->get('/admin/derco-bara-energy/settings')
        ->assertOk();
});

it('allows Editor to access content resources while restricting Companies, Users, and Settings in Filament CMS', function (): void {
    $editor = User::query()->where('email', 'editor@dercobara.co.id')->firstOrFail();

    $this->actingAs($editor)
        ->get('/admin/derco-bara-energy/pages')
        ->assertOk();

    $this->actingAs($editor)
        ->get('/admin/derco-bara-energy/companies')
        ->assertForbidden();

    $this->actingAs($editor)
        ->get('/admin/derco-bara-energy/users')
        ->assertForbidden();

    $this->actingAs($editor)
        ->get('/admin/derco-bara-energy/settings')
        ->assertForbidden();
});

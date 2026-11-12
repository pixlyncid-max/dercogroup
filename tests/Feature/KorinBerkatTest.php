<?php

declare(strict_types=1);

use App\Domain\Company\Models\Company;
use App\Domain\Contact\Models\ContactMessage;
use App\Domain\Product\Models\Product;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
});

it('renders all 5 active PT Korin Berkat Energy pages in Indonesian and English via SubsidiaryPageController', function (): void {
    $this->get('/id/bisnis-kami/korin-berkat-energy')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('locale', 'id')
            ->where('company.slug', 'korin-berkat-energy')
            ->where('company.theme', 'korin-berkat')
            ->where('page.key', 'home')
            ->has('blocks', 5)
            ->has('stats', 4)
            ->has('products', 3)
            ->has('operations', 4)
        );

    $this->get('/en/our-business/korin-berkat-energy')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('locale', 'en')
            ->where('company.slug', 'korin-berkat-energy')
            ->where('company.theme', 'korin-berkat')
            ->where('page.key', 'home')
        );

    $idSlugs = [
        'tentang-kami' => 'about',
        'produk' => 'products',
        'operasi' => 'operations',
        'kontak' => 'contact',
    ];

    foreach ($idSlugs as $slug => $expectedKey) {
        $this->get("/id/bisnis-kami/korin-berkat-energy/{$slug}")
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('ThemedPage')
                ->where('company.slug', 'korin-berkat-energy')
                ->where('page.key', $expectedKey)
            );
    }

    $enSlugs = [
        'about-us' => 'about',
        'products' => 'products',
        'operations' => 'operations',
        'contact' => 'contact',
    ];

    foreach ($enSlugs as $slug => $expectedKey) {
        $this->get("/en/our-business/korin-berkat-energy/{$slug}")
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('ThemedPage')
                ->where('company.slug', 'korin-berkat-energy')
                ->where('page.key', $expectedKey)
            );
    }

    // Compliance page has been deleted entirely for Korin Berkat
    $this->get('/id/bisnis-kami/korin-berkat-energy/mutu-dan-kepatuhan')->assertNotFound();
    $this->get('/en/our-business/korin-berkat-energy/quality-and-compliance')->assertNotFound();
});

it('resolves product detail page in Indonesian and English with specification table and alternate URLs', function (): void {
    $this->get('/id/bisnis-kami/korin-berkat-energy/produk/thermal-coal-gar-4200')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('locale', 'id')
            ->where('company.slug', 'korin-berkat-energy')
            ->where('product.slug', 'thermal-coal-gar-4200')
            ->has('product.specs', 5)
            ->where('navigation.alternateUrls.id', '/id/bisnis-kami/korin-berkat-energy/produk/thermal-coal-gar-4200')
            ->where('navigation.alternateUrls.en', '/en/our-business/korin-berkat-energy/products/thermal-coal-gar-4200')
        );

    $this->get('/en/our-business/korin-berkat-energy/products/thermal-coal-gar-4200')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('locale', 'en')
            ->where('company.slug', 'korin-berkat-energy')
            ->where('product.slug', 'thermal-coal-gar-4200')
            ->has('product.specs', 5)
        );
});

it('returns 404 for unknown products, unpublished products, and draft subsidiary pages or products', function (): void {
    $this->get('/id/bisnis-kami/korin-berkat-energy/produk/non-existent-grade')
        ->assertNotFound();

    // Unpublished product returns 404 for public visitors
    $korinBerkat = Company::query()->where('slug', 'korin-berkat-energy')->firstOrFail();
    Product::query()
        ->where('company_id', $korinBerkat->id)
        ->where('slug', 'thermal-coal-gar-3800')
        ->update(['is_published' => false]);

    $this->get('/id/bisnis-kami/korin-berkat-energy/produk/thermal-coal-gar-3800')
        ->assertNotFound();

    // Draft subsidiary returns 404 on home, subpages, and product detail
    $holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();
    $draftCompany = Company::factory()->draft()->subsidiary($holding)->create(['slug' => 'test-draft-sub']);
    $this->get('/id/bisnis-kami/test-draft-sub')->assertNotFound();
    $this->get('/en/our-business/test-draft-sub')->assertNotFound();
});

it('stores contact message linked to PT Korin Berkat Energy when submitted on subsidiary contact page', function (): void {
    Mail::fake();

    $korinBerkat = Company::query()->where('slug', 'korin-berkat-energy')->firstOrFail();

    $this->from('/id/bisnis-kami/korin-berkat-energy/kontak')
        ->post('/id/bisnis-kami/korin-berkat-energy/contact-messages', [
            'name' => 'Hendra Kusuma',
            'email' => 'hendra@pembeli-batubara.co.id',
            'subject' => 'Kontrak GAR 4200 Tongkang 300ft',
            'message' => 'Mohon penawaran spesifikasi dan jadwal muat untuk kuartal mendatang.',
            'website' => '',
        ])
        ->assertRedirect('/id/bisnis-kami/korin-berkat-energy/kontak')
        ->assertSessionHas('contactSuccess', true);

    $saved = ContactMessage::query()->first();
    expect($saved)->not->toBeNull()
        ->and($saved?->company_id)->toBe($korinBerkat->id)
        ->and($saved?->locale)->toBe('id');
});

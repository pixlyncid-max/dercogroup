<?php

declare(strict_types=1);

use App\Domain\Company\Models\Company;
use App\Domain\Contact\Models\ContactMessage;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Page\Models\Page;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
});

it('renders all 5 active PT Beramal Sukses Sejahtera pages in Indonesian and English with beramal theme', function (): void {
    $this->get('/id/bisnis-kami/beramal-sukses-sejahtera')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('locale', 'id')
            ->where('company.slug', 'beramal-sukses-sejahtera')
            ->where('company.theme', 'beramal')
            ->where('page.key', 'home')
            ->has('blocks', 4)
            ->has('stats', 4)
            ->has('products', 3)
            ->has('operations', 4)
        );

    $this->get('/en/our-business/beramal-sukses-sejahtera')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('locale', 'en')
            ->where('company.slug', 'beramal-sukses-sejahtera')
            ->where('company.theme', 'beramal')
            ->where('page.key', 'home')
        );

    $idSlugs = [
        'tentang-kami' => 'about',
        'produk' => 'products',
        'operasi' => 'operations',
        'hubungi-kami' => 'contact',
    ];

    foreach ($idSlugs as $slug => $expectedKey) {
        $this->get("/id/bisnis-kami/beramal-sukses-sejahtera/{$slug}")
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('ThemedPage')
                ->where('company.slug', 'beramal-sukses-sejahtera')
                ->where('company.theme', 'beramal')
                ->where('page.key', $expectedKey)
            );
    }

    $enSlugs = [
        'about-us' => 'about',
        'products' => 'products',
        'operations' => 'operations',
        'contact-us' => 'contact',
    ];

    foreach ($enSlugs as $slug => $expectedKey) {
        $this->get("/en/our-business/beramal-sukses-sejahtera/{$slug}")
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('ThemedPage')
                ->where('company.slug', 'beramal-sukses-sejahtera')
                ->where('company.theme', 'beramal')
                ->where('page.key', $expectedKey)
            );
    }

    // Compliance page is unpublished: returns 404 for public access
    $this->get('/id/bisnis-kami/beramal-sukses-sejahtera/mutu-dan-kepatuhan')->assertNotFound();
    $this->get('/en/our-business/beramal-sukses-sejahtera/quality-and-compliance')->assertNotFound();

    // With ?preview=1 for authenticated admin, compliance returns 200 with preview flag
    $admin = User::query()->firstOrFail();
    $this->actingAs($admin)
        ->get('/id/bisnis-kami/beramal-sukses-sejahtera/mutu-dan-kepatuhan?preview=1')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('page.key', 'compliance')
            ->where('isPreview', true)
        );

    // Legacy contact URLs redirect with 301 to hubungi-kami / contact-us
    $this->get('/id/bisnis-kami/beramal-sukses-sejahtera/kontak')
        ->assertStatus(301)
        ->assertRedirect('/id/bisnis-kami/beramal-sukses-sejahtera/hubungi-kami');

    $this->get('/en/our-business/beramal-sukses-sejahtera/contact')
        ->assertStatus(301)
        ->assertRedirect('/en/our-business/beramal-sukses-sejahtera/contact-us');
});

it('reflects CMS-updated Products page title in both navigation and page heading data', function (): void {
    $beramal = Company::query()->where('slug', 'beramal-sukses-sejahtera')->firstOrFail();

    $productsPage = Page::query()
        ->where('company_id', $beramal->id)
        ->where('key', PageKey::Products)
        ->firstOrFail();

    $productsPage->update([
        'title' => [
            'id' => 'Layanan & Solusi Operasional',
            'en' => 'Operational Services & Solutions',
        ],
    ]);

    $this->get('/id/bisnis-kami/beramal-sukses-sejahtera/produk')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('page.key', 'products')
            ->where('page.title', 'Layanan & Solusi Operasional')
            ->where('page.meta_title', 'Layanan & Solusi Operasional — PT Beramal Sukses Sejahtera')
            ->where('navigation.items.2.key', 'products')
            ->where('navigation.items.2.label', 'Layanan & Solusi Operasional')
        );

    $this->get('/en/our-business/beramal-sukses-sejahtera/products')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('page.key', 'products')
            ->where('page.title', 'Operational Services & Solutions')
            ->where('page.meta_title', 'Operational Services & Solutions — PT Beramal Sukses Sejahtera')
            ->where('navigation.items.2.key', 'products')
            ->where('navigation.items.2.label', 'Operational Services & Solutions')
        );
});

it('resolves neutral service/product detail page for PT Beramal Sukses Sejahtera in ID and EN', function (): void {
    $this->get('/id/bisnis-kami/beramal-sukses-sejahtera/produk/layanan-manajemen-fasilitas')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('locale', 'id')
            ->where('company.slug', 'beramal-sukses-sejahtera')
            ->where('company.theme', 'beramal')
            ->where('product.slug', 'layanan-manajemen-fasilitas')
            ->has('product.specs', 5)
            ->where('navigation.alternateUrls.id', '/id/bisnis-kami/beramal-sukses-sejahtera/produk/layanan-manajemen-fasilitas')
            ->where('navigation.alternateUrls.en', '/en/our-business/beramal-sukses-sejahtera/products/layanan-manajemen-fasilitas')
        );

    $this->get('/en/our-business/beramal-sukses-sejahtera/products/layanan-manajemen-fasilitas')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('locale', 'en')
            ->where('company.slug', 'beramal-sukses-sejahtera')
            ->where('company.theme', 'beramal')
            ->where('product.slug', 'layanan-manajemen-fasilitas')
            ->has('product.specs', 5)
        );
});

it('stores contact message linked to PT Beramal Sukses Sejahtera when submitted on subsidiary contact page', function (): void {
    Mail::fake();

    $beramal = Company::query()->where('slug', 'beramal-sukses-sejahtera')->firstOrFail();

    $this->from('/id/bisnis-kami/beramal-sukses-sejahtera/kontak')
        ->post('/id/bisnis-kami/beramal-sukses-sejahtera/contact-messages', [
            'name' => 'Rina Maharani',
            'email' => 'rina@mitra-operasional.co.id',
            'subject' => 'Kemitraan Layanan Manajemen Fasilitas',
            'message' => 'Mohon jadwal diskusi terkait dukungan pengelolaan fasilitas kawasan operasional.',
            'website' => '',
        ])
        ->assertRedirect('/id/bisnis-kami/beramal-sukses-sejahtera/kontak')
        ->assertSessionHas('contactSuccess', true);

    $saved = ContactMessage::query()->first();
    expect($saved)->not->toBeNull()
        ->and($saved?->company_id)->toBe($beramal->id)
        ->and($saved?->locale)->toBe('id');
});

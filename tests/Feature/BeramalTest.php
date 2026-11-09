<?php

declare(strict_types=1);

use App\Domain\Company\Models\Company;
use App\Domain\Contact\Models\ContactMessage;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Page\Models\Page;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
});

it('renders all 6 PT Beramal Sukses Sejahtera pages in Indonesian and English with beramal theme', function (): void {
    $this->get('/id/bisnis-kami/beramal-sukses-sejahtera')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('locale', 'id')
            ->where('company.slug', 'beramal-sukses-sejahtera')
            ->where('company.theme', 'beramal')
            ->where('page.key', 'home')
            ->has('blocks', 6)
            ->has('stats', 4)
            ->has('products', 3)
            ->has('operations', 4)
            ->has('certifications', 5)
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
        'mutu-dan-kepatuhan' => 'compliance',
        'kontak' => 'contact',
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
        'quality-and-compliance' => 'compliance',
        'contact' => 'contact',
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

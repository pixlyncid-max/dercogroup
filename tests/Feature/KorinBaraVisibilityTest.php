<?php

declare(strict_types=1);

use App\Domain\Company\Enums\CompanyStatus;
use App\Domain\Company\Models\Company;
use App\Domain\Contact\Models\ContactMessage;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
});

it('returns 404 for public visitors on all PT Korin Bara Energy draft URLs and excludes it from Holding and sitemap.xml', function (): void {
    $draftUrls = [
        '/id/bisnis-kami/korin-bara-energy',
        '/id/bisnis-kami/korin-bara-energy?preview=1',
        '/id/bisnis-kami/korin-bara-energy/tentang-kami',
        '/id/bisnis-kami/korin-bara-energy/produk',
        '/id/bisnis-kami/korin-bara-energy/produk/thermal-coal-gar-4200',
        '/id/bisnis-kami/korin-bara-energy/operasi',
        '/id/bisnis-kami/korin-bara-energy/mutu-dan-kepatuhan',
        '/id/bisnis-kami/korin-bara-energy/kontak',
        '/en/our-business/korin-bara-energy',
        '/en/our-business/korin-bara-energy?preview=1',
        '/en/our-business/korin-bara-energy/about-us',
        '/en/our-business/korin-bara-energy/products',
        '/en/our-business/korin-bara-energy/products/thermal-coal-gar-4200',
        '/en/our-business/korin-bara-energy/operations',
        '/en/our-business/korin-bara-energy/quality-and-compliance',
        '/en/our-business/korin-bara-energy/contact',
    ];

    foreach ($draftUrls as $url) {
        $this->get($url)->assertNotFound();
    }

    // Holding Home and Our Business only include the 2 published subsidiaries
    $this->get('/id')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->has('subsidiaries', 2)
            ->where('subsidiaries.0.slug', 'korin-berkat-energy')
            ->where('subsidiaries.1.slug', 'beramal-sukses-sejahtera')
        );

    $this->get('/id/bisnis-kami')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->has('subsidiaries', 2)
        );

    // Sitemap XML excludes draft company URLs
    $sitemapResponse = $this->get('/sitemap.xml');
    $sitemapResponse->assertOk();
    $sitemapXml = (string) $sitemapResponse->getContent();

    expect($sitemapXml)
        ->toContain('/id/bisnis-kami/korin-berkat-energy')
        ->toContain('/id/bisnis-kami/beramal-sukses-sejahtera')
        ->not->toContain('korin-bara-energy');
});

it('allows Super Admin and Editor to preview all 6 PT Korin Bara Energy pages and product detail with ?preview=1 and noindex headers', function (): void {
    $superAdmin = User::query()->where('email', 'admin@dercobara.co.id')->firstOrFail();
    $editor = User::query()->where('email', 'editor@dercobara.co.id')->firstOrFail();

    // Without ?preview=1, even logged-in admin gets 404 on draft public URL
    $this->actingAs($superAdmin)
        ->get('/id/bisnis-kami/korin-bara-energy')
        ->assertNotFound();

    // With ?preview=1, Super Admin gets 200 OK with korin-bara theme, isPreview=true, and X-Robots-Tag: noindex, nofollow
    $this->actingAs($superAdmin)
        ->get('/id/bisnis-kami/korin-bara-energy?preview=1')
        ->assertOk()
        ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('locale', 'id')
            ->where('isPreview', true)
            ->where('company.slug', 'korin-bara-energy')
            ->where('company.theme', 'korin-bara')
            ->where('company.status', 'draft')
            ->where('page.key', 'home')
            ->has('blocks', 6)
            ->has('stats', 4)
            ->has('products', 3)
            ->has('operations', 4)
            ->has('certifications', 5)
            ->where('navigation.items.0.href', '/id/bisnis-kami/korin-bara-energy?preview=1')
            ->where('navigation.alternateUrls.en', '/en/our-business/korin-bara-energy?preview=1')
        );

    $idSlugs = [
        'tentang-kami' => 'about',
        'produk' => 'products',
        'operasi' => 'operations',
        'mutu-dan-kepatuhan' => 'compliance',
        'kontak' => 'contact',
    ];

    foreach ($idSlugs as $slug => $expectedKey) {
        $this->actingAs($superAdmin)
            ->get("/id/bisnis-kami/korin-bara-energy/{$slug}?preview=1")
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('ThemedPage')
                ->where('isPreview', true)
                ->where('company.slug', 'korin-bara-energy')
                ->where('company.theme', 'korin-bara')
                ->where('page.key', $expectedKey)
            );
    }

    // Editor can also preview EN pages and product detail with ?preview=1
    $this->actingAs($editor)
        ->get('/en/our-business/korin-bara-energy/products/thermal-coal-gar-4200?preview=1')
        ->assertOk()
        ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('locale', 'en')
            ->where('isPreview', true)
            ->where('company.slug', 'korin-bara-energy')
            ->where('company.theme', 'korin-bara')
            ->where('product.slug', 'thermal-coal-gar-4200')
            ->has('product.specs', 5)
            ->where('navigation.alternateUrls.id', '/id/bisnis-kami/korin-bara-energy/produk/thermal-coal-gar-4200?preview=1')
            ->where('navigation.alternateUrls.en', '/en/our-business/korin-bara-energy/products/thermal-coal-gar-4200?preview=1')
        );
});

it('immediately publishes PT Korin Bara Energy to Holding grid, footer, public routes, and sitemap.xml when status changes to published', function (): void {
    $korinBara = Company::query()->where('slug', 'korin-bara-energy')->firstOrFail();
    $korinBara->update(['status' => CompanyStatus::Published]);

    // Holding Home and Our Business now list all 3 subsidiaries
    $this->get('/id')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->has('subsidiaries', 3)
            ->where('subsidiaries.0.slug', 'korin-berkat-energy')
            ->where('subsidiaries.1.slug', 'beramal-sukses-sejahtera')
            ->where('subsidiaries.2.slug', 'korin-bara-energy')
        );

    // Public visitors can now access Korin Bara Energy without ?preview=1
    $this->get('/id/bisnis-kami/korin-bara-energy')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('isPreview', false)
            ->where('company.slug', 'korin-bara-energy')
            ->where('company.theme', 'korin-bara')
            ->where('company.status', 'published')
            ->where('page.key', 'home')
        );

    $this->get('/en/our-business/korin-bara-energy/products/thermal-coal-gar-4200')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('isPreview', false)
            ->where('company.slug', 'korin-bara-energy')
            ->where('company.theme', 'korin-bara')
            ->where('product.slug', 'thermal-coal-gar-4200')
        );

    // Sitemap XML now includes Korin Bara Energy pages and products
    $sitemapXml = (string) $this->get('/sitemap.xml')->assertOk()->getContent();
    expect($sitemapXml)
        ->toContain('/id/bisnis-kami/korin-bara-energy')
        ->toContain('/en/our-business/korin-bara-energy')
        ->toContain('/id/bisnis-kami/korin-bara-energy/produk/thermal-coal-gar-4200');

    // Contact form submission works once published
    Mail::fake();
    $this->from('/id/bisnis-kami/korin-bara-energy/kontak')
        ->post('/id/bisnis-kami/korin-bara-energy/contact-messages', [
            'name' => 'Hendra Wijaya',
            'email' => 'hendra@energi-nusantara.co.id',
            'subject' => 'Kontrak Pasokan GAR 4.200',
            'message' => 'Kami tertarik mendiskusikan jadwal pengapalan tongkang kuartal depan.',
            'website' => '',
        ])
        ->assertRedirect('/id/bisnis-kami/korin-bara-energy/kontak')
        ->assertSessionHas('contactSuccess', true);

    $saved = ContactMessage::query()->first();
    expect($saved)->not->toBeNull()
        ->and($saved?->company_id)->toBe($korinBara->id);
});

<?php

declare(strict_types=1);

use App\Domain\Company\Actions\CreateCompanyPages;
use App\Domain\Company\Actions\PublishKorinBaraAction;
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

it('renders all 6 published PT Korin Bara Energy pages and product detail publicly without preview flag', function (): void {
    $publicUrls = [
        '/id/bisnis-kami/korin-bara-energy',
        '/id/bisnis-kami/korin-bara-energy/tentang-kami',
        '/id/bisnis-kami/korin-bara-energy/produk',
        '/id/bisnis-kami/korin-bara-energy/produk/thermal-coal-gar-4200',
        '/id/bisnis-kami/korin-bara-energy/operasi',
        '/id/bisnis-kami/korin-bara-energy/mutu-dan-kepatuhan',
        '/id/bisnis-kami/korin-bara-energy/kontak',
        '/en/our-business/korin-bara-energy',
        '/en/our-business/korin-bara-energy/about-us',
        '/en/our-business/korin-bara-energy/products',
        '/en/our-business/korin-bara-energy/products/thermal-coal-gar-4200',
        '/en/our-business/korin-bara-energy/operations',
        '/en/our-business/korin-bara-energy/quality-and-compliance',
        '/en/our-business/korin-bara-energy/contact',
    ];

    foreach ($publicUrls as $url) {
        $response = $this->get($url);
        $response->assertOk();
        // Public pages do NOT have noindex headers
        expect($response->headers->get('X-Robots-Tag'))->not()->toBe('noindex, nofollow');
    }

    // Holding Home and Our Business include all 3 published subsidiaries
    $this->get('/id')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->has('subsidiaries', 3)
            ->where('subsidiaries.0.slug', 'korin-berkat-energy')
            ->where('subsidiaries.1.slug', 'beramal-sukses-sejahtera')
            ->where('subsidiaries.2.slug', 'korin-bara-energy')
        );

    $this->get('/id/bisnis-kami')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->has('subsidiaries', 3)
        );

    // Sitemap XML includes Korin Bara URLs
    $sitemapResponse = $this->get('/sitemap.xml');
    $sitemapResponse->assertOk();
    $sitemapXml = (string) $sitemapResponse->getContent();

    expect($sitemapXml)
        ->toContain('/id/bisnis-kami/korin-bara-energy')
        ->toContain('/en/our-business/korin-bara-energy')
        ->toContain('/id/bisnis-kami/korin-bara-energy/produk/thermal-coal-gar-4200');

    // Contact form submission works for Korin Bara
    Mail::fake();
    $korinBara = Company::query()->where('slug', 'korin-bara-energy')->firstOrFail();
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

it('hides draft companies from public visitors and sitemap while allowing admin preview (FR-VIS)', function (): void {
    $holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();
    $draftCompany = Company::factory()->draft()->subsidiary($holding)->create([
        'slug' => 'draft-exploration',
        'name' => 'PT Draft Exploration',
    ]);
    app(CreateCompanyPages::class)->execute($draftCompany);

    $superAdmin = User::query()->where('email', 'admin@dercobara.co.id')->firstOrFail();
    $editor = User::query()->where('email', 'editor@dercobara.co.id')->firstOrFail();

    // 1. Public visitor gets 404
    $this->get('/id/bisnis-kami/draft-exploration')->assertNotFound();

    // 2. Draft company excluded from sitemap.xml
    $sitemapXml = (string) $this->get('/sitemap.xml')->assertOk()->getContent();
    expect($sitemapXml)->not->toContain('draft-exploration');

    // 3. Logged-in admin without ?preview=1 still gets 404
    $this->actingAs($superAdmin)
        ->get('/id/bisnis-kami/draft-exploration')
        ->assertNotFound();

    // 4. Logged-in admin with ?preview=1 gets 200 and noindex header
    $this->actingAs($superAdmin)
        ->get('/id/bisnis-kami/draft-exploration?preview=1')
        ->assertOk()
        ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('locale', 'id')
            ->where('isPreview', true)
            ->where('company.slug', 'draft-exploration')
            ->where('company.status', 'draft')
        );

    // 5. Editor can also preview with ?preview=1
    $this->actingAs($editor)
        ->get('/en/our-business/draft-exploration?preview=1')
        ->assertOk()
        ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
});

it('executes PublishKorinBaraAction idempotently', function (): void {
    $action = app(PublishKorinBaraAction::class);

    // Run first time
    $action->execute();
    $korinBara = Company::query()->where('slug', 'korin-bara-energy')->firstOrFail();
    expect($korinBara->status)->toBe(CompanyStatus::Published)
        ->and($korinBara->pages()->where('is_published', false)->count())->toBe(0);

    // Run second time (idempotent)
    $action->execute();
    $korinBara->refresh();
    expect($korinBara->status)->toBe(CompanyStatus::Published)
        ->and($korinBara->pages()->where('is_published', false)->count())->toBe(0);
});

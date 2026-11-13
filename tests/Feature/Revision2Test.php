<?php

declare(strict_types=1);

use App\Domain\Company\Actions\SyncSubsidiaryPagesAction;
use App\Domain\Company\Models\Company;
use App\Domain\Page\Enums\BlockType;
use App\Domain\Page\Enums\PageKey;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
});

it('verifies per-theme page configuration and page counts', function (): void {
    $korinBerkat = Company::query()->where('slug', 'korin-berkat-energy')->firstOrFail();
    $beramal = Company::query()->where('slug', 'beramal-sukses-sejahtera')->firstOrFail();
    $holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();

    // Holding has 4 pages
    expect($holding->pages()->count())->toBe(4);

    // Korin Berkat has 5 pages (Compliance completely excluded)
    expect($korinBerkat->pages()->count())->toBe(5);
    expect($korinBerkat->pages()->where('key', PageKey::Compliance->value)->exists())->toBeFalse();

    // Beramal has 5 pages (Compliance completely deleted per Revisi 6 Section 0 Item 9)
    expect($beramal->pages()->count())->toBe(5);
    expect($beramal->pages()->where('key', PageKey::Compliance->value)->exists())->toBeFalse();
});

it('executes SyncSubsidiaryPagesAction idempotently', function (): void {
    $action = app(SyncSubsidiaryPagesAction::class);

    // Run once
    $action->execute();

    $korinBerkat = Company::query()->where('slug', 'korin-berkat-energy')->firstOrFail();
    $beramal = Company::query()->where('slug', 'beramal-sukses-sejahtera')->firstOrFail();

    expect($korinBerkat->pages()->count())->toBe(5);
    expect($beramal->pages()->count())->toBe(5);

    // Run a second time to ensure idempotency
    $action->execute();

    expect($korinBerkat->pages()->count())->toBe(5);
    expect($beramal->pages()->count())->toBe(5);
});

it('returns 404 for Beramal compliance because the page is permanently deleted per Revisi 6 Section 0 Item 9', function (): void {
    // Both public and preview requests return 404
    $this->get('/id/bisnis-kami/beramal-sukses-sejahtera/mutu-dan-kepatuhan')->assertNotFound();
    $this->get('/en/our-business/beramal-sukses-sejahtera/quality-and-compliance')->assertNotFound();
    $this->get('/id/bisnis-kami/beramal-sukses-sejahtera/mutu-dan-kepatuhan?preview=1')->assertNotFound();

    $admin = User::query()->firstOrFail();
    $this->actingAs($admin)
        ->get('/id/bisnis-kami/beramal-sukses-sejahtera/mutu-dan-kepatuhan?preview=1')
        ->assertNotFound();
});

it('returns 404 for Korin Berkat compliance URLs because the page is permanently deleted', function (): void {
    $this->get('/id/bisnis-kami/korin-berkat-energy/mutu-dan-kepatuhan')->assertNotFound();
    $this->get('/en/our-business/korin-berkat-energy/quality-and-compliance')->assertNotFound();
    $this->get('/id/bisnis-kami/korin-berkat-energy/mutu-dan-kepatuhan?preview=1')->assertNotFound();
});

it('redirects Beramal legacy contact slugs with 301 permanent redirect', function (): void {
    $this->get('/id/bisnis-kami/beramal-sukses-sejahtera/kontak')
        ->assertStatus(301)
        ->assertRedirect('/id/bisnis-kami/beramal-sukses-sejahtera/hubungi-kami');

    $this->get('/en/our-business/beramal-sukses-sejahtera/contact')
        ->assertStatus(301)
        ->assertRedirect('/en/our-business/beramal-sukses-sejahtera/contact-us');
});

it('verifies sitemap excludes draft, unpublished, and deleted pages', function (): void {
    $response = $this->get('/sitemap.xml');
    $response->assertOk();

    $content = $response->getContent();
    expect($content)->toContain('korin-bara-energy');
    expect($content)->not()->toContain('beramal-sukses-sejahtera/mutu-dan-kepatuhan');
    expect($content)->not()->toContain('beramal-sukses-sejahtera/quality-and-compliance');
    expect($content)->not()->toContain('korin-berkat-energy/mutu-dan-kepatuhan');
    expect($content)->not()->toContain('korin-berkat-energy/quality-and-compliance');
    expect($content)->toContain('beramal-sukses-sejahtera/hubungi-kami');
    expect($content)->toContain('korin-berkat-energy/kontak');
});

it('verifies block structure adjustments on Korin Berkat and Beramal', function (): void {
    $korinBerkat = Company::query()->where('slug', 'korin-berkat-energy')->firstOrFail();
    $beramal = Company::query()->where('slug', 'beramal-sukses-sejahtera')->firstOrFail();

    // 1. Korin Berkat Home: has no CertificationList
    $kbHome = $korinBerkat->pages()->where('key', PageKey::Home->value)->firstOrFail();
    expect($kbHome->blocks()->where('type', BlockType::CertificationList)->exists())->toBeFalse();

    // 2. Korin Berkat About: has no Timeline, has ValuesGrid with 4 items titled "Nilai Perusahaan"
    $kbAbout = $korinBerkat->pages()->where('key', PageKey::About->value)->firstOrFail();
    expect($kbAbout->blocks()->where('type', BlockType::Timeline)->exists())->toBeFalse();

    $kbValues = $kbAbout->blocks()->where('type', BlockType::ValuesGrid)->firstOrFail();
    $items = $kbValues->data['items'] ?? [];
    expect(count($items))->toBe(4);
    expect($items[0]['title']['id'])->toBe('Integritas Pasokan [PLACEHOLDER]');
    expect($items[1]['title']['id'])->toBe('Ketepatan Spesifikasi [PLACEHOLDER]');
    expect($items[2]['title']['id'])->toBe('Keandalan Pengapalan [PLACEHOLDER]');
    expect($items[3]['title']['id'])->toBe('Kepatuhan Berkelanjutan [PLACEHOLDER]');

    // 3. Beramal Home: has no CertificationList and no OperationList
    $bHome = $beramal->pages()->where('key', PageKey::Home->value)->firstOrFail();
    expect($bHome->blocks()->where('type', BlockType::CertificationList)->exists())->toBeFalse();
    expect($bHome->blocks()->where('type', BlockType::OperationList)->exists())->toBeFalse();

    // 4. Beramal About: has no Timeline
    $bAbout = $beramal->pages()->where('key', PageKey::About->value)->firstOrFail();
    expect($bAbout->blocks()->where('type', BlockType::Timeline)->exists())->toBeFalse();
});

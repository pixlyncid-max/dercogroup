<?php

declare(strict_types=1);

use App\Domain\Company\Models\Company;
use App\Domain\Page\Enums\BlockType;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Page\Models\Page;
use App\Domain\Page\Models\PageBlock;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
});

test('3.7 Sentinel Test: CMS content changes appear immediately in public page HTML (ID & EN)', function (): void {
    $holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();
    $homePage = Page::query()
        ->where('company_id', $holding->id)
        ->where('key', PageKey::Home->value)
        ->firstOrFail();

    $heroBlock = $homePage->blocks()->where('type', BlockType::Hero)->firstOrFail();

    $sentinelTitleId = 'SENTINEL_HERO_TITLE_ID_998811';
    $sentinelDescId = 'SENTINEL_HERO_DESC_ID_554422';
    $sentinelTitleEn = 'SENTINEL_HERO_TITLE_EN_112233';
    $sentinelDescEn = 'SENTINEL_HERO_DESC_EN_332211';

    $blockData = $heroBlock->data;
    $blockData['title'] = ['id' => $sentinelTitleId, 'en' => $sentinelTitleEn];
    $blockData['description'] = ['id' => $sentinelDescId, 'en' => $sentinelDescEn];

    $heroBlock->update(['data' => $blockData]);

    // Test Indonesian public page
    $responseId = $this->get('/id');
    $responseId->assertOk();
    $contentId = $responseId->getContent();
    expect($contentId)->toContain($sentinelTitleId)
        ->and($contentId)->toContain($sentinelDescId)
        ->and($contentId)->not->toContain($sentinelTitleEn);

    // Test English public page
    $responseEn = $this->get('/en');
    $responseEn->assertOk();
    $contentEn = $responseEn->getContent();
    expect($contentEn)->toContain($sentinelTitleEn)
        ->and($contentEn)->toContain($sentinelDescEn)
        ->and($contentEn)->not->toContain($sentinelTitleId);
});

test('3.7 Sentinel Test: Visibility toggle (is_visible) completely hides section from public website', function (): void {
    $holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();
    $homePage = Page::query()
        ->where('company_id', $holding->id)
        ->where('key', PageKey::Home->value)
        ->firstOrFail();

    $ctaBlock = $homePage->blocks()->where('type', BlockType::CtaBanner)->firstOrFail();

    $sentinelCtaText = 'UNIQUE_SENTINEL_CTA_BANNER_SECRET_777';
    $blockData = $ctaBlock->data;
    $blockData['title'] = ['id' => $sentinelCtaText, 'en' => $sentinelCtaText];
    $ctaBlock->update(['data' => $blockData, 'is_visible' => true]);

    // When is_visible = true, sentinel appears
    $responseVisible = $this->get('/id');
    $responseVisible->assertOk();
    expect($responseVisible->getContent())->toContain($sentinelCtaText);

    // When is_visible = false, sentinel is completely absent
    $ctaBlock->update(['is_visible' => false]);
    $responseHidden = $this->get('/id');
    $responseHidden->assertOk();
    expect($responseHidden->getContent())->not->toContain($sentinelCtaText);
});

test('3.7 Sentinel Test: Image slot renders uploaded image path in public HTML', function (): void {
    $holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();
    $homePage = Page::query()
        ->where('company_id', $holding->id)
        ->where('key', PageKey::Home->value)
        ->firstOrFail();

    $heroBlock = $homePage->blocks()->where('type', BlockType::Hero)->firstOrFail();

    $sentinelImagePath = 'blocks/custom-sentinel-mining-truck-99.webp';
    $sentinelAlt = 'Custom Sentinel Alt Text Description';

    $blockData = $heroBlock->data;
    $blockData['image'] = $sentinelImagePath;
    $blockData['image_alt'] = ['id' => $sentinelAlt, 'en' => $sentinelAlt];
    $heroBlock->update(['data' => $blockData]);

    $response = $this->get('/id');
    $response->assertOk();
    $content = $response->getContent();

    expect($content)->toContain($sentinelImagePath);
});

test('3.5 Sentinel Test: Every rendered section has stable id and data-section attributes', function (): void {
    $urls = [
        '/id',
        '/id/bisnis-kami/korin-berkat-energy',
        '/id/bisnis-kami/korin-bara-energy',
        '/id/bisnis-kami/beramal-sukses-sejahtera',
    ];

    foreach ($urls as $url) {
        $response = $this->get($url);
        $response->assertOk();
        $content = $response->getContent();

        // Header and Footer sections
        expect($content)->toContain('id="section-header"')
            ->and($content)->toContain('data-section="header"')
            ->and($content)->toContain('id="section-footer"')
            ->and($content)->toContain('data-section="footer"');

        // Page content sections
        expect($content)->toContain('id="section-')
            ->and($content)->toContain('data-section="');
    }
});

test('3.7 Sentinel Test: Subsidiary page content is fully dynamic from CMS', function (): void {
    $subsidiaries = [
        'korin-berkat-energy' => '/id/bisnis-kami/korin-berkat-energy',
        'korin-bara-energy' => '/id/bisnis-kami/korin-bara-energy',
        'beramal-sukses-sejahtera' => '/id/bisnis-kami/beramal-sukses-sejahtera',
    ];

    foreach ($subsidiaries as $slug => $url) {
        $company = Company::query()->where('slug', $slug)->firstOrFail();
        $homePage = Page::query()
            ->where('company_id', $company->id)
            ->where('key', PageKey::Home->value)
            ->firstOrFail();

        $heroBlock = $homePage->blocks()->where('type', BlockType::Hero)->firstOrFail();
        $sentinelText = "DYNAMIC_CMS_SUB_{$slug}_TITLE_VERIFIED";

        $blockData = $heroBlock->data;
        $blockData['title'] = ['id' => $sentinelText, 'en' => $sentinelText];
        $heroBlock->update(['data' => $blockData]);

        $response = $this->get($url);
        $response->assertOk();
        expect($response->getContent())->toContain($sentinelText);
    }
});


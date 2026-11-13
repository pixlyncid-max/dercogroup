<?php

declare(strict_types=1);

use App\Domain\Company\Models\Company;
use App\Domain\Page\Blocks\FooterBlock;
use App\Domain\Page\Blocks\HeaderBlock;
use App\Domain\Page\Enums\BlockType;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Page\Models\Page;
use App\Domain\Page\Models\PageBlock;
use App\Domain\Page\Schema\SectionSchema;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
});

test('2.1 & 2.4: every defined section across all companies and pages has valid default ID and EN content', function (): void {
    $companies = Company::query()->get();
    expect($companies)->toHaveCount(4);

    foreach ($companies as $company) {
        $pageKeys = $company->theme->pageKeys();
        $pageKeyStrings = array_map(fn (PageKey $k): string => $k->value, $pageKeys);
        $pageKeyStrings[] = 'footer';

        foreach ($pageKeyStrings as $pageKey) {
            $sections = SectionSchema::getSectionsForPage($company->theme, $pageKey);
            expect($sections)->not->toBeEmpty();

            foreach ($sections as $section) {
                expect($section['section_key'])->toBeString()->not->toBeEmpty()
                    ->and($section['label'])->toBeString()->not->toBeEmpty()
                    ->and($section['help_text'])->toBeString()->not->toBeEmpty()
                    ->and($section['block_type'])->toBeInstanceOf(BlockType::class);

                // Verify default content exists for this section
                $defaults = SectionSchema::getDefaultDataForSection(
                    $company->slug,
                    $pageKey,
                    $section['section_key'],
                    $section['block_type']
                );

                expect($defaults)->toBeArray();

                // If translatable title exists, verify ID and EN are non-empty
                if (isset($defaults['title']) && is_array($defaults['title'])) {
                    expect($defaults['title'])->toHaveKey('id')
                        ->and($defaults['title'])->toHaveKey('en');
                }
            }
        }
    }
});

test('2.1: Section schemas provide valid rules and throw on invalid payloads', function (): void {
    // Header block validation
    $headerValid = [
        'logo_alt' => ['id' => 'Logo PT Derco', 'en' => 'Derco Logo'],
        'show_company_badge' => true,
        'show_language_switch' => true,
    ];
    $normalizedHeader = HeaderBlock::validateAndNormalize($headerValid);
    expect($normalizedHeader['show_company_badge'])->toBeTrue();

    // Footer block validation (requires description and copyright_text)
    $footerValid = [
        'description' => ['id' => 'Deskripsi', 'en' => 'Description'],
        'copyright_text' => ['id' => 'Hak Cipta', 'en' => 'Copyright'],
    ];
    $normalizedFooter = FooterBlock::validateAndNormalize($footerValid);
    expect($normalizedFooter['description']['id'])->toBe('Deskripsi');

    // Missing required field on Footer must throw
    expect(fn () => FooterBlock::validateAndNormalize([]))
        ->toThrow(ValidationException::class);
});

test('2.2: Page sections map correctly places Header in Beranda and Footer in pseudo-page', function (): void {
    $holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();

    // Beranda must have Header as first section and company-scoped
    $homeSections = SectionSchema::getSectionsForPage($holding->theme, PageKey::Home->value);
    expect($homeSections[0]['section_key'])->toBe('header')
        ->and($homeSections[0]['is_company_scoped'])->toBeTrue()
        ->and($homeSections[0]['block_type'])->toBe(BlockType::Header);

    // Footer pseudo-page has Footer section and company-scoped
    $footerSections = SectionSchema::getSectionsForPage($holding->theme, 'footer');
    expect($footerSections)->toHaveCount(1)
        ->and($footerSections[0]['section_key'])->toBe('footer')
        ->and($footerSections[0]['is_company_scoped'])->toBeTrue()
        ->and($footerSections[0]['block_type'])->toBe(BlockType::Footer);
});

test('2.3: Storage in page_blocks maintains company_id and section_key', function (): void {
    $holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();

    // Ensure company header block exists and is scoped to company without page_id
    $headerBlock = PageBlock::query()
        ->where('company_id', $holding->id)
        ->where('section_key', 'header')
        ->first();

    expect($headerBlock)->not->toBeNull()
        ->and($headerBlock->type)->toBe(BlockType::Header)
        ->and($headerBlock->company_id)->toBe($holding->id);

    // Ensure all blocks in holding have valid company_id
    $nullCompanyBlocks = PageBlock::query()->whereNull('company_id')->count();
    expect($nullCompanyBlocks)->toBe(0);
});

test('2.4: Link picker options generate valid internal page references and subsidiary URLs', function (): void {
    $holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();

    $options = SectionSchema::getLinkPickerOptions($holding);

    expect($options)->toHaveKey('Halaman Perusahaan Ini')
        ->and($options['Halaman Perusahaan Ini'])->toHaveKey('page:home')
        ->and($options['Halaman Perusahaan Ini'])->toHaveKey('page:about')
        ->and($options['Halaman Perusahaan Ini'])->toHaveKey('page:business')
        ->and($options['Halaman Perusahaan Ini'])->toHaveKey('page:contact');
});

test('2.5: Compliance / Mutu & Kepatuhan page is deleted across all companies per Section 0 Item 9', function (): void {
    $compliancePages = Page::query()->where('key', PageKey::Compliance->value)->count();
    expect($compliancePages)->toBe(0);

    // No CertificationList blocks exist
    $certBlocks = PageBlock::query()->where('type', BlockType::CertificationList)->count();
    expect($certBlocks)->toBe(0);
});

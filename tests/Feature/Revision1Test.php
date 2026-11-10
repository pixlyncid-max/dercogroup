<?php

declare(strict_types=1);

use App\Domain\Company\Models\Company;
use App\Domain\Page\Blocks\HeroBlock;
use App\Domain\Page\Enums\BlockType;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Page\Models\Page;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
});

it('redirects legacy holding contact slugs with 301 permanent redirect', function (): void {
    $this->get('/id/kontak')
        ->assertStatus(301)
        ->assertRedirect('/id/hubungi-kami');

    $this->get('/en/contact')
        ->assertStatus(301)
        ->assertRedirect('/en/contact-us');
});

it('verifies that holding Tentang Kami has no Timeline block', function (): void {
    $holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();
    $aboutPage = Page::query()
        ->where('company_id', $holding->id)
        ->where('key', PageKey::About->value)
        ->firstOrFail();

    $timelineBlock = $aboutPage->blocks()->where('type', BlockType::Timeline)->first();
    expect($timelineBlock)->toBeNull();

    // Check correct ordered blocks
    $blockTypes = $aboutPage->blocks()->orderBy('sort_order')->pluck('type')->all();
    expect($blockTypes)->toBe([
        BlockType::Hero,
        BlockType::StackedImages,
        BlockType::MissionList,
        BlockType::ValuesGrid,
        BlockType::SubsidiaryGrid,
        BlockType::CtaBanner,
    ]);
});

it('validates hero block slides schema constraints', function (): void {
    // Valid: 3 slides with ID and EN alt text
    $validData = [
        'title' => ['id' => 'Judul Hero', 'en' => 'Hero Title'],
        'description' => ['id' => 'Deskripsi Hero', 'en' => 'Hero Description'],
        'slides' => [
            [
                'image' => '/images/slide1.webp',
                'image_alt' => ['id' => 'Alt 1 ID', 'en' => 'Alt 1 EN'],
            ],
            [
                'image' => '/images/slide2.webp',
                'image_alt' => ['id' => 'Alt 2 ID', 'en' => 'Alt 2 EN'],
            ],
            [
                'image' => '/images/slide3.webp',
                'image_alt' => ['id' => 'Alt 3 ID', 'en' => 'Alt 3 EN'],
            ],
        ],
    ];

    $normalized = HeroBlock::validateAndNormalize($validData);
    expect($normalized['slides'])->toHaveCount(3);

    // Invalid: less than 3 slides
    $tooFewData = [
        'title' => ['id' => 'Judul Hero', 'en' => 'Hero Title'],
        'description' => ['id' => 'Deskripsi Hero', 'en' => 'Hero Description'],
        'slides' => [
            [
                'image' => '/images/slide1.webp',
                'image_alt' => ['id' => 'Alt 1 ID', 'en' => 'Alt 1 EN'],
            ],
            [
                'image' => '/images/slide2.webp',
                'image_alt' => ['id' => 'Alt 2 ID', 'en' => 'Alt 2 EN'],
            ],
        ],
    ];
    expect(fn () => HeroBlock::validateAndNormalize($tooFewData))
        ->toThrow(ValidationException::class);

    // Invalid: missing English alt text
    $missingAltEn = [
        'title' => ['id' => 'Judul Hero', 'en' => 'Hero Title'],
        'description' => ['id' => 'Deskripsi Hero', 'en' => 'Hero Description'],
        'slides' => [
            [
                'image' => '/images/slide1.webp',
                'image_alt' => ['id' => 'Alt 1 ID'],
            ],
            [
                'image' => '/images/slide2.webp',
                'image_alt' => ['id' => 'Alt 2 ID', 'en' => 'Alt 2 EN'],
            ],
            [
                'image' => '/images/slide3.webp',
                'image_alt' => ['id' => 'Alt 3 ID', 'en' => 'Alt 3 EN'],
            ],
        ],
    ];
    expect(fn () => HeroBlock::validateAndNormalize($missingAltEn))
        ->toThrow(ValidationException::class);
});

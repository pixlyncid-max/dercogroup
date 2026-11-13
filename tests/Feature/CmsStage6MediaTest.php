<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domain\Company\Actions\ResolveCompanyLogo;
use App\Domain\Company\Models\Company;
use App\Domain\Media\Actions\ProcessUploadedMediaAction;
use App\Domain\Page\Enums\BlockType;
use App\Domain\Page\Models\PageBlock;
use App\Filament\Pages\EditWebsitePage;
use App\Models\User;
use App\Support\ImageSource;
use Database\Seeders\DatabaseSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Tests\TestCase;

class CmsStage6MediaTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;

    protected Company $holding;

    protected Company $subsidiary;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->superAdmin = User::query()->where('email', 'admin@dercobara.co.id')->firstOrFail();
        $this->holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();
        $this->subsidiary = Company::query()->where('slug', 'korin-berkat-energy')->firstOrFail();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_6_1_resolve_company_logo_falls_back_to_branding_config_when_no_custom_logo(): void
    {
        $action = app(ResolveCompanyLogo::class);

        $logoData = $action->execute($this->holding);

        $this->assertNotNull($logoData);
        $this->assertEquals('/images/logo/Logo-DercoBaraEnergy.png', $logoData['light']);
        $this->assertEquals('PT Derco Bara Energy', $logoData['alt']);
    }

    public function test_6_2_resolve_company_logo_returns_custom_logo_from_header_block_and_syncs_with_tenant(): void
    {
        Storage::fake('public');
        $customLogoPath = 'blocks/custom-corporate-derco-logo.webp';
        Storage::disk('public')->put($customLogoPath, 'fake-webp-bytes');

        // Create or update Header block for holding
        $headerBlock = PageBlock::query()->updateOrCreate(
            [
                'company_id' => $this->holding->id,
                'section_key' => 'header',
            ],
            [
                'type' => BlockType::Header,
                'is_visible' => true,
                'data' => [
                    'logo_path' => $customLogoPath,
                    'logo_path_alt' => [
                        'id' => 'Logo Kustom Resmi Derco Holding',
                        'en' => 'Official Custom Derco Holding Logo',
                    ],
                    'show_company_badge' => true,
                    'show_language_switch' => true,
                    'nav_items' => [],
                ],
            ]
        );

        $action = app(ResolveCompanyLogo::class);
        $resolved = $action->execute($this->holding);

        $this->assertNotNull($resolved);
        $this->assertStringContainsString('custom-corporate-derco-logo.webp', $resolved['light']);
        $this->assertEquals('Logo Kustom Resmi Derco Holding', $resolved['alt']);

        // Filament tenant switcher avatar automatically reflects the single source of truth
        $avatarUrl = $this->holding->getFilamentAvatarUrl();
        $this->assertNotNull($avatarUrl);
        $this->assertStringContainsString('custom-corporate-derco-logo.webp', $avatarUrl);
    }

    public function test_6_3_process_uploaded_media_converts_jpg_png_to_webp_and_preserves_svg(): void
    {
        Storage::fake('public');
        $action = app(ProcessUploadedMediaAction::class);

        // 1. Test PNG image upload (should convert to WebP)
        $pngFile = UploadedFile::fake()->image('mining-site.png', 800, 600);
        $resultPng = $action->execute($pngFile);

        $this->assertEquals('image', $resultPng['type']);
        $this->assertEquals('webp', $resultPng['format']);
        $this->assertStringEndsWith('.webp', $resultPng['path']);
        Storage::disk('public')->assertExists($resultPng['path']);

        // 2. Test SVG image upload (should preserve SVG)
        $svgFile = UploadedFile::fake()->create('custom-badge.svg', 10, 'image/svg+xml');
        $resultSvg = $action->execute($svgFile);

        $this->assertEquals('image', $resultSvg['type']);
        $this->assertEquals('svg', $resultSvg['format']);
        $this->assertStringEndsWith('.svg', $resultSvg['path']);
        Storage::disk('public')->assertExists($resultSvg['path']);

        // 3. Test PDF document upload (should store as document)
        $pdfFile = UploadedFile::fake()->create('coal-spec-report.pdf', 500, 'application/pdf');
        $resultPdf = $action->execute($pdfFile);

        $this->assertEquals('document', $resultPdf['type']);
        $this->assertEquals('pdf', $resultPdf['format']);
        $this->assertStringEndsWith('.pdf', $resultPdf['path']);
        Storage::disk('public')->assertExists($resultPdf['path']);
    }

    public function test_6_4_save_rejects_images_without_both_id_and_en_alt_text(): void
    {
        Storage::fake('public');
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->holding);

        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);

        // Set home_strategic_focus primary_image without EN alt text
        $component->set('formData.home_strategic_focus.primary_image', 'blocks/test-mining.webp');
        $component->set('formData.home_strategic_focus.primary_image_alt.id', 'Deskripsi foto Indonesia');
        $component->set('formData.home_strategic_focus.primary_image_alt.en', ''); // Missing EN

        $component->call('save');
        $component->assertHasErrors(['formData.home_strategic_focus.primary_image_alt']);
    }

    public function test_6_5_save_succeeds_when_image_has_both_id_and_en_alt_text(): void
    {
        Storage::fake('public');
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->holding);

        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);

        // Fill primary_image with both ID and EN alt text
        $component->set('formData.home_strategic_focus.primary_image', 'blocks/test-mining-valid.webp');
        $component->set('formData.home_strategic_focus.primary_image_alt.id', 'Operasional tambang batubara');
        $component->set('formData.home_strategic_focus.primary_image_alt.en', 'Coal mining operations');

        $component->call('save');
        $component->assertHasNoErrors();

        // Verify database persistence
        $block = PageBlock::query()
            ->where('company_id', $this->holding->id)
            ->where('section_key', 'home_strategic_focus')
            ->first();

        $this->assertNotNull($block);
        $this->assertEquals('blocks/test-mining-valid.webp', $block->data['primary_image']);
        $this->assertEquals('Operasional tambang batubara', $block->data['primary_image_alt']['id']);
        $this->assertEquals('Coal mining operations', $block->data['primary_image_alt']['en']);
    }

    public function test_6_6_process_uploaded_media_rejects_oversized_files(): void
    {
        Storage::fake('public');
        $action = app(ProcessUploadedMediaAction::class);

        // Image > 5MB should fail
        $oversizedImage = UploadedFile::fake()->create('huge-banner.png', 6000, 'image/png'); // 6MB
        $this->expectException(ValidationException::class);
        $action->execute($oversizedImage);
    }

    public function test_6_7_image_source_resolves_fallback_placeholder_when_empty(): void
    {
        // When path is null or empty, resolves to local SVG placeholder
        $resolvedEmpty = ImageSource::resolve('');
        $this->assertEquals('/images/placeholders/placeholder-image.svg', $resolvedEmpty['url']);

        $resolvedNull = ImageSource::resolve(null);
        $this->assertEquals('/images/placeholders/placeholder-image.svg', $resolvedNull['url']);

        // When valid path is passed, returns correct storage URL
        $resolvedValid = ImageSource::resolve('blocks/hero-coal.webp');
        $this->assertStringContainsString('blocks/hero-coal.webp', $resolvedValid['url'] ?? '');
    }
}

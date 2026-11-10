<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domain\Company\Actions\ResolveCompanyLogo;
use App\Domain\Company\Enums\CompanyTheme;
use App\Domain\Company\Models\Company;
use App\Domain\Company\Resources\CompanyResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class BrandingLogoTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_company_themes_have_valid_branding_configuration(): void
    {
        $configLogos = config('branding.logos');
        $this->assertIsArray($configLogos, 'config/branding.php logos must be an array');

        foreach (CompanyTheme::cases() as $theme) {
            $this->assertArrayHasKey(
                $theme->value,
                $configLogos,
                "Theme [{$theme->value}] MUST have a configuration entry in config/branding.php"
            );

            $entry = $configLogos[$theme->value];
            $this->assertNotEmpty($entry['light'], "Theme [{$theme->value}] must have 'light' file");
            $this->assertNotEmpty($entry['dark'], "Theme [{$theme->value}] must have 'dark' file");
            $this->assertGreaterThan(0, $entry['width'], "Theme [{$theme->value}] must have positive width");
            $this->assertGreaterThan(0, $entry['height'], "Theme [{$theme->value}] must have positive height");
            $this->assertNotEmpty($entry['alt'], "Theme [{$theme->value}] must have non-empty alt text");

            // Verify naming semantics: light = for light surfaces (original), dark = for dark surfaces (-light variant)
            $this->assertStringNotContainsString(
                '-light.',
                $entry['light'],
                "Key 'light' is for light surfaces (original dark-ink logo), should not contain '-light.'"
            );
            $this->assertStringContainsString(
                '-light.',
                $entry['dark'],
                "Key 'dark' is for dark surfaces (light/white-ink logo), must be the '-light.' variant"
            );
        }
    }

    public function test_resolve_company_logo_returns_logo_object_when_files_exist(): void
    {
        $action = app(ResolveCompanyLogo::class);

        $dercoLogo = $action->execute(CompanyTheme::Holding);
        $this->assertNotNull($dercoLogo);
        $this->assertEquals('/images/logo/Logo-DercoBaraEnergy.png', $dercoLogo['light']);
        $this->assertEquals('/images/logo/Logo-DercoBaraEnergy-light.png', $dercoLogo['dark']);
        $this->assertEquals(676, $dercoLogo['width']);
        $this->assertEquals(369, $dercoLogo['height']);
        $this->assertEquals('PT Derco Bara Energy', $dercoLogo['alt']);

        $korinBaraLogo = $action->execute(CompanyTheme::KorinBara);
        $this->assertNotNull($korinBaraLogo);
        $this->assertEquals('/images/logo/Logo-KorinBaraEnergy-compact.png', $korinBaraLogo['compact']);
    }

    public function test_resolve_company_logo_returns_null_for_non_existent_or_missing_files(): void
    {
        $action = app(ResolveCompanyLogo::class);

        $result = $action->execute('non-existent-theme');
        $this->assertNull($result);

        // Mock config with non-existent file
        config()->set('branding.logos.mock-missing', [
            'light' => '/images/logo/does-not-exist.png',
            'dark' => '/images/logo/does-not-exist-light.png',
            'width' => 100,
            'height' => 100,
            'alt' => 'Mock',
        ]);

        $missingResult = $action->execute('mock-missing');
        $this->assertNull($missingResult);
    }

    public function test_company_resource_includes_resolved_logo(): void
    {
        $this->seed();

        $holding = Company::where('slug', 'derco-bara-energy')->firstOrFail();
        $payload = (new CompanyResource($holding))->toArray(Request::create('/id'));

        $this->assertArrayHasKey('logo', $payload);
        $this->assertNotNull($payload['logo']);
        $this->assertEquals('/images/logo/Logo-DercoBaraEnergy.png', $payload['logo']['light']);
        $this->assertEquals('/images/logo/Logo-DercoBaraEnergy-light.png', $payload['logo']['dark']);
        $this->assertEquals(676, $payload['logo']['width']);
        $this->assertEquals(369, $payload['logo']['height']);
        $this->assertEquals('PT Derco Bara Energy', $payload['logo']['alt']);
    }
}

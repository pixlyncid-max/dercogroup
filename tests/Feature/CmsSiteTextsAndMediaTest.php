<?php

declare(strict_types=1);

use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\Setting;
use App\Domain\Company\Services\SiteCustomizationService;
use App\Filament\Pages\ManageSiteTextsAndMedia;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
});

it('saves tenant-scoped bilingual UI text overrides in Filament and reflects them on public pages without locale bleed', function (): void {
    $admin = User::query()->firstOrFail();
    $beramal = Company::query()->where('slug', 'beramal-sukses-sejahtera')->firstOrFail();

    $this->actingAs($admin);
    Filament::setTenant($beramal);

    Livewire::test(ManageSiteTextsAndMedia::class)
        ->assertOk()
        ->set('texts.id.operations__nickel__badge', 'Operasi Tambang Nikel Kustom')
        ->set('texts.en.operations__nickel__badge', 'Custom Nickel Mining Operations')
        ->call('save')
        ->assertHasNoErrors();

    // Beramal ID reflects ID override
    $this->get('/id/bisnis-kami/beramal-sukses-sejahtera/operasi')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->where('translations.operations.nickel.badge', 'Operasi Tambang Nikel Kustom')
        );

    // Beramal EN reflects EN override
    $this->get('/en/our-business/beramal-sukses-sejahtera/operations')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->where('translations.operations.nickel.badge', 'Custom Nickel Mining Operations')
        );

    // Holding stays isolated with default translation
    $this->get('/id')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->where('translations.operations.nickel.badge', 'Operasi Nikel Laterit')
        );
});

it('shares tenant-scoped siteMedia overrides to Inertia public pages', function (): void {
    $beramal = Company::query()->where('slug', 'beramal-sukses-sejahtera')->firstOrFail();

    Setting::setValue(
        SiteCustomizationService::SITE_MEDIA_SETTING_KEY,
        [
            'operation_fallback_mine' => '/images/placeholders/aerial-pit-1280.webp',
        ],
        'media',
        $beramal,
    );

    $this->get('/id/bisnis-kami/beramal-sukses-sejahtera/operasi')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->where('siteMedia.operation_fallback_mine', '/images/placeholders/aerial-pit-1280.webp')
        );
});

it('serves public storage files via /media route without requiring public/storage symlink', function (): void {
    $this->get('/media/documents/sertifikat-kepatuhan-id.pdf')
        ->assertOk();

    $this->get('/media/../.env')
        ->assertNotFound();
});

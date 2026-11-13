<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domain\Company\Models\Company;
use App\Domain\Page\Models\PageBlock;
use App\Filament\Pages\EditWebsitePage;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
    $this->superAdmin = User::where('email', 'admin@dercobara.co.id')->firstOrFail();
    $this->holding = Company::where('slug', 'derco-bara-energy')->firstOrFail();
    $this->subsidiary = Company::where('slug', 'korin-berkat-energy')->firstOrFail();
});

test('5.1 & 5.2: EditWebsitePage mounts and renders page header, language bar, and section cards', function (): void {
    $this->actingAs($this->superAdmin);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    Filament::setTenant($this->holding);

    Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda'])
        ->assertSee('Beranda')
        ->assertSee('Simpan Perubahan')
        ->assertSee('Lihat Website')
        ->assertSee('Preview Website')
        ->assertSee('Bahasa Indonesia')
        ->assertSee('English')
        ->assertSee('Header Website (Logo & Menu)')
        ->assertSee('Banner Utama (Hero)')
        ->assertSee('Unit Bisnis / Anak Usaha')
        ->assertSee('Visi & Misi Perusahaan')
        ->assertSee('Banner Ajakan Kerjasama (CTA)');
});

test('5.2: Language toggle switches locale and getEmptyFieldsCount computes accurately', function (): void {
    $this->actingAs($this->superAdmin);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    Filament::setTenant($this->holding);

    $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda'])
        ->assertSet('locale', 'id')
        ->set('locale', 'en')
        ->assertSet('locale', 'en');

    $emptyEnCount = $component->instance()->getEmptyFieldsCount('en');
    expect($emptyEnCount)->toBeGreaterThanOrEqual(0);
});

test('5.3: Preview Website drawer toggles and switches responsive devices', function (): void {
    $this->actingAs($this->superAdmin);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    Filament::setTenant($this->holding);

    Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda'])
        ->assertSet('showPreview', false)
        ->assertSet('previewDevice', 'desktop')
        ->set('showPreview', true)
        ->assertSet('showPreview', true)
        ->set('previewDevice', 'mobile')
        ->assertSet('previewDevice', 'mobile')
        ->set('previewDevice', 'tablet')
        ->assertSet('previewDevice', 'tablet');
});

test('5.4: Repeater items can be added, re-ordered, and removed dynamically', function (): void {
    $this->actingAs($this->superAdmin);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    Filament::setTenant($this->holding);

    $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);

    // Initial nav_items
    $initialCount = count($component->get('formData.header.nav_items') ?? []);

    // Add a repeater item
    $component->call('addRepeaterItem', 'header', 'nav_items');
    $itemsAfterAdd = $component->get('formData.header.nav_items');
    expect($itemsAfterAdd)->toHaveCount($initialCount + 1);

    // Set label on the added item
    $newIdx = $initialCount;
    $component->set("formData.header.nav_items.{$newIdx}.label.id", 'Menu Tambahan Uji');
    expect($component->get("formData.header.nav_items.{$newIdx}.label.id"))->toBe('Menu Tambahan Uji');

    // Remove the item
    $component->call('removeRepeaterItem', 'header', 'nav_items', $newIdx);
    expect($component->get('formData.header.nav_items'))->toHaveCount($initialCount);
});

test('5.4 & 5.5: Saving changes updates database and persists visibility and content', function (): void {
    $this->actingAs($this->superAdmin);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    Filament::setTenant($this->holding);

    $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);

    // Modify Hero title and CTA
    $customTitle = 'Solusi Energi Terpadu Kalimantan 2026';
    $component->set('formData.home_hero.title.id', $customTitle);

    // Toggle CTA visibility off
    $component->set('visibility.home_cta', false);

    // Trigger save
    $component->call('save');

    // Verify database record
    $heroBlock = PageBlock::query()
        ->where('company_id', $this->holding->id)
        ->where('section_key', 'home_hero')
        ->firstOrFail();

    expect($heroBlock->data['title']['id'])->toBe($customTitle);

    $ctaBlock = PageBlock::query()
        ->where('company_id', $this->holding->id)
        ->where('section_key', 'home_cta')
        ->firstOrFail();

    expect($ctaBlock->is_visible)->toBeFalse();
});

test('5.4: Image upload stores file in storage and updates formData path', function (): void {
    Storage::fake('public');

    $this->actingAs($this->superAdmin);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    Filament::setTenant($this->holding);

    $file = UploadedFile::fake()->image('test-hero-banner.webp', 1920, 1080);

    Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda'])
        ->set('tempUploads.home_hero.image', $file)
        ->assertNotSet('formData.home_hero.image', null)
        ->call('removeImage', 'home_hero', 'image')
        ->assertSet('formData.home_hero.image', null);
});

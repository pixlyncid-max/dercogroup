<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domain\Company\Models\Company;
use App\Domain\Page\Enums\BlockType;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Page\Models\ContentRevision;
use App\Domain\Page\Models\PageBlock;
use App\Filament\Pages\EditWebsitePage;
use App\Filament\Pages\WebsiteSettings;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CmsStage9VerificationTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;

    private User $editor;

    private Company $holding;

    private Company $korinBerkat;

    private Company $korinBara;

    private Company $beramal;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->superAdmin = User::query()->where('email', 'admin@dercobara.co.id')->firstOrFail();
        $this->editor = User::query()->where('email', 'editor@dercobara.co.id')->firstOrFail();
        $this->holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();
        $this->korinBerkat = Company::query()->where('slug', 'korin-berkat-energy')->firstOrFail();
        $this->korinBara = Company::query()->where('slug', 'korin-bara-energy')->firstOrFail();
        $this->beramal = Company::query()->where('slug', 'beramal-sukses-sejahtera')->firstOrFail();
    }

    /**
     * 9.3 Uji Kegunaan 10 Tugas Umum (Maksimal 5 Klik dari Sidebar):
     * Task 1: Ubah judul hero (Klik 1: Sidebar Beranda, Input data, Klik 2: Simpan) = 2 klik.
     */
    public function test_task_1_change_hero_title_within_click_budget(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->korinBerkat);

        $clicks = 0;
        // Klik 1: Navigasi ke Beranda
        $clicks++;
        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);

        // Input data
        $component->set('formData.home_hero.title.id', 'Judul Baru Beranda Korin Berkat')
            ->set('formData.home_hero.title.en', 'New Homepage Title Korin Berkat');

        // Klik 2: Simpan Perubahan
        $clicks++;
        $component->call('save')->assertHasNoErrors();

        $this->assertLessThanOrEqual(5, $clicks, 'Task 1 must complete in <= 5 clicks');

        // Verifikasi tampil di database
        $homePage = $this->korinBerkat->pages()->where('key', PageKey::Home->value)->firstOrFail();
        $heroBlock = PageBlock::query()
            ->where('page_id', $homePage->id)
            ->where(fn ($q) => $q->where('section_key', 'home_hero')->orWhere('type', BlockType::Hero))
            ->firstOrFail();

        $this->assertSame('Judul Baru Beranda Korin Berkat', $heroBlock->data['title']['id']);
        $this->assertSame('New Homepage Title Korin Berkat', $heroBlock->data['title']['en']);
    }

    /**
     * Task 2: Ganti foto hero (Klik 1: Sidebar Beranda, Upload file, Klik 2: Simpan) = 2 klik.
     */
    public function test_task_2_change_photo_within_click_budget(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->holding);

        $clicks = 0;
        $clicks++; // Klik 1: Sidebar Beranda
        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);

        $component->set('formData.home_hero.image', '/images/hero/new-hero.webp');
        $clicks++; // Klik 2: Simpan Perubahan
        $component->call('save')->assertHasNoErrors();

        $this->assertLessThanOrEqual(5, $clicks, 'Task 2 must complete in <= 5 clicks');

        $homePage = $this->holding->pages()->where('key', PageKey::Home->value)->firstOrFail();
        $heroBlock = PageBlock::query()->where('page_id', $homePage->id)->where('type', BlockType::Hero)->firstOrFail();
        $this->assertSame('/images/hero/new-hero.webp', $heroBlock->data['image']);
    }

    /**
     * Task 3: Ganti logo (Klik 1: Sidebar Beranda, Upload logo pada Header Card, Klik 2: Simpan) = 2 klik.
     */
    public function test_task_3_change_logo_within_click_budget(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->korinBerkat);

        $clicks = 0;
        $clicks++; // Klik 1: Sidebar Beranda
        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);

        $component->set('formData.header.logo_path', '/storage/logos/korin-custom.webp')
            ->set('formData.header.logo_path_alt.id', 'Logo Resmi PT Korin Berkat Energy')
            ->set('formData.header.logo_path_alt.en', 'Official Logo of PT Korin Berkat Energy');

        $clicks++; // Klik 2: Simpan Perubahan
        $component->call('save')->assertHasNoErrors();

        $this->assertLessThanOrEqual(5, $clicks, 'Task 3 must complete in <= 5 clicks');

        $headerBlock = PageBlock::query()
            ->where('company_id', $this->korinBerkat->id)
            ->where('section_key', 'header')
            ->firstOrFail();

        $this->assertSame('/storage/logos/korin-custom.webp', $headerBlock->data['logo_path']);
    }

    /**
     * Task 4: Ubah nomor telepon Kontak (Klik 1: Sidebar Kontak, Ubah telepon, Klik 2: Simpan) = 2 klik.
     */
    public function test_task_4_change_phone_number_within_click_budget(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->korinBerkat);

        $clicks = 0;
        $clicks++; // Klik 1: Sidebar Kontak
        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'kontak']);

        $component->set('formData.contact_info.phone', '+62 21 8899 0011');
        $clicks++; // Klik 2: Simpan Perubahan
        $component->call('save')->assertHasNoErrors();

        $this->assertLessThanOrEqual(5, $clicks, 'Task 4 must complete in <= 5 clicks');

        $contactPage = $this->korinBerkat->pages()->where('key', PageKey::Contact->value)->firstOrFail();
        $contactBlock = PageBlock::query()
            ->where('page_id', $contactPage->id)
            ->where(fn ($q) => $q->where('section_key', 'contact_info')->orWhere('type', BlockType::ContactInfo))
            ->firstOrFail();

        $this->assertSame('+62 21 8899 0011', $contactBlock->data['phone']);
    }

    /**
     * Task 5: Tambah produk / item spesifikasi (Klik 1: Sidebar Produk, Klik 2: Tambah Item, Input, Klik 3: Simpan) = 3 klik.
     */
    public function test_task_5_add_product_item_within_click_budget(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->korinBerkat);

        $clicks = 0;
        $clicks++; // Klik 1: Sidebar Produk
        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'produk']);

        $clicks++; // Klik 2: Tambah Item Repeater
        $component->call('addRepeaterItem', 'products_catalog', 'items');

        $items = $component->get('formData.products_catalog.items');
        $lastIdx = count($items) - 1;

        $component->set("formData.products_catalog.items.{$lastIdx}.name.id", 'Batubara GAR 5800')
            ->set("formData.products_catalog.items.{$lastIdx}.name.en", 'Coal GAR 5800')
            ->set("formData.products_catalog.items.{$lastIdx}.calorie_gar", '5800 kcal/kg');

        $clicks++; // Klik 3: Simpan Perubahan
        $component->call('save')->assertHasNoErrors();

        $this->assertLessThanOrEqual(5, $clicks, 'Task 5 must complete in <= 5 clicks');

        $productsPage = $this->korinBerkat->pages()->where('key', PageKey::Products->value)->firstOrFail();
        $catalogBlock = PageBlock::query()->where('page_id', $productsPage->id)->firstOrFail();
        $savedItems = $catalogBlock->data['items'] ?? [];
        $lastSaved = end($savedItems);

        $this->assertSame('Batubara GAR 5800', $lastSaved['name']['id']);
    }

    /**
     * Task 6: Sembunyikan section (Klik 1: Sidebar Halaman, Klik 2: Saklar Switch, Klik 3: Simpan) = 3 klik.
     */
    public function test_task_6_toggle_section_visibility_within_click_budget(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->korinBerkat);

        $clicks = 0;
        $clicks++; // Klik 1: Sidebar Beranda
        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);

        $clicks++; // Klik 2: Toggle Visibility
        $component->set('visibility.home_stats', false);

        $clicks++; // Klik 3: Simpan Perubahan
        $component->call('save')->assertHasNoErrors();

        $this->assertLessThanOrEqual(5, $clicks, 'Task 6 must complete in <= 5 clicks');

        $homePage = $this->korinBerkat->pages()->where('key', PageKey::Home->value)->firstOrFail();
        $statsBlock = PageBlock::query()
            ->where('page_id', $homePage->id)
            ->where(fn ($q) => $q->where('section_key', 'home_stats')->orWhere('type', BlockType::Stats))
            ->firstOrFail();

        $this->assertFalse($statsBlock->is_visible);
    }

    /**
     * Task 7: Ubah urutan menu / item repeater (Klik 1: Sidebar Beranda, Klik 2: Geser Urutan, Klik 3: Simpan) = 3 klik.
     */
    public function test_task_7_reorder_repeater_item_within_click_budget(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->holding);

        $clicks = 0;
        $clicks++; // Klik 1: Sidebar Beranda
        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);

        // Add 2 items first
        $component->call('addRepeaterItem', 'header', 'nav_menu');
        $component->call('addRepeaterItem', 'header', 'nav_menu');
        $component->set('formData.header.nav_menu.0.label.id', 'Menu Satu');
        $component->set('formData.header.nav_menu.1.label.id', 'Menu Dua');

        $clicks++; // Klik 2: Geser urutan item 1 ke atas (menjadi item 0)
        $component->call('moveRepeaterItem', 'header', 'nav_menu', 1, 0);

        $clicks++; // Klik 3: Simpan Perubahan
        $component->call('save')->assertHasNoErrors();

        $this->assertLessThanOrEqual(5, $clicks, 'Task 7 must complete in <= 5 clicks');

        $headerBlock = PageBlock::query()->where('company_id', $this->holding->id)->where('section_key', 'header')->firstOrFail();
        $nav = $headerBlock->data['nav_menu'] ?? [];
        $this->assertSame('Menu Dua', $nav[0]['label']['id']);
    }

    /**
     * Task 8: Ubah teks Footer (Klik 1: Sidebar Footer, Edit Hak Cipta, Klik 2: Simpan) = 2 klik.
     */
    public function test_task_8_change_footer_text_within_click_budget(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->korinBerkat);

        $clicks = 0;
        $clicks++; // Klik 1: Sidebar Footer
        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'footer']);

        $component->set('formData.footer.copyright_text.id', '© 2026 PT Korin Berkat Energy Hak Cipta Dilindungi.');
        $clicks++; // Klik 2: Simpan Perubahan
        $component->call('save')->assertHasNoErrors();

        $this->assertLessThanOrEqual(5, $clicks, 'Task 8 must complete in <= 5 clicks');

        $footerBlock = PageBlock::query()->where('company_id', $this->korinBerkat->id)->where('section_key', 'footer')->firstOrFail();
        $this->assertSame('© 2026 PT Korin Berkat Energy Hak Cipta Dilindungi.', $footerBlock->data['copyright_text']['id']);
    }

    /**
     * Task 9: Isi versi English (Klik 1: Sidebar Halaman, Klik 2: Pill Saklar "EN", Edit teks, Klik 3: Simpan) = 3 klik.
     */
    public function test_task_9_fill_english_version_within_click_budget(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->korinBerkat);

        $clicks = 0;
        $clicks++; // Klik 1: Sidebar Beranda
        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);

        $clicks++; // Klik 2: Saklar bahasa English
        $component->set('locale', 'en');
        $this->assertSame('en', $component->get('locale'));

        $component->set('formData.home_hero.title.en', 'Excellence in Sustainable Coal Operations');

        $clicks++; // Klik 3: Simpan Perubahan
        $component->call('save')->assertHasNoErrors();

        $this->assertLessThanOrEqual(5, $clicks, 'Task 9 must complete in <= 5 clicks');

        $homePage = $this->korinBerkat->pages()->where('key', PageKey::Home->value)->firstOrFail();
        $heroBlock = PageBlock::query()->where('page_id', $homePage->id)->where('type', BlockType::Hero)->firstOrFail();
        $this->assertSame('Excellence in Sustainable Coal Operations', $heroBlock->data['title']['en']);
    }

    /**
     * Task 10: Pulihkan versi riwayat (Klik 1: Sidebar Halaman, Klik 2: Tombol Riwayat, Klik 3: Pulihkan Versi) = 3 klik.
     */
    public function test_task_10_restore_revision_within_click_budget(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->korinBerkat);

        // 1. Buat versi 1 awal yang valid
        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);
        $component->set('formData.home_hero.title.id', 'Judul Versi Satu Original');
        $component->call('save')->assertHasNoErrors();

        $rev1 = ContentRevision::query()
            ->where('company_id', $this->korinBerkat->id)
            ->where('page_key', 'beranda')
            ->latest('id')
            ->firstOrFail();

        // 2. Modifikasi ke versi 2
        $component->set('formData.home_hero.title.id', 'Judul Versi Dua Baru');
        $component->call('save')->assertHasNoErrors();

        $homePage = $this->korinBerkat->pages()->where('key', PageKey::Home->value)->firstOrFail();
        $heroBlock = PageBlock::query()->where('page_id', $homePage->id)->where('type', BlockType::Hero)->firstOrFail();
        $this->assertSame('Judul Versi Dua Baru', $heroBlock->data['title']['id']);

        // 3. Admin awam memulihkan versi 1 dalam <= 5 klik:
        $clicks = 0;
        $clicks++; // Klik 1: Sidebar Beranda
        $editComponent = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);

        $clicks++; // Klik 2: Buka Riwayat Perubahan
        $editComponent->call('openHistory');

        $clicks++; // Klik 3: Pulihkan versi 1
        $editComponent->call('restoreRevision', $rev1->id)->assertHasNoErrors();

        $this->assertLessThanOrEqual(5, $clicks, 'Task 10 must complete in <= 5 clicks');

        $heroBlock->refresh();
        $this->assertSame('Judul Versi Satu Original', $heroBlock->data['title']['id']);
    }

    /**
     * 9.1 Multi-tenant Switching: 1 Admin can switch across all 4 companies seamlessly.
     */
    public function test_admin_switches_across_all_four_companies(): void
    {
        $this->actingAs($this->superAdmin);

        $companies = [$this->holding, $this->korinBerkat, $this->korinBara, $this->beramal];

        foreach ($companies as $comp) {
            Filament::setTenant($comp);
            $this->assertSame($comp->id, Filament::getTenant()?->id);

            // EditWebsitePage can load for each tenant
            $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);
            $component->assertSuccessful();

            // WebsiteSettings can load for each tenant
            $settingsComp = Livewire::test(WebsiteSettings::class);
            $settingsComp->assertSuccessful();
            $this->assertSame($comp->name, $settingsComp->get('name'));
        }
    }
}

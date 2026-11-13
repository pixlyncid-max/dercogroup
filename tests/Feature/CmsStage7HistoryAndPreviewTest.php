<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domain\Company\Models\Company;
use App\Domain\Page\Enums\BlockType;
use App\Domain\Page\Models\ContentRevision;
use App\Domain\Page\Models\Page;
use App\Domain\Page\Models\PageBlock;
use App\Filament\Pages\EditWebsitePage;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CmsStage7HistoryAndPreviewTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;

    protected User $editor;

    protected Company $holding;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->superAdmin = User::query()->where('email', 'admin@dercobara.co.id')->firstOrFail();
        $this->editor = User::query()->where('email', 'editor@dercobara.co.id')->firstOrFail();
        $this->holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_7_1_save_creates_content_revision_snapshot_and_increments_version(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->holding);

        $homePage = $this->holding->pages()->where('key', 'home')->firstOrFail();
        $initialVersion = (int) ($homePage->content_version ?? 1);

        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);

        $customTitle = 'Judul Hero Uji Riwayat Versi 2026';
        $component->set('formData.home_hero.title.id', $customTitle);
        $component->call('save');
        $component->assertHasNoErrors();

        // 1. Verify revision record was created in database
        $revision = ContentRevision::query()
            ->where('company_id', $this->holding->id)
            ->where('page_key', 'beranda')
            ->latest('id')
            ->first();

        $this->assertNotNull($revision);
        $this->assertEquals($this->superAdmin->id, $revision->user_id);
        $this->assertGreaterThan($initialVersion, $revision->version);
        $this->assertEquals($customTitle, $revision->snapshot['formData']['home_hero']['title']['id']);

        // 2. Verify page content_version incremented
        $homePage->refresh();
        $this->assertEquals($revision->version, $homePage->content_version);
    }

    public function test_7_1_content_revision_enforces_maximum_10_versions(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->holding);

        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);

        // Create 12 revisions consecutively
        for ($i = 1; $i <= 12; $i++) {
            $component->set('formData.home_hero.title.id', "Judul Iterasi {$i}");
            $component->call('save');
        }

        // Verify that only the 10 most recent revisions remain
        $count = ContentRevision::query()
            ->where('company_id', $this->holding->id)
            ->where('page_key', 'beranda')
            ->count();

        $this->assertEquals(10, $count);
    }

    public function test_7_1_restore_revision_loads_snapshot_and_persists_to_database(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->holding);

        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);

        // 1. Save first version
        $firstTitle = 'Judul Versi Pertama 100';
        $component->set('formData.home_hero.title.id', $firstTitle);
        $component->call('save');

        $firstRevision = ContentRevision::query()
            ->where('company_id', $this->holding->id)
            ->where('page_key', 'beranda')
            ->latest('id')
            ->firstOrFail();

        // 2. Save second version
        $secondTitle = 'Judul Versi Kedua 200';
        $component->set('formData.home_hero.title.id', $secondTitle);
        $component->call('save');

        $heroBlock = PageBlock::query()
            ->where('company_id', $this->holding->id)
            ->where(function ($q): void {
                $q->where('section_key', 'home_hero')
                    ->orWhere('type', BlockType::Hero);
            })
            ->firstOrFail();
        $this->assertEquals($secondTitle, $heroBlock->data['title']['id']);

        // 3. Restore the first revision
        $component->call('restoreRevision', $firstRevision->id);

        // Verify database reverted back to first title
        $heroBlock->refresh();
        $this->assertEquals($firstTitle, $heroBlock->data['title']['id']);

        // Verify restoration created a new audit revision record
        $latestRevision = ContentRevision::query()
            ->where('company_id', $this->holding->id)
            ->where('page_key', 'beranda')
            ->latest('id')
            ->firstOrFail();

        $this->assertStringContainsString('Dipulihkan dari versi', $latestRevision->summary ?? '');
    }

    public function test_7_2_concurrency_conflict_modal_triggers_when_db_version_is_higher(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->holding);

        // User A loads the form (at loadedVersion = 1)
        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);
        $this->assertEquals(1, $component->get('loadedVersion'));

        // In the background, User B modifies and bumps version in DB
        $homePage = $this->holding->pages()->where('key', 'home')->firstOrFail();
        $homePage->update(['content_version' => 5]);

        ContentRevision::recordSnapshot(
            company: $this->holding,
            pageKey: 'beranda',
            user: $this->editor,
            version: 5,
            snapshot: [
                'formData' => ['home_hero' => ['title' => ['id' => 'Perubahan Oleh Editor']]],
                'visibility' => [],
            ],
            summary: 'Pembaruan oleh Editor'
        );

        // User A attempts to save their outdated form
        $component->set('formData.home_hero.title.id', 'Perubahan Terlambat User A');
        $component->call('save');

        // Conflict modal should be triggered
        $this->assertTrue($component->get('showConflictModal'));
        $this->assertEquals('Editor Konten Derco', $component->get('conflictInfo.editor_name'));
        $this->assertEquals(5, $component->get('conflictInfo.version'));

        // DB should NOT have been overwritten
        $heroBlock = PageBlock::query()
            ->where('company_id', $this->holding->id)
            ->where(function ($q): void {
                $q->where('section_key', 'home_hero')
                    ->orWhere('type', BlockType::Hero);
            })
            ->firstOrFail();
        $this->assertNotEquals('Perubahan Terlambat User A', $heroBlock->data['title']['id'] ?? '');
    }

    public function test_7_2_concurrency_reload_latest_syncs_form_with_server_version(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->holding);

        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);

        // Background update to block in database
        $heroBlock = PageBlock::query()
            ->where('company_id', $this->holding->id)
            ->where(function ($q): void {
                $q->where('section_key', 'home_hero')
                    ->orWhere('type', BlockType::Hero);
            })
            ->firstOrFail();

        $data = $heroBlock->data;
        $data['title']['id'] = 'Judul Terbaru di Server';
        $heroBlock->update(['data' => $data]);

        $homePage = $this->holding->pages()->where('key', 'home')->firstOrFail();
        $homePage->update(['content_version' => 10]);

        // Trigger reloadLatest
        $component->call('reloadLatest');

        $this->assertFalse($component->get('showConflictModal'));
        $this->assertEquals(10, $component->get('loadedVersion'));
        $this->assertEquals('Judul Terbaru di Server', $component->get('formData.home_hero.title.id'));
    }

    public function test_7_2_concurrency_force_save_overwrites_server_and_increments_version(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->holding);

        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);

        $homePage = $this->holding->pages()->where('key', 'home')->firstOrFail();
        $homePage->update(['content_version' => 8]);

        // Force save with User A's changes
        $forceTitle = 'Judul Force Save User A';
        $component->set('formData.home_hero.title.id', $forceTitle);
        $component->call('forceSave');

        $this->assertFalse($component->get('showConflictModal'));

        // Database should be overwritten and version incremented beyond 8
        $heroBlock = PageBlock::query()
            ->where('company_id', $this->holding->id)
            ->where(function ($q): void {
                $q->where('section_key', 'home_hero')
                    ->orWhere('type', BlockType::Hero);
            })
            ->firstOrFail();
        $this->assertEquals($forceTitle, $heroBlock->data['title']['id']);

        $homePage->refresh();
        $this->assertGreaterThan(8, $homePage->content_version);
    }

    public function test_7_3_preview_website_drawer_switches_devices_and_contains_open_tab_button(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->holding);

        $component = Livewire::test(EditWebsitePage::class, ['pageKey' => 'beranda']);

        $component->set('showPreview', true);
        $component->set('previewDevice', 'tablet');
        $this->assertEquals('tablet', $component->get('previewDevice'));

        $component->set('previewDevice', 'mobile');
        $this->assertEquals('mobile', $component->get('previewDevice'));

        $component->assertSee('Buka Tab Baru');
        $component->assertSee('Live Preview Website');
    }
}

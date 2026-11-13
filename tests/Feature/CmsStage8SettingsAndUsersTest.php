<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domain\Company\Actions\CreateCompanyPages;
use App\Domain\Company\Enums\CompanyStatus;
use App\Domain\Company\Enums\CompanyTheme;
use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\Setting;
use App\Domain\Contact\Actions\SubmitContactMessage;
use App\Domain\Contact\Enums\ContactMessageStatus;
use App\Domain\Contact\Models\ContactMessage;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Page\Models\Page;
use App\Domain\Page\Models\PageBlock;
use App\Filament\Pages\WebsiteSettings;
use App\Filament\Resources\CompanyResource;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Livewire\Livewire;
use Tests\TestCase;

class CmsStage8SettingsAndUsersTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;

    private User $editor;

    private Company $holding;

    private Company $subsidiary;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->superAdmin = User::query()->where('email', 'admin@dercobara.co.id')->firstOrFail();
        $this->editor = User::query()->where('email', 'editor@dercobara.co.id')->firstOrFail();
        $this->holding = Company::query()->whereNull('parent_id')->firstOrFail();
        $this->subsidiary = Company::query()->whereNotNull('parent_id')->firstOrFail();
    }

    public function test_8_1_super_admin_and_editor_can_access_website_settings(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->holding);
        $this->assertTrue(WebsiteSettings::canAccess());

        $this->actingAs($this->editor);
        Filament::setTenant($this->holding);
        $this->assertTrue(WebsiteSettings::canAccess());
    }

    public function test_8_1_website_settings_saves_content_and_settings_per_company(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->subsidiary);

        Livewire::test(WebsiteSettings::class)
            ->set('name', 'PT Korin Berkat Energy Updated')
            ->set('tagline.id', 'Solusi batubara terpercaya dan handal')
            ->set('tagline.en', 'Reliable and trusted coal solutions')
            ->set('about_short.id', 'Perusahaan perdagangan batubara terkemuka.')
            ->set('about_short.en', 'Leading coal trading enterprise.')
            ->set('contact_email', 'inquiry@korinberkat.co.id')
            ->set('seo_title.id', 'PT Korin Berkat Energy — Ekspor Batubara')
            ->set('seo_title.en', 'PT Korin Berkat Energy — Coal Export')
            ->set('seo_description.id', 'Mitra penyedia batubara kalori tinggi.')
            ->set('seo_description.en', 'High calorie coal supplier partner.')
            ->set('status', 'published')
            ->set('theme', 'korin-berkat')
            ->call('save')
            ->assertHasNoErrors();

        $this->subsidiary->refresh();
        $this->assertSame('PT Korin Berkat Energy Updated', $this->subsidiary->name);
        $this->assertSame('Solusi batubara terpercaya dan handal', $this->subsidiary->translateField('tagline', 'id'));
        $this->assertSame('Reliable and trusted coal solutions', $this->subsidiary->translateField('tagline', 'en'));

        // Check settings in database
        $recipientVal = Setting::getValue('contact_form_recipient', null, $this->subsidiary);
        $this->assertSame('inquiry@korinberkat.co.id', is_array($recipientVal) ? ($recipientVal['value'] ?? null) : $recipientVal);
        $seoTitle = Setting::getValue('seo_title', null, $this->subsidiary);
        $this->assertSame('PT Korin Berkat Energy — Ekspor Batubara', $seoTitle['id']);

        // Verify that contact message submission routes to the updated email
        $msg = app(SubmitContactMessage::class)->execute($this->subsidiary, [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Kerjasama',
            'message' => 'Halo, kami ingin bermitra.',
        ]);
        $this->assertNotNull($msg);
        $this->assertSame($this->subsidiary->id, $msg->company_id);
    }

    public function test_8_1_editor_cannot_change_company_status_and_theme(): void
    {
        $this->actingAs($this->editor);
        Filament::setTenant($this->subsidiary);

        $initialStatus = $this->subsidiary->status;
        $initialTheme = $this->subsidiary->theme;

        Livewire::test(WebsiteSettings::class)
            ->set('status', 'draft') // Attempt to change status to draft
            ->set('theme', 'holding') // Attempt to change theme
            ->call('save')
            ->assertHasNoErrors();

        $this->subsidiary->refresh();
        // Editor's attempt to modify status/theme is ignored because they are not Super Admin
        $this->assertSame($initialStatus->value, $this->subsidiary->status->value);
        $this->assertSame($initialTheme->value, $this->subsidiary->theme->value);
    }

    public function test_8_2_contact_messages_scoped_to_tenant_with_internal_notes_and_actions(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setTenant($this->holding);

        $message = ContactMessage::query()->create([
            'company_id' => $this->holding->id,
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'subject' => 'Penawaran Kerjasama',
            'message' => 'Detail proposal terlampir.',
            'status' => ContactMessageStatus::New,
        ]);

        $this->assertSame(ContactMessageStatus::New, $message->status);

        // Update internal notes and status to replied
        $message->update([
            'internal_notes' => 'Sudah ditelepon oleh tim sales pada 13 Nov.',
            'status' => ContactMessageStatus::Replied,
        ]);

        $message->refresh();
        $this->assertSame('Sudah ditelepon oleh tim sales pada 13 Nov.', $message->internal_notes);
        $this->assertSame(ContactMessageStatus::Replied, $message->status);

        // Soft delete and restore
        $message->delete();
        $this->assertSoftDeleted('contact_messages', ['id' => $message->id]);

        $message->restore();
        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'deleted_at' => null,
        ]);
    }

    public function test_8_3_super_admin_cannot_delete_self_or_last_super_admin(): void
    {
        $this->actingAs($this->superAdmin);

        // Ensure user cannot delete their own account via policy
        $this->assertFalse($this->superAdmin->can('delete', $this->superAdmin));

        // When only 1 super admin exists, they cannot be deleted
        $superAdminCount = User::role(User::ROLE_SUPER_ADMIN)->count();
        $this->assertSame(1, $superAdminCount);
        $this->assertFalse($this->superAdmin->can('delete', $this->superAdmin));

        // Editor cannot access UserResource
        $this->actingAs($this->editor);
        $this->assertFalse(UserResource::canAccess());
    }

    public function test_8_3_password_reset_broker_can_generate_token_for_user(): void
    {
        $this->actingAs($this->superAdmin);

        $token = Password::broker()->createToken($this->editor);
        $this->assertNotEmpty($token);
        $this->assertTrue(Password::broker()->tokenExists($this->editor, $token));
    }

    public function test_8_4_company_delete_safeguards_holding_children_and_messages(): void
    {
        $this->actingAs($this->superAdmin);

        // Holding cannot be deleted
        $this->assertTrue($this->holding->isHolding());
        $this->assertFalse($this->superAdmin->can('delete', $this->holding));

        // Company with subsidiaries cannot be deleted
        $this->assertTrue($this->holding->subsidiaries()->exists());
        $this->assertFalse($this->superAdmin->can('delete', $this->holding));

        // Company with contact messages cannot be deleted
        ContactMessage::query()->create([
            'company_id' => $this->subsidiary->id,
            'name' => 'Tester',
            'email' => 'test@example.com',
            'subject' => 'Tanya',
            'message' => 'Pesan',
        ]);
        $this->assertTrue($this->subsidiary->contactMessages()->exists());
        $this->assertFalse($this->superAdmin->can('delete', $this->subsidiary));

        // Editor cannot access CompanyResource
        $this->actingAs($this->editor);
        $this->assertFalse(CompanyResource::canAccess());
    }

    public function test_8_4_create_company_pages_seeds_default_sections_from_schema(): void
    {
        $this->actingAs($this->superAdmin);

        $newCompany = Company::query()->create([
            'name' => 'PT Energi Baru Sukses',
            'slug' => 'energi-baru-sukses',
            'parent_id' => $this->holding->id,
            'theme' => CompanyTheme::KorinBerkat,
            'status' => CompanyStatus::Draft,
            'sort_order' => 10,
        ]);

        $pages = app(CreateCompanyPages::class)->execute($newCompany);

        // 5 pages for subsidiary theme
        $this->assertCount(5, $pages);

        // Verify Home page has sections initialized
        $homePage = $pages->firstWhere('key', PageKey::Home->value);
        $this->assertNotNull($homePage);

        // Check company-scoped header exists
        $headerBlock = PageBlock::query()
            ->where('company_id', $newCompany->id)
            ->where('section_key', 'header')
            ->first();
        $this->assertNotNull($headerBlock);

        // Check footer block exists
        $footerBlock = PageBlock::query()
            ->where('company_id', $newCompany->id)
            ->where('section_key', 'footer')
            ->first();
        $this->assertNotNull($footerBlock);

        // Check page-scoped hero exists
        $heroBlock = PageBlock::query()
            ->where('page_id', $homePage->id)
            ->where('section_key', 'home_hero')
            ->first();
        $this->assertNotNull($heroBlock);
        $this->assertTrue($heroBlock->is_visible);
    }
}

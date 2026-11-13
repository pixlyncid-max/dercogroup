<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Domain\Company\Enums\CompanyStatus;
use App\Domain\Company\Enums\CompanyTheme;
use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\Setting;
use App\Domain\Media\Actions\ProcessUploadedMediaAction;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use UnitEnum;

class WebsiteSettings extends Page
{
    use WithFileUploads;

    protected string $view = 'filament.pages.website-settings';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan Website';

    protected static ?int $navigationSort = 2;

    public string $locale = 'id';

    public ?int $companyId = null;

    public string $name = '';

    /**
     * @var array<string, string>
     */
    public array $tagline = ['id' => '', 'en' => ''];

    /**
     * @var array<string, string>
     */
    public array $about_short = ['id' => '', 'en' => ''];

    public string $contact_email = '';

    /**
     * @var array<string, string>
     */
    public array $seo_title = ['id' => '', 'en' => ''];

    /**
     * @var array<string, string>
     */
    public array $seo_description = ['id' => '', 'en' => ''];

    public ?string $seo_og_image = null;

    /**
     * @var UploadedFile|null
     */
    public $tempOgImage = null;

    public string $status = 'draft';

    public string $theme = 'holding';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user && ($user->isSuperAdmin() || $user->isEditor());
    }

    public function mount(): void
    {
        /** @var Company|null $tenant */
        $tenant = Filament::getTenant();

        if (! $tenant) {
            abort(404, 'Perusahaan tidak ditemukan.');
        }

        $this->companyId = $tenant->id;
        $this->name = $tenant->name;
        $this->tagline = [
            'id' => (string) ($tenant->translateField('tagline', 'id') ?? ''),
            'en' => (string) ($tenant->translateField('tagline', 'en') ?? ''),
        ];
        $this->about_short = [
            'id' => (string) ($tenant->translateField('about_short', 'id') ?? ''),
            'en' => (string) ($tenant->translateField('about_short', 'en') ?? ''),
        ];
        $this->status = $tenant->status->value;
        $this->theme = $tenant->theme->value;

        // Contact email
        $recipient = Setting::getValue('contact_form_recipient', null, $tenant);
        if (is_array($recipient)) {
            $this->contact_email = (string) ($recipient['value'] ?? '');
        } elseif (is_string($recipient)) {
            $this->contact_email = $recipient;
        } else {
            $companyEmail = $tenant->contacts()->whereNotNull('notify_email')->value('notify_email');
            $this->contact_email = is_string($companyEmail) ? $companyEmail : 'corsec@dercobara.co.id';
        }

        // SEO Settings
        $seoTitle = Setting::getValue('seo_title', null, $tenant);
        $this->seo_title = [
            'id' => is_array($seoTitle) ? (string) ($seoTitle['id'] ?? $tenant->name) : $tenant->name,
            'en' => is_array($seoTitle) ? (string) ($seoTitle['en'] ?? $tenant->name) : $tenant->name,
        ];

        $seoDesc = Setting::getValue('seo_description', null, $tenant);
        $this->seo_description = [
            'id' => is_array($seoDesc) ? (string) ($seoDesc['id'] ?? $this->tagline['id']) : $this->tagline['id'],
            'en' => is_array($seoDesc) ? (string) ($seoDesc['en'] ?? $this->tagline['en']) : $this->tagline['en'],
        ];

        $seoImage = Setting::getValue('seo_og_image', null, $tenant);
        $this->seo_og_image = is_string($seoImage) ? $seoImage : (is_array($seoImage) ? ($seoImage['value'] ?? null) : null);
    }

    public function setLocale(string $locale): void
    {
        $this->locale = in_array($locale, ['id', 'en'], true) ? $locale : 'id';
    }

    public function getActiveCompany(): ?Company
    {
        /** @var Company|null $tenant */
        $tenant = Filament::getTenant();

        return $tenant;
    }

    public function getMissingCountForLocale(string $targetLocale): int
    {
        $count = 0;

        if (trim($this->tagline[$targetLocale] ?? '') === '') {
            $count++;
        }
        if (trim($this->about_short[$targetLocale] ?? '') === '') {
            $count++;
        }
        if (trim($this->seo_title[$targetLocale] ?? '') === '') {
            $count++;
        }
        if (trim($this->seo_description[$targetLocale] ?? '') === '') {
            $count++;
        }

        return $count;
    }

    public function removeOgImage(): void
    {
        $this->seo_og_image = null;
        $this->tempOgImage = null;
    }

    public function save(): void
    {
        $isSuperAdmin = auth()->user()?->isSuperAdmin() ?? false;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'tagline.id' => ['nullable', 'string', 'max:255'],
            'tagline.en' => ['nullable', 'string', 'max:255'],
            'about_short.id' => ['nullable', 'string', 'max:1000'],
            'about_short.en' => ['nullable', 'string', 'max:1000'],
            'seo_title.id' => ['nullable', 'string', 'max:100'],
            'seo_title.en' => ['nullable', 'string', 'max:100'],
            'seo_description.id' => ['nullable', 'string', 'max:255'],
            'seo_description.en' => ['nullable', 'string', 'max:255'],
        ];

        if ($isSuperAdmin) {
            $rules['status'] = ['required', Rule::in(['draft', 'published'])];
            $rules['theme'] = ['required', Rule::in(['holding', 'korin-berkat', 'korin-bara', 'beramal'])];
        }

        $this->validate($rules, [
            'name.required' => 'Nama perusahaan wajib diisi.',
            'contact_email.required' => 'Email tujuan kontak wajib diisi.',
            'contact_email.email' => 'Format email tujuan kontak tidak valid.',
        ]);

        /** @var Company|null $company */
        $company = Company::find($this->companyId);
        if (! $company) {
            Notification::make()->danger()->title('Perusahaan tidak ditemukan')->send();

            return;
        }

        // Handle OG Image upload
        if ($this->tempOgImage instanceof UploadedFile) {
            $result = app(ProcessUploadedMediaAction::class)->execute(
                file: $this->tempOgImage,
                directory: 'seo',
                maxKilobytes: 5120, // 5MB
                convertToWebp: true,
            );

            if ($result['status'] === 'success') {
                $this->seo_og_image = $result['path'];
                $this->tempOgImage = null;
            } else {
                Notification::make()
                    ->danger()
                    ->title('Gagal Mengunggah Gambar Pratinjau')
                    ->body($result['message'])
                    ->send();

                return;
            }
        }

        // Update Company model
        $company->name = $this->name;
        $company->tagline = $this->tagline;
        $company->about_short = $this->about_short;

        if ($isSuperAdmin) {
            $company->status = CompanyStatus::from($this->status);
            $company->theme = CompanyTheme::from($this->theme);
        }

        $company->save();

        // Update Settings model
        Setting::setValue('contact_form_recipient', $this->contact_email, 'contact', $company);
        Setting::setValue('seo_title', $this->seo_title, 'seo', $company);
        Setting::setValue('seo_description', $this->seo_description, 'seo', $company);
        Setting::setValue('seo_og_image', $this->seo_og_image, 'seo', $company);

        Notification::make()
            ->success()
            ->title('Pengaturan Berhasil Disimpan')
            ->body('Informasi identitas, kontak, dan SEO website telah diperbarui.')
            ->send();
    }
}

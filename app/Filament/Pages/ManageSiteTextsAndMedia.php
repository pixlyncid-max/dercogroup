<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\Setting;
use App\Domain\Company\Services\SiteCustomizationService;
use App\Domain\Media\Actions\ProcessUploadedMediaAction;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use UnitEnum;

class ManageSiteTextsAndMedia extends Page
{
    use WithFileUploads;

    protected string $view = 'filament.pages.manage-site-texts-and-media';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-language';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Teks & Media Website';

    protected static ?string $title = 'Kontrol Teks & Media Website';

    protected static ?int $navigationSort = 3;

    public string $activeTab = 'texts';

    public string $locale = 'id';

    public string $searchQuery = '';

    public string $selectedGroup = 'all';

    public ?int $companyId = null;

    /**
     * Editable flat translations: ['id' => ['key' => 'val'], 'en' => ['key' => 'val']]
     *
     * @var array{id: array<string, string>, en: array<string, string>}
     */
    public array $texts = [
        'id' => [],
        'en' => [],
    ];

    /**
     * Default flat translations for comparison & reset.
     *
     * @var array{id: array<string, string>, en: array<string, string>}
     */
    public array $defaults = [
        'id' => [],
        'en' => [],
    ];

    /**
     * Stored media slot paths: ['hero_fallback' => 'site-media/...']
     *
     * @var array<string, string|null>
     */
    public array $mediaPaths = [];

    /**
     * Temporary Livewire uploads keyed by slot name.
     *
     * @var array<string, UploadedFile|null>
     */
    public array $tempMediaUploads = [];

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
        $service = app(SiteCustomizationService::class);

        $this->defaults = [
            'id' => $service->getDefaultFlatTranslations('id'),
            'en' => $service->getDefaultFlatTranslations('en'),
        ];

        $storedOverrides = Setting::getValue(SiteCustomizationService::UI_TEXTS_SETTING_KEY, [], $tenant);
        if (! is_array($storedOverrides)) {
            $storedOverrides = [];
        }

        foreach (['id', 'en'] as $loc) {
            $locOverrides = is_array($storedOverrides[$loc] ?? null) ? $storedOverrides[$loc] : [];
            foreach ($this->defaults[$loc] as $key => $defaultVal) {
                $fieldKey = self::encodeKey($key);
                $overrideVal = $locOverrides[$key] ?? null;
                $this->texts[$loc][$fieldKey] = is_string($overrideVal) && trim($overrideVal) !== ''
                    ? $overrideVal
                    : $defaultVal;
            }
        }

        $storedMedia = Setting::getValue(SiteCustomizationService::SITE_MEDIA_SETTING_KEY, [], $tenant);
        if (! is_array($storedMedia)) {
            $storedMedia = [];
        }

        foreach (array_keys(SiteCustomizationService::mediaSlots()) as $slot) {
            $val = $storedMedia[$slot] ?? null;
            $this->mediaPaths[$slot] = is_string($val) && trim($val) !== '' ? $val : null;
            $this->tempMediaUploads[$slot] = null;
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = in_array($tab, ['texts', 'media'], true) ? $tab : 'texts';
    }

    public function setLocale(string $locale): void
    {
        $this->locale = in_array($locale, ['id', 'en'], true) ? $locale : 'id';
    }

    public static function encodeKey(string $dotKey): string
    {
        return str_replace('.', '__', $dotKey);
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getFilteredGroupedTexts(): array
    {
        $query = mb_strtolower(trim($this->searchQuery));
        $currentDefaults = $this->defaults[$this->locale] ?? [];
        $grouped = [];

        foreach ($currentDefaults as $dotKey => $defaultVal) {
            $fieldKey = self::encodeKey($dotKey);
            $value = (string) ($this->texts[$this->locale][$fieldKey] ?? $defaultVal);
            $group = SiteCustomizationService::groupLabelForKey($dotKey);

            if ($this->selectedGroup !== 'all' && $group !== $this->selectedGroup) {
                continue;
            }

            if ($query !== '') {
                $haystack = mb_strtolower($dotKey.' '.$value.' '.$defaultVal);
                if (! str_contains($haystack, $query)) {
                    continue;
                }
            }

            $grouped[$group][$dotKey] = $value;
        }

        return $grouped;
    }

    /**
     * @return array<int, string>
     */
    public function getAvailableGroups(): array
    {
        return [
            'Umum & Tombol',
            'Navigasi & Footer',
            'Hero, Visi Misi & Profil',
            'Produk & Spesifikasi',
            'Operasi, Alur Kerja & Nikel',
            'Kontak & Kepatuhan',
        ];
    }

    public function getOverrideCountForLocale(string $targetLocale): int
    {
        $count = 0;
        foreach ($this->defaults[$targetLocale] ?? [] as $key => $defaultVal) {
            $fieldKey = self::encodeKey($key);
            if (($this->texts[$targetLocale][$fieldKey] ?? $defaultVal) !== $defaultVal) {
                $count++;
            }
        }

        return $count;
    }

    public function resetTextKey(string $key): void
    {
        if (isset($this->defaults[$this->locale][$key])) {
            $this->texts[$this->locale][self::encodeKey($key)] = $this->defaults[$this->locale][$key];
        }
    }

    public function resetAllTextsForLocale(): void
    {
        foreach ($this->defaults[$this->locale] as $key => $defaultVal) {
            $this->texts[$this->locale][self::encodeKey($key)] = $defaultVal;
        }

        Notification::make()
            ->info()
            ->title('Teks dikembalikan ke bawaan')
            ->body('Klik tombol Simpan Perubahan untuk menerapkan reset ke website.')
            ->send();
    }

    public function removeMediaSlot(string $slot): void
    {
        if (array_key_exists($slot, $this->mediaPaths)) {
            $this->mediaPaths[$slot] = null;
            $this->tempMediaUploads[$slot] = null;
        }
    }

    public function getMediaPreviewUrl(string $slot): string
    {
        $path = $this->mediaPaths[$slot] ?? null;
        if (is_string($path) && trim($path) !== '') {
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
                return $path;
            }

            return Storage::disk('public')->url($path);
        }

        $slots = SiteCustomizationService::mediaSlots();

        return $slots[$slot]['default'] ?? '/images/placeholders/hero-excavator-1280.webp';
    }

    /**
     * @return array<string, array{label: string, description: string, default: string}>
     */
    public function getMediaSlots(): array
    {
        return SiteCustomizationService::mediaSlots();
    }

    public function save(): void
    {
        /** @var Company|null $company */
        $company = Company::find($this->companyId);
        if (! $company) {
            Notification::make()->danger()->title('Perusahaan tidak ditemukan')->send();

            return;
        }

        // 1. Save only non-default UI text overrides for ID & EN
        $textOverrides = ['id' => [], 'en' => []];
        foreach (['id', 'en'] as $loc) {
            foreach ($this->defaults[$loc] as $key => $defaultVal) {
                $fieldKey = self::encodeKey($key);
                $currentVal = trim((string) ($this->texts[$loc][$fieldKey] ?? ''));
                if ($currentVal !== '' && $currentVal !== $defaultVal) {
                    $textOverrides[$loc][$key] = $currentVal;
                }
            }
        }

        Setting::setValue(SiteCustomizationService::UI_TEXTS_SETTING_KEY, $textOverrides, 'ui_texts', $company);

        // 2. Process any uploaded media slots
        $mediaAction = app(ProcessUploadedMediaAction::class);
        foreach (array_keys(SiteCustomizationService::mediaSlots()) as $slot) {
            $upload = $this->tempMediaUploads[$slot] ?? null;
            if ($upload instanceof UploadedFile) {
                $result = $mediaAction->execute(
                    file: $upload,
                    directory: "site-media/{$company->slug}",
                    maxKilobytes: 5120,
                    convertToWebp: true,
                );

                if ($result['status'] === 'success') {
                    $this->mediaPaths[$slot] = $result['path'];
                    $this->tempMediaUploads[$slot] = null;
                } else {
                    Notification::make()
                        ->danger()
                        ->title("Gagal Mengunggah Gambar ({$slot})")
                        ->body($result['message'])
                        ->send();

                    return;
                }
            }
        }

        $cleanMedia = array_filter(
            $this->mediaPaths,
            static fn (?string $path): bool => is_string($path) && trim($path) !== '',
        );

        Setting::setValue(SiteCustomizationService::SITE_MEDIA_SETTING_KEY, $cleanMedia, 'media', $company);

        Notification::make()
            ->success()
            ->title('Teks & Media Website Disimpan')
            ->body("Perubahan teks antarmuka dan gambar untuk {$company->name} langsung aktif di website.")
            ->send();
    }
}

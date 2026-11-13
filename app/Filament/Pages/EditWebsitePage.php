<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Domain\Company\Models\Company;
use App\Domain\Media\Actions\ProcessUploadedMediaAction;
use App\Domain\Page\Blocks\Block;
use App\Domain\Page\Blocks\BlockRegistry;
use App\Domain\Page\Enums\BlockType;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Page\Models\ContentRevision;
use App\Domain\Page\Models\PageBlock;
use App\Domain\Page\Schema\SectionSchema;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Pages\PageConfiguration;
use Filament\Panel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Livewire\WithFileUploads;

class EditWebsitePage extends Page
{
    use WithFileUploads;

    protected string $view = 'filament.pages.edit-website-page';

    protected static bool $shouldRegisterNavigation = true;

    public string $pageKey = 'beranda';

    public string $locale = 'id';

    public ?int $companyId = null;

    /**
     * @var array<string, array<string, mixed>>
     */
    public array $formData = [];

    /**
     * @var array<string, bool>
     */
    public array $visibility = [];

    /**
     * Temporary uploaded files keyed by dot path (e.g. "header.logo_path")
     *
     * @var array<string, mixed>
     */
    public array $tempUploads = [];

    public bool $showPreview = false;

    public string $previewDevice = 'desktop';

    public bool $isSaving = false;

    public int $loadedVersion = 1;

    public bool $showHistoryModal = false;

    public bool $showPreviewModal = false;

    public ?int $selectedRevisionId = null;

    public bool $showConflictModal = false;

    /**
     * @var array{editor_name?: string, updated_at?: string, version?: int}
     */
    public array $conflictInfo = [];

    public static function routes(Panel $panel, ?PageConfiguration $configuration = null): void
    {
        $middleware = static::getRouteMiddleware($panel);

        Route::get('/halaman/{pageKey}', static::class)
            ->middleware($middleware)
            ->withoutMiddleware(static::getWithoutRouteMiddleware($panel))
            ->name('halaman');
    }

    public static function getRelativeRouteName(Panel $panel): string
    {
        return 'halaman';
    }

    public function mount(string $pageKey = 'beranda'): void
    {
        $tenant = Filament::getTenant();
        if (! $tenant instanceof Company) {
            abort(404, 'Perusahaan tidak ditemukan');
        }

        $validPages = static::getAvailablePagesForCompany($tenant);
        if (! array_key_exists($pageKey, $validPages)) {
            abort(404, "Halaman '{$pageKey}' tidak tersedia untuk {$tenant->name}.");
        }

        $this->pageKey = $pageKey;
        $this->companyId = $tenant->id;
        session(['filament_last_tenant_slug' => $tenant->slug]);

        $this->loadSectionsData($tenant);
    }

    /**
     * Load block data and visibility status for each section of this page.
     */
    public function loadSectionsData(Company $tenant): void
    {
        $sections = $this->getSections();
        $normalizedKey = SectionSchema::normalizePageKey($this->pageKey);
        $pageKeyEnum = PageKey::tryFrom($normalizedKey) ?? PageKey::tryFrom($this->pageKey);
        $page = $tenant->pages()->where('key', $pageKeyEnum ?? $normalizedKey)->first();

        foreach ($sections as $section) {
            $sectionKey = $section['section_key'];
            $blockType = $section['block_type'];
            $blockClass = BlockRegistry::for($blockType);

            if ($section['is_company_scoped']) {
                $block = PageBlock::query()
                    ->where('company_id', $tenant->id)
                    ->where(function ($q) use ($sectionKey, $blockType): void {
                        $q->where('section_key', $sectionKey)
                            ->orWhere('type', $blockType);
                    })
                    ->first();
            } else {
                $block = $page ? $page->blocks()
                    ->where(function ($q) use ($sectionKey, $blockType): void {
                        $q->where('section_key', $sectionKey)
                            ->orWhere('type', $blockType);
                    })
                    ->first() : null;
            }

            $defaultData = $blockClass::defaults();
            $rawBlockData = $block?->data ?? [];

            $this->formData[$sectionKey] = array_replace_recursive(
                $defaultData,
                is_array($rawBlockData) ? $rawBlockData : [],
            );

            $this->visibility[$sectionKey] = $block?->is_visible ?? true;
        }

        if ($page) {
            $this->loadedVersion = (int) ($page->content_version ?? 1);
        } else {
            $this->loadedVersion = (int) ($tenant->content_version ?? 1);
        }
    }

    /**
     * Handle temporary file upload automatically via Livewire with WebP optimization.
     */
    public function updatedTempUploads($value, $key): void
    {
        if ($value instanceof UploadedFile) {
            try {
                $result = app(ProcessUploadedMediaAction::class)->execute($value);
                Arr::set($this->formData, $key, $result['path']);

                $formatLabel = strtoupper($result['format']);
                Notification::make()
                    ->title("Media berhasil diunggah ({$formatLabel})")
                    ->body('File disimpan dengan optimal untuk performa web.')
                    ->success()
                    ->send();
            } catch (ValidationException $e) {
                Notification::make()
                    ->title('Gagal mengunggah file')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();

                throw $e;
            }
        }
    }

    /**
     * Remove an image from a section field.
     */
    public function removeImage(string $sectionKey, string $fieldName): void
    {
        Arr::set($this->formData, "{$sectionKey}.{$fieldName}", null);
    }

    /**
     * Remove an image from a repeater item.
     */
    public function removeRepeaterImage(string $sectionKey, string $fieldName, int $index, string $subFieldName): void
    {
        Arr::set($this->formData, "{$sectionKey}.{$fieldName}.{$index}.{$subFieldName}", null);
    }

    /**
     * Add an item to a repeater field with complete default subfield values.
     */
    public function addRepeaterItem(string $sectionKey, string $fieldName): void
    {
        $section = collect($this->getSections())->firstWhere('section_key', $sectionKey);
        if (! $section) {
            return;
        }

        $blockClass = BlockRegistry::for($section['block_type']);
        $fieldDef = collect($blockClass::fields())->firstWhere('name', $fieldName);
        $subFields = $fieldDef['fields'] ?? [];

        $newItem = Block::buildDefaultsFromFields($subFields);
        $this->formData[$sectionKey][$fieldName][] = $newItem;
    }

    /**
     * Remove an item from a repeater field.
     */
    public function removeRepeaterItem(string $sectionKey, string $fieldName, int $index): void
    {
        if (isset($this->formData[$sectionKey][$fieldName][$index])) {
            unset($this->formData[$sectionKey][$fieldName][$index]);
            $this->formData[$sectionKey][$fieldName] = array_values($this->formData[$sectionKey][$fieldName]);
        }
    }

    /**
     * Re-order repeater items (move up/down or drag-drop).
     */
    public function moveRepeaterItem(string $sectionKey, string $fieldName, int $fromIndex, int $toIndex): void
    {
        $list = $this->formData[$sectionKey][$fieldName] ?? [];
        if (! isset($list[$fromIndex]) || ! isset($list[$toIndex])) {
            return;
        }

        $item = array_splice($list, $fromIndex, 1);
        array_splice($list, $toIndex, 0, $item);
        $this->formData[$sectionKey][$fieldName] = array_values($list);
    }

    /**
     * Save all sections of this page to database.
     */
    public function save(): void
    {
        $tenant = $this->getActiveCompany();
        if (! $tenant instanceof Company) {
            return;
        }

        $this->isSaving = true;
        $sections = $this->getSections();

        // 1. Strictly validate image alt text in both ID and EN before saving (WCAG 2.1 AA & SEO)
        if (! $this->validateImageAltTexts($sections)) {
            $this->isSaving = false;

            return;
        }

        $this->performSave($tenant, 'Pembaruan konten halaman', force: false);
    }

    /**
     * Core saving routine with optimistic concurrency checking and revision snapshotting.
     */
    public function performSave(Company $tenant, ?string $summary = null, bool $force = false): void
    {
        $this->isSaving = true;
        $sections = $this->getSections();
        $normalizedKey = SectionSchema::normalizePageKey($this->pageKey);
        $pageKeyEnum = PageKey::tryFrom($normalizedKey) ?? PageKey::tryFrom($this->pageKey);
        $page = $tenant->pages()->where('key', $pageKeyEnum ?? $normalizedKey)->first();

        $currentDbVersion = $page ? (int) ($page->content_version ?? 1) : (int) ($tenant->content_version ?? 1);

        // Optimistic concurrency locking check
        if (! $force && $currentDbVersion > $this->loadedVersion) {
            $latestRev = ContentRevision::query()
                ->where('company_id', $tenant->id)
                ->where('page_key', $this->pageKey)
                ->with('user')
                ->latest('id')
                ->first();

            $this->conflictInfo = [
                'editor_name' => $latestRev?->user?->name ?? 'Admin lain',
                'updated_at' => $latestRev?->created_at?->diffForHumans() ?? 'baru saja',
                'version' => $currentDbVersion,
            ];
            $this->showConflictModal = true;
            $this->isSaving = false;

            Notification::make()
                ->title('Konflik Perubahan Terdeteksi')
                ->body("Halaman ini telah diubah oleh {$this->conflictInfo['editor_name']} ({$this->conflictInfo['updated_at']}).")
                ->warning()
                ->send();

            return;
        }

        foreach ($sections as $section) {
            $sectionKey = $section['section_key'];
            $blockType = $section['block_type'];
            $data = $this->formData[$sectionKey] ?? [];
            $isVisible = $this->visibility[$sectionKey] ?? true;

            if ($section['is_company_scoped']) {
                $block = PageBlock::query()
                    ->where('company_id', $tenant->id)
                    ->where(function ($q) use ($sectionKey, $blockType): void {
                        $q->where('section_key', $sectionKey)
                            ->orWhere('type', $blockType);
                    })
                    ->first();

                if ($block) {
                    $block->update([
                        'section_key' => $sectionKey,
                        'type' => $blockType,
                        'is_visible' => $isVisible,
                        'data' => $data,
                    ]);
                } else {
                    PageBlock::query()->create([
                        'company_id' => $tenant->id,
                        'section_key' => $sectionKey,
                        'page_id' => null,
                        'type' => $blockType,
                        'is_visible' => $isVisible,
                        'data' => $data,
                    ]);
                }
            } elseif ($page) {
                $block = $page->blocks()
                    ->where(function ($q) use ($sectionKey, $blockType): void {
                        $q->where('section_key', $sectionKey)
                            ->orWhere('type', $blockType);
                    })
                    ->first();

                if ($block) {
                    $block->update([
                        'section_key' => $sectionKey,
                        'type' => $blockType,
                        'is_visible' => $isVisible,
                        'data' => $data,
                    ]);
                } else {
                    $page->blocks()->create([
                        'company_id' => $tenant->id,
                        'section_key' => $sectionKey,
                        'type' => $blockType,
                        'is_visible' => $isVisible,
                        'data' => $data,
                    ]);
                }
            }
        }

        // Increment version
        $nextVersion = max($currentDbVersion, $this->loadedVersion) + 1;
        if ($page) {
            $page->update(['content_version' => $nextVersion]);
            $page->touch();
        } else {
            $tenant->update(['content_version' => $nextVersion]);
        }
        $tenant->touch();
        $this->loadedVersion = $nextVersion;

        // Record revision snapshot (max 10 versions auto-enforced)
        $user = auth()->user();
        ContentRevision::recordSnapshot(
            company: $tenant,
            pageKey: $this->pageKey,
            user: $user instanceof User ? $user : null,
            version: $nextVersion,
            snapshot: [
                'formData' => $this->formData,
                'visibility' => $this->visibility,
            ],
            summary: $summary ?? 'Pembaruan konten halaman'
        );

        $this->isSaving = false;
        $this->showConflictModal = false;

        Notification::make()
            ->title('Perubahan berhasil disimpan!')
            ->body('Seluruh konten website telah diperbarui secara langsung.')
            ->success()
            ->send();
    }

    /**
     * Concurrency resolution: Reload the latest version from server.
     */
    public function reloadLatest(): void
    {
        $tenant = $this->getActiveCompany();
        if ($tenant) {
            $this->loadSectionsData($tenant);
        }
        $this->showConflictModal = false;

        Notification::make()
            ->title('Versi Terbaru Dimuat')
            ->body('Isi formulir telah diselaraskan dengan data terbaru di database.')
            ->info()
            ->send();
    }

    /**
     * Concurrency resolution: Force save current form data over server version.
     */
    public function forceSave(): void
    {
        $tenant = $this->getActiveCompany();
        if (! $tenant) {
            return;
        }

        $this->performSave($tenant, 'Ditulis paksa menimpa versi sebelumnya', force: true);
    }

    public function openHistory(): void
    {
        $this->showHistoryModal = true;
    }

    public function closeHistory(): void
    {
        $this->showHistoryModal = false;
        $this->showPreviewModal = false;
        $this->selectedRevisionId = null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, ContentRevision>
     */
    public function getRevisions()
    {
        $tenant = $this->getActiveCompany();
        if (! $tenant) {
            return collect();
        }

        return ContentRevision::query()
            ->where('company_id', $tenant->id)
            ->where('page_key', $this->pageKey)
            ->with('user')
            ->orderByDesc('id')
            ->take(ContentRevision::MAX_REVISIONS_PER_PAGE)
            ->get();
    }

    public function previewRevision(int $revisionId): void
    {
        $this->selectedRevisionId = $revisionId;
        $this->showPreviewModal = true;
    }

    public function restoreRevision(int $revisionId): void
    {
        $tenant = $this->getActiveCompany();
        if (! $tenant) {
            return;
        }

        $revision = ContentRevision::query()
            ->where('company_id', $tenant->id)
            ->where('page_key', $this->pageKey)
            ->findOrFail($revisionId);

        $snapshot = $revision->snapshot;
        if (isset($snapshot['formData']) && is_array($snapshot['formData'])) {
            $this->formData = $snapshot['formData'];
        }
        if (isset($snapshot['visibility']) && is_array($snapshot['visibility'])) {
            $this->visibility = $snapshot['visibility'];
        }

        $restoreSummary = 'Dipulihkan dari versi ' . $revision->created_at->format('d M Y H:i');
        $this->performSave($tenant, $restoreSummary, force: true);

        $this->closeHistory();

        Notification::make()
            ->title('Versi Berhasil Dipulihkan!')
            ->body("Konten halaman telah dikembalikan ke arsip versi {$revision->created_at->format('d M Y H:i')}.")
            ->success()
            ->send();
    }

    /**
     * Strictly validate that every uploaded image has both ID and EN alt text (WCAG 2.1 AA & SEO).
     *
     * @param  list<array<string, mixed>>  $sections
     */
    protected function validateImageAltTexts(array $sections): bool
    {
        $hasErrors = false;

        foreach ($sections as $section) {
            $sectionKey = $section['section_key'];
            $sectionName = $section['name'] ?? $sectionKey;
            $data = $this->formData[$sectionKey] ?? [];
            $blockClass = BlockRegistry::for($section['block_type']);

            foreach ($blockClass::fields() as $field) {
                $name = $field['name'];
                $type = $field['type'] ?? 'text';

                if ($type === 'image') {
                    $imgPath = $data[$name] ?? null;
                    if (is_string($imgPath) && trim($imgPath) !== '') {
                        $altId = trim((string) ($data["{$name}_alt"]['id'] ?? ''));
                        $altEn = trim((string) ($data["{$name}_alt"]['en'] ?? ''));

                        if ($altId === '' || $altEn === '') {
                            $hasErrors = true;
                            $errorKey = "formData.{$sectionKey}.{$name}_alt";
                            $errorMsg = "Teks Alt (Bahasa Indonesia dan English) wajib diisi untuk foto pada bagian '{$sectionName}' sebelum disimpan.";
                            $this->addError($errorKey, $errorMsg);
                        }
                    }
                }

                if ($type === 'repeater' && is_array($data[$name] ?? null)) {
                    $subFields = $field['fields'] ?? [];
                    $hasImageSubfield = collect($subFields)->contains(fn ($sub) => ($sub['type'] ?? '') === 'image');

                    if ($hasImageSubfield) {
                        foreach ($data[$name] as $index => $row) {
                            foreach ($subFields as $sub) {
                                if (($sub['type'] ?? '') === 'image') {
                                    $subName = $sub['name'];
                                    $subImg = $row[$subName] ?? null;

                                    if (is_string($subImg) && trim($subImg) !== '') {
                                        $subAltId = trim((string) ($row["{$subName}_alt"]['id'] ?? ''));
                                        $subAltEn = trim((string) ($row["{$subName}_alt"]['en'] ?? ''));

                                        if ($subAltId === '' || $subAltEn === '') {
                                            $hasErrors = true;
                                            $rowNum = $index + 1;
                                            $errorKey = "formData.{$sectionKey}.{$name}.{$index}.{$subName}_alt";
                                            $errorMsg = "Teks Alt (Bahasa Indonesia dan English) wajib diisi untuk foto pada item ke-{$rowNum} bagian '{$sectionName}'.";
                                            $this->addError($errorKey, $errorMsg);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($hasErrors) {
            Notification::make()
                ->title('Gagal menyimpan perubahan')
                ->body('Terdapat foto yang belum dilengkapi Teks Alt (ID & EN).')
                ->danger()
                ->send();

            return false;
        }

        return true;
    }

    /**
     * Count empty translatable fields in a given locale for the language badge.
     */
    public function getEmptyFieldsCount(string $targetLocale): int
    {
        $count = 0;
        foreach ($this->getSections() as $section) {
            $key = $section['section_key'];
            $data = $this->formData[$key] ?? [];
            $blockClass = BlockRegistry::for($section['block_type']);

            foreach ($blockClass::fields() as $field) {
                $name = $field['name'];
                $type = $field['type'] ?? 'text';

                if (in_array($type, ['translatable_text', 'translatable_textarea', 'translatable_richtext'], true)) {
                    $val = $data[$name][$targetLocale] ?? '';
                    if (trim((string) $val) === '') {
                        $count++;
                    }
                }

                if ($type === 'image') {
                    $altVal = $data["{$name}_alt"][$targetLocale] ?? '';
                    if (trim((string) $altVal) === '') {
                        $count++;
                    }
                }

                if ($type === 'repeater' && is_array($data[$name] ?? null)) {
                    $subFields = $field['fields'] ?? [];
                    foreach ($data[$name] as $row) {
                        foreach ($subFields as $sub) {
                            $subName = $sub['name'];
                            $subType = $sub['type'] ?? 'text';
                            if (in_array($subType, ['translatable_text', 'translatable_textarea'], true)) {
                                $subVal = $row[$subName][$targetLocale] ?? '';
                                if (trim((string) $subVal) === '') {
                                    $count++;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $count;
    }

    /**
     * Resolve public preview URL for the currently edited page.
     */
    public function getPublicUrl(): string
    {
        $tenant = $this->getActiveCompany();
        if (! $tenant instanceof Company) {
            return url('/id');
        }

        $normalized = SectionSchema::normalizePageKey($this->pageKey);
        $page = $tenant->pages()->where('key', $normalized)->first();

        if ($normalized === PageKey::Home->value) {
            return $tenant->isHolding() ? url('/id') : url("/id/bisnis-kami/{$tenant->slug}");
        }

        if ($page) {
            $slug = $page->getTranslation('slug', 'id', false) ?: $normalized;
            if ($tenant->isHolding()) {
                return url("/id/{$slug}");
            }

            return url("/id/bisnis-kami/{$tenant->slug}/{$slug}");
        }

        return url('/id');
    }

    public function getTitle(): string
    {
        $tenant = $this->getActiveCompany();
        if ($tenant instanceof Company) {
            $pages = static::getAvailablePagesForCompany($tenant);

            return $pages[$this->pageKey]['label'] ?? 'Editor Halaman';
        }

        return 'Editor Halaman';
    }

    public function getSubheading(): ?string
    {
        $tenant = $this->getActiveCompany();
        if ($tenant instanceof Company) {
            $pages = static::getAvailablePagesForCompany($tenant);

            return $pages[$this->pageKey]['subheading'] ?? null;
        }

        return null;
    }

    /**
     * @return array<string, array{label: string, subheading: string, icon: string}>
     */
    public static function getAvailablePagesForCompany(Company $company): array
    {
        if ($company->isHolding()) {
            return [
                'beranda' => [
                    'label' => 'Beranda',
                    'subheading' => 'Kelola header logo, menu navigasi, dan seluruh section halaman utama holding.',
                    'icon' => 'heroicon-o-home',
                ],
                'tentang-kami' => [
                    'label' => 'Tentang Kami',
                    'subheading' => 'Kelola narasi profil, sejarah, kepemimpinan, dan nilai perusahaan holding.',
                    'icon' => 'heroicon-o-information-circle',
                ],
                'bisnis-kami' => [
                    'label' => 'Bisnis Kami',
                    'subheading' => 'Kelola portofolio bisnis dan sinergi anak usaha holding.',
                    'icon' => 'heroicon-o-briefcase',
                ],
                'kontak' => [
                    'label' => 'Kontak',
                    'subheading' => 'Kelola informasi kantor pusat, formulir hubungi kami, dan lokasi.',
                    'icon' => 'heroicon-o-phone',
                ],
                'footer' => [
                    'label' => 'Footer',
                    'subheading' => 'Kelola informasi footer, tautan legal, dan hak cipta holding.',
                    'icon' => 'heroicon-o-bars-3-bottom-left',
                ],
            ];
        }

        return [
            'beranda' => [
                'label' => 'Beranda',
                'subheading' => "Kelola header logo, menu navigasi, dan seluruh section beranda {$company->name}.",
                'icon' => 'heroicon-o-home',
            ],
            'tentang-kami' => [
                'label' => 'Tentang Kami',
                'subheading' => "Kelola profil, visi misi, dan keunggulan {$company->name}.",
                'icon' => 'heroicon-o-information-circle',
            ],
            'produk' => [
                'label' => 'Produk',
                'subheading' => 'Kelola katalog produk, spesifikasi batubara, dan parameter kualitas.',
                'icon' => 'heroicon-o-cube',
            ],
            'operasi' => [
                'label' => 'Operasi',
                'subheading' => 'Kelola fasilitas tambang, infrastruktur pelabuhan, dan armada logistik.',
                'icon' => 'heroicon-o-cog-6-tooth',
            ],
            'kontak' => [
                'label' => 'Kontak',
                'subheading' => 'Kelola kontak operasional dan formulir inquiry pemasaran.',
                'icon' => 'heroicon-o-phone',
            ],
            'footer' => [
                'label' => 'Footer',
                'subheading' => "Kelola informasi footer dan hak cipta {$company->name}.",
                'icon' => 'heroicon-o-bars-3-bottom-left',
            ],
        ];
    }

    /**
     * @return array<NavigationItem>
     */
    public static function getNavigationItems(): array
    {
        $tenant = Filament::getTenant();
        if (! $tenant instanceof Company) {
            return [];
        }

        $pages = static::getAvailablePagesForCompany($tenant);
        $items = [];
        $sort = 1;

        foreach ($pages as $key => $page) {
            $items[] = NavigationItem::make($page['label'])
                ->icon($page['icon'])
                ->group('Halaman Website')
                ->sort($sort++)
                ->url(static::getUrl(['pageKey' => $key, 'tenant' => $tenant]))
                ->isActiveWhen(fn (): bool => request()->route('pageKey') === $key);
        }

        return $items;
    }

    public function getActiveCompany(): ?Company
    {
        $tenant = Filament::getTenant();
        if ($tenant instanceof Company) {
            return $tenant;
        }

        if ($this->companyId) {
            return Company::find($this->companyId);
        }

        return null;
    }

    /**
     * @return list<array{
     *     section_key: string,
     *     block_type: BlockType,
     *     label: string,
     *     help_text: string,
     *     is_company_scoped: bool,
     *     is_toggleable: bool,
     *     is_relational: bool
     * }>
     */
    public function getSections(): array
    {
        $tenant = $this->getActiveCompany();
        if (! $tenant instanceof Company) {
            return [];
        }

        return SectionSchema::getSectionsForPage($tenant->theme, $this->pageKey);
    }
}

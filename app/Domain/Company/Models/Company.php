<?php

declare(strict_types=1);

namespace App\Domain\Company\Models;

use App\Domain\Company\Actions\ResolveCompanyLogo;
use App\Domain\Company\Enums\CompanyStatus;
use App\Domain\Company\Enums\CompanyTheme;
use App\Domain\Compliance\Models\Certification;
use App\Domain\Contact\Models\CompanyContact;
use App\Domain\Contact\Models\ContactMessage;
use App\Domain\Operation\Models\Operation;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Page\Models\Page;
use App\Domain\Product\Models\Product;
use App\Support\FilamentThemeResolver;
use App\Support\HasFallbackTranslations;
use App\Support\HasWebpConversions;
use App\Support\RecordsAuditLog;
use Database\Factories\CompanyFactory;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;

class Company extends Model implements HasAvatar, HasMedia, HasName
{
    use HasFactory;
    use HasFallbackTranslations;
    use HasWebpConversions;
    use RecordsAuditLog;

    protected static function newFactory(): CompanyFactory
    {
        return CompanyFactory::new();
    }

    /**
     * @var list<string>
     */
    public array $translatable = [
        'tagline',
        'about_short',
        'logo_alt',
    ];

    protected $fillable = [
        'parent_id',
        'slug',
        'name',
        'theme',
        'tagline',
        'about_short',
        'logo_alt',
        'sort_order',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'theme' => CompanyTheme::class,
            'status' => CompanyStatus::class,
            'sort_order' => 'integer',
        ];
    }

    /**
     * Scope a query to only include published companies (FR-VIS).
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', CompanyStatus::Published);
    }

    public function isHolding(): bool
    {
        return $this->parent_id === null;
    }

    public function isPublished(): bool
    {
        return $this->status === CompanyStatus::Published;
    }

    public function getFilamentName(): string|Htmlable
    {
        $statusBadge = $this->isPublished()
            ? '<span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 ml-1.5">Published</span>'
            : '<span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800 ml-1.5">Draft</span>';

        return str('<span>'.$this->name.'</span> '.$statusBadge)->toHtmlString();
    }

    public function getFilamentAvatarUrl(): ?string
    {
        /** @var array{light?: string, compact?: string}|null $logoData */
        $logoData = app(ResolveCompanyLogo::class)->execute($this);

        $path = $logoData['compact'] ?? $logoData['light'] ?? null;

        return $path ? FilamentThemeResolver::resolveAssetUrl($path) : null;
    }

    public function hasPage(PageKey|string $key): bool
    {
        $keyString = $key instanceof PageKey ? $key->value : $key;

        if ($this->relationLoaded('pages')) {
            return $this->pages->contains(function (Page $page) use ($keyString): bool {
                $pageKey = $page->key instanceof PageKey ? $page->key->value : (string) $page->key;

                return $pageKey === $keyString;
            });
        }

        return $this->pages()->where('key', $keyString)->exists();
    }

    /**
     * @return BelongsTo<self, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return HasMany<self, $this>
     */
    public function subsidiaries(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * @return HasMany<self, $this>
     */
    public function publishedSubsidiaries(): HasMany
    {
        return $this->subsidiaries()->published();
    }

    /**
     * @return HasMany<Page, $this>
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class)->orderBy('sort_order');
    }

    /**
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class)->orderBy('sort_order');
    }

    /**
     * @return HasMany<Operation, $this>
     */
    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class)->orderBy('sort_order');
    }

    /**
     * @return HasMany<CompanyStat, $this>
     */
    public function stats(): HasMany
    {
        return $this->hasMany(CompanyStat::class)->orderBy('sort_order');
    }

    /**
     * @return HasMany<Certification, $this>
     */
    public function certifications(): HasMany
    {
        return $this->hasMany(Certification::class)->orderBy('sort_order');
    }

    /**
     * @return HasMany<CompanyContact, $this>
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(CompanyContact::class);
    }

    /**
     * @return HasMany<ContactMessage, $this>
     */
    public function contactMessages(): HasMany
    {
        return $this->hasMany(ContactMessage::class)->latest();
    }
}

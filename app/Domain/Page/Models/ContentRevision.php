<?php

declare(strict_types=1);

namespace App\Domain\Page\Models;

use App\Domain\Company\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentRevision extends Model
{
    public const MAX_REVISIONS_PER_PAGE = 10;

    protected $fillable = [
        'company_id',
        'page_key',
        'user_id',
        'version',
        'snapshot',
        'summary',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'snapshot' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record a new revision snapshot and enforce the 10-revision limit.
     *
     * @param  array{formData: array<string, mixed>, visibility: array<string, bool>}  $snapshot
     */
    public static function recordSnapshot(
        Company $company,
        string $pageKey,
        ?User $user,
        int $version,
        array $snapshot,
        ?string $summary = null
    ): self {
        $revision = static::query()->create([
            'company_id' => $company->id,
            'page_key' => $pageKey,
            'user_id' => $user?->id,
            'version' => $version,
            'snapshot' => $snapshot,
            'summary' => $summary,
        ]);

        // Auto-prune older revisions beyond 10
        $excessIds = static::query()
            ->where('company_id', $company->id)
            ->where('page_key', $pageKey)
            ->orderByDesc('id')
            ->skip(self::MAX_REVISIONS_PER_PAGE)
            ->take(50)
            ->pluck('id');

        if ($excessIds->isNotEmpty()) {
            static::query()->whereIn('id', $excessIds)->delete();
        }

        return $revision;
    }
}


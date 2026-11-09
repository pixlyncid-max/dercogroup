<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'auditable_type',
        'auditable_id',
        'event',
        'changes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'changes' => 'array',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(Model $model, string $event, array $changes = []): void
    {
        self::query()->create([
            'user_id' => Auth::id(),
            'auditable_type' => $model::class,
            'auditable_id' => (int) $model->getKey(),
            'event' => $event,
            'changes' => $changes,
        ]);
    }
}

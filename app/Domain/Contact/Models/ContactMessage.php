<?php

declare(strict_types=1);

namespace App\Domain\Contact\Models;

use App\Domain\Company\Models\Company;
use App\Domain\Contact\Enums\ContactMessageStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'locale',
        'name',
        'email',
        'phone',
        'organization',
        'subject',
        'message',
        'status',
        'ip_address',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ContactMessageStatus::class,
        ];
    }

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}

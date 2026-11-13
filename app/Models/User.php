<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Company\Models\Company;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasDefaultTenant, HasTenants
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasRoles;
    use Notifiable;

    public const ROLE_SUPER_ADMIN = 'Super Admin';

    public const ROLE_EDITOR = 'Editor';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole([self::ROLE_SUPER_ADMIN, self::ROLE_EDITOR]);
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->hasAnyRole([self::ROLE_SUPER_ADMIN, self::ROLE_EDITOR]);
    }

    /**
     * @return Collection<int, Company>
     */
    public function getTenants(Panel $panel): Collection
    {
        return Company::query()
            ->orderByRaw('parent_id IS NOT NULL') // Holding first
            ->orderBy('sort_order')
            ->get();
    }

    public function getDefaultTenant(Panel $panel): ?Model
    {
        $lastTenantSlug = session('filament_last_tenant_slug');
        if ($lastTenantSlug) {
            $lastCompany = Company::where('slug', $lastTenantSlug)->first();
            if ($lastCompany) {
                return $lastCompany;
            }
        }

        return Company::query()
            ->orderByRaw('parent_id IS NOT NULL') // Holding first
            ->orderBy('sort_order')
            ->first();
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(self::ROLE_SUPER_ADMIN);
    }

    public function isEditor(): bool
    {
        return $this->hasRole(self::ROLE_EDITOR);
    }
}

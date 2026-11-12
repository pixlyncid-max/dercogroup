<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class SettingPolicy extends CmsBasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function view(User $user, mixed $model): bool
    {
        return $user->isSuperAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(User $user, mixed $model): bool
    {
        return $user->isSuperAdmin();
    }

    public function delete(User $user, mixed $model): bool
    {
        return $user->isSuperAdmin();
    }
}

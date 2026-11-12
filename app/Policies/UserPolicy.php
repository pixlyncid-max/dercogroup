<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class UserPolicy extends CmsBasePolicy
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
        if (! $user->isSuperAdmin()) {
            return false;
        }

        if ($model instanceof User) {
            if ($user->id === $model->id) {
                return false;
            }

            if ($model->isSuperAdmin() && User::role(User::ROLE_SUPER_ADMIN)->count() <= 1) {
                return false;
            }
        }

        return true;
    }
}

<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

abstract class CmsBasePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->isCmsUser($user);
    }

    public function view(User $user, mixed $model): bool
    {
        return $this->isCmsUser($user);
    }

    public function create(User $user): bool
    {
        return $this->isCmsUser($user);
    }

    public function update(User $user, mixed $model): bool
    {
        return $this->isCmsUser($user);
    }

    public function delete(User $user, mixed $model): bool
    {
        return $this->isCmsUser($user);
    }

    public function deleteAny(User $user): bool
    {
        return $this->isCmsUser($user);
    }

    public function restore(User $user, mixed $model): bool
    {
        return $this->isCmsUser($user);
    }

    public function forceDelete(User $user, mixed $model): bool
    {
        return $user->isSuperAdmin();
    }

    protected function isCmsUser(User $user): bool
    {
        return $user->hasAnyRole([User::ROLE_SUPER_ADMIN, User::ROLE_EDITOR]);
    }
}


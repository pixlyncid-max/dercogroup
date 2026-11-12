<?php

declare(strict_types=1);

namespace App\Policies;

use App\Domain\Company\Models\Company;
use App\Models\User;

class CompanyPolicy extends CmsBasePolicy
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

        if ($model instanceof Company) {
            if ($model->isHolding() || $model->subsidiaries()->exists() || $model->contactMessages()->exists()) {
                return false;
            }
        }

        return true;
    }
}


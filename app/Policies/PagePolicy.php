<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class PagePolicy extends CmsBasePolicy
{
    public function delete(User $user, mixed $model): bool
    {
        return $user->isSuperAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }
}


<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

final class UserPolicy
{
    public function view(User $user, string $userId): Response
    {
        return $user->getKey() === $userId
            ? Response::allow()
            : Response::deny();
    }

    public function update(User $user, string $userId): Response
    {
        return $user->getKey() === $userId
            ? Response::allow()
            : Response::deny();
    }
}

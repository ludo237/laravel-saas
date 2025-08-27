<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;

/**
 * TODO:
 *  right now we are not leveraging the fine tuned permissions of the role
 *  because we only have admins and members
 */
final class TeamPolicy
{
    public function update(User $user, string $teamId): Response
    {
        $isAdmin = TeamMember::query()
            ->whereHas('role', function (Builder $query) {
                $query->where('slug', '=', 'admin');
            })
            ->where('user_id', '=', $user->getKey())
            ->where('team_id', '=', $teamId)
            ->exists();

        return $isAdmin
            ? Response::allow()
            : Response::deny();
    }
}

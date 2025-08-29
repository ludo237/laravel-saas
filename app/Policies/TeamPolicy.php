<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\TeamMember;
use App\Models\User;
use App\QueryBuilders\HasPermission;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;

final class TeamPolicy
{
    public function update(User $user, string $teamId): Response
    {
        $hasPermission = TeamMember::query()
            ->tap(fn ($query): Builder => new HasPermission(
                userId: $user->getKey(),
                permissionPath: 'team.edit',
                teamId: $teamId
            )($query))
            ->exists();

        return $hasPermission
            ? Response::allow()
            : Response::deny('You cannot update this team');
    }
}

<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use App\QueryBuilders\HasFreeTierSubscription;
use App\QueryBuilders\HasPermission;
use App\QueryBuilders\IdentifiedBy;
use Illuminate\Auth\Access\Response;

final class TeamInvitePolicy
{
    public function create(User $user, string $teamId): Response
    {
        // Check if team has free tier subscription (free tier teams cannot add members)
        $isFreeTier = Team::query()
            ->tap(fn ($query) => new IdentifiedBy($teamId)($query))
            ->tap(new HasFreeTierSubscription())
            ->exists();

        if ($isFreeTier) {
            return Response::deny('Free tier teams cannot add members. Please upgrade your subscription.');
        }

        // Check if user has member.create permission for this team
        $hasPermission = TeamMember::query()
            ->tap(new HasPermission($user->getKey(), 'member.create', $teamId))
            ->exists();

        return $hasPermission
            ? Response::allow()
            : Response::deny('You do not have permission to invite members to this team.');
    }
}

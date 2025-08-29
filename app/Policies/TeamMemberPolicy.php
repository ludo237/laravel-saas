<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;

final class TeamMemberPolicy
{
    public function delete(User $user, string $teamId, string $userTeamMemberId): Response
    {
        // If there's only one team member we cannot delete the team
        $count = TeamMember::query()
            ->where('team_id', '=', $teamId)
            ->count();

        if ($count === 1) {
            return Response::deny('Cannot remove the last member of the team');
        }

        // Only admins can remove members
        /** @var TeamMember|null $teamMember */
        $teamMember = TeamMember::query()
            ->whereHas('role', function (Builder $query): void {
                $query->where('slug', '=', 'admin');
            })
            ->where('user_id', '=', $user->getKey())
            ->where('team_id', '=', $teamId)
            ->first();

        if ($teamMember === null) {
            return Response::deny('Only admins can remove other members');
        }

        // A member cannot remove himself
        if ($teamMember->getAttributeValue('user_id') === $userTeamMemberId) {
            return Response::deny('You cannot remove yourself');
        }

        return Response::allow();
    }
}

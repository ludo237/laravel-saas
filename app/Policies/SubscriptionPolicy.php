<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use App\QueryBuilders\HasPermission;
use App\QueryBuilders\IdentifiedBy;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;

final class SubscriptionPolicy
{
    public function create(User $user, string $tierId, string $teamId): Response
    {
        // Check if team already has this tier (optimized database query)
        $hasCurrentTier = Team::query()
            ->tap(fn ($query): Builder => new IdentifiedBy($teamId)($query))
            ->whereHas('subscription', function (Builder $query) use ($tierId): void {
                $query->where('tier_id', $tierId);
            })
            ->exists();

        if ($hasCurrentTier) {
            return Response::deny('Please select a different subscription');
        }

        // Check user admin permission (following existing pattern)
        $hasPermission = TeamMember::query()
            ->tap(fn ($query): Builder => new HasPermission(
                userId: $user->getKey(),
                permissionPath: 'subscription.create',
                teamId: $teamId
            )($query)
            )
            ->exists();

        return $hasPermission
            ? Response::allow()
            : Response::deny('You do not have permission to create this subscription.');
    }

    public function cancel(User $user, string $subscriptionId): Response
    {
        // Check if user has the destroy permission
        $hasPermission = TeamMember::query()
            ->tap(fn ($query): Builder => new HasPermission(
                userId: $user->getKey(),
                permissionPath: 'subscription.destroy',
            )($query)
            )
            ->whereHas('team.subscription', function (Builder $query) use ($subscriptionId): void {
                $query->whereKey($subscriptionId);
            })
            ->exists();

        return $hasPermission
            ? Response::allow()
            : Response::deny('You do not have permission to cancel this subscription.');
    }
}

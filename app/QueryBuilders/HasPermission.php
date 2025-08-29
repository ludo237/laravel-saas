<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final readonly class HasPermission
{
    public function __construct(
        private string $userId,
        private string $permissionPath,
        private ?string $teamId = null
    ) {}

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $builder
     * @return Builder<TModel>
     */
    public function __invoke(Builder $builder): Builder
    {
        // Convert dot notation to JSON path
        // 'member.create' -> 'permissions->member' contains 'create'
        [$resource, $action] = explode('.', $this->permissionPath, 2);
        $jsonPath = "permissions->{$resource}";

        $query = $builder
            ->where('user_id', '=', $this->userId)
            ->whereHas('role', function (Builder $roleQuery) use ($jsonPath, $action): void {
                $roleQuery->whereJsonContains($jsonPath, $action);
            });

        // Add team constraint if provided
        if ($this->teamId !== null) {
            $query->where('team_id', '=', $this->teamId);
        }

        return $query;
    }
}

<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final readonly class HasMember
{
    public function __construct(private string $id, private ?string $teamId = null) {}

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $builder
     * @return Builder<TModel>
     */
    public function __invoke(Builder $builder): Builder
    {
        if (is_null($this->teamId)) {
            return $builder->whereHas('members', function (Builder $query) {
                $query->where('user_id', '=', $this->id);
            });
        }

        return $builder->whereHas('members', function (Builder $query) {
            $query
                ->where('user_id', '=', $this->id)
                ->where('team_id', '=', $this->teamId);
        });

    }
}

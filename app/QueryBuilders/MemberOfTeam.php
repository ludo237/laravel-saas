<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final readonly class MemberOfTeam
{
    private string $identifier;

    public function __construct(private Team|string $team)
    {
        $this->identifier = $this->team instanceof Team ? $this->team->getKey() : $this->team;
    }

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $builder
     * @return Builder<TModel>
     */
    public function __invoke(Builder $builder): Builder
    {
        return $builder->where('team_id', '=', $this->identifier);
    }
}

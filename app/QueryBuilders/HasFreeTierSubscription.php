<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final readonly class HasFreeTierSubscription
{
    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $builder
     * @return Builder<TModel>
     */
    public function __invoke(Builder $builder): Builder
    {
        return $builder->whereHas('subscription.tier', function (Builder $query): void {
            $query->where('name', '=', 'free_tier');
        });
    }
}

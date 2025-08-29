<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final readonly class IdentifiedBy
{
    public function __construct(private string $key) {}

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $builder
     * @return Builder<TModel>
     */
    public function __invoke(Builder $builder): Builder
    {
        return $builder->whereKey($this->key);
    }
}

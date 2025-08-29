<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Team>
 *
 * @codeCoverageIgnore
 */
final class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        return [
            'stripe_id' => 'cus_'.Str::random(14),
            'name' => $this->faker->userName(),
        ];
    }

    /**
     * @return Factory<Team>
     */
    public function asTesterCustomer(): Factory
    {
        return $this->state(fn (array $attributes): array => [
            'stripe_id' => 'cus_', // Add a static stripe_id from your sandbox
        ]);
    }
}

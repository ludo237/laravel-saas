<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Tier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tier>
 *
 * @codeCoverageIgnore
 */
final class TierFactory extends Factory
{
    protected $model = Tier::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(asText: true),
        ];
    }

    /**
     * @return Factory<Tier>
     */
    public function asZeroTier(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'zero tier',
        ]);
    }

    /**
     * @return Factory<Tier>
     */
    public function asPoorTier(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'poor tier',
        ]);
    }

    /**
     * @return Factory<Tier>
     */
    public function asFreeTier(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'free_tier',
        ]);
    }
}

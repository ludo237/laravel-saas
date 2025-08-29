<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TeamRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeamRole>
 *
 * @codeCoverageIgnore
 */
final class TeamRoleFactory extends Factory
{
    protected $model = TeamRole::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->word(),
            'name' => $this->faker->word(),
            'permissions' => [],
        ];
    }

    public function admin(): self
    {
        return $this->state(fn (array $attributes): array => [
            'slug' => 'admin',
            'name' => 'Administrator',
            'permissions' => [
                'team' => [
                    'edit',
                    'destroy',
                ],
                'member' => [
                    'create',
                    'edit',
                    'destroy',
                ],
                'subscription' => [
                    'create',
                    'edit',
                    'destroy',
                ],
            ],
        ]);
    }

    public function member(): self
    {
        return $this->state(fn (array $attributes): array => [
            'slug' => 'member',
            'name' => 'Member',
            'permissions' => [],
        ]);
    }
}

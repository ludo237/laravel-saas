<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeamMember>
 *
 * @codeCoverageIgnore
 */
final class TeamMemberFactory extends Factory
{
    protected $model = TeamMember::class;

    public function definition(): array
    {
        return [
            'team_id' => TeamFactory::new(),
            'user_id' => UserFactory::new(),
            'role_id' => TeamRoleFactory::new(),
            'joined_at' => $this->faker->dateTimeThisMonth,
            'default' => false,
        ];
    }

    public function default(): self
    {
        return $this->state(fn (array $attributes) => [
            'default' => true,
        ]);
    }
}

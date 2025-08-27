<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TeamInvite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeamInvite>
 */
final class TeamInviteFactory extends Factory
{
    protected $model = TeamInvite::class;

    public function definition(): array
    {
        return [
            'team_id' => TeamFactory::new(),
            'user_id' => UserFactory::new(),
            'role_id' => TeamRoleFactory::new(),
            'email' => $this->faker->safeEmail(),
        ];
    }
}

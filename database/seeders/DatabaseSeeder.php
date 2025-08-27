<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Factories\TeamFactory;
use Database\Factories\TeamRoleFactory;
use Database\Factories\TierFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $freeTier = TierFactory::new()
            ->asFreetier()
            ->create();

        $adminRole = TeamRoleFactory::new()
            ->admin()
            ->create();

        TeamRoleFactory::new()
            ->member()
            ->create();

        $team = TeamFactory::new()
            ->for($freeTier, 'subscription')
            ->create();

        UserFactory::new()
            ->asDefaultUser()
            ->hasAttached($team, [
                'role_id' => $adminRole->getKey(),
                'joined_at' => Date::now(),
                'default' => true,
            ], 'memberOfTeams')
            ->create();
    }
}

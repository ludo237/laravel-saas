<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 *
 * @codeCoverageIgnore
 */
final class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'email_verified_at' => null,
            'phone' => null,
            'phone_verified_at' => null,
            'password' => $this->faker->password(minLength: 8),
            'remember_token' => $this->faker->bothify('#?##??#??#'),
        ];
    }

    public function withVerifiedEmail(): self
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => $this->faker->dateTimeBetween('-1 month'),
        ]);
    }

    public function withPhone(): self
    {
        return $this->state(fn (array $attributes) => [
            'phone' => $this->faker->e164PhoneNumber,
            'phone_verified_at' => null,
        ]);
    }

    public function withVerifiedPhone(): self
    {
        return $this->state(fn (array $attributes) => [
            'phone' => $this->faker->e164PhoneNumber,
            'phone_verified_at' => $this->faker->dateTimeBetween('-1 month'),
        ]);
    }

    public function asDefaultUser(): self
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'foo@bar.com',
            'email_verified_at' => $this->faker->dateTimeBetween('-1 month'),
            'phone' => $this->faker->e164PhoneNumber,
            'phone_verified_at' => $this->faker->dateTimeBetween('-1 month'),
            'password' => 'supersecret',
        ]);
    }
}

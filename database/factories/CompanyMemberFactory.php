<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyMember>
 */
class CompanyMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'user_id' => User::factory(),
            'role' => $this->faker->randomElement(['owner', 'admin', 'member']),
            'status' => $this->faker->randomElement(['active', 'invited', 'inactive']),
            'joined_at' => $this->faker->optional()->dateTime(),
        ];
    }

    /**
     * Indicate that the member is an owner.
     */
    public function owner(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'owner',
            'status' => 'active',
            'joined_at' => now(),
        ]);
    }

    /**
     * Indicate that the member is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'status' => 'active',
            'joined_at' => now(),
        ]);
    }

    /**
     * Indicate that the member is a regular member.
     */
    public function member(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'member',
            'status' => 'active',
            'joined_at' => now(),
        ]);
    }

    /**
     * Indicate that the member is invited.
     */
    public function invited(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'invited',
            'joined_at' => null,
        ]);
    }

    /**
     * Indicate that the member is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'joined_at' => now(),
        ]);
    }
}

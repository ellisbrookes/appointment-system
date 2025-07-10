<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'telephone_number' => fake()->phoneNumber(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create a user with an active subscription.
     */
    public function subscribed(string $plan = 'default'): static
    {
        return $this->afterCreating(function (User $user) use ($plan) {
            // Create a fake subscription record without hitting Stripe API
            $pricingService = new \App\Services\PricingService();
            $productsWithPrices = $pricingService->getProductsWithPrices();

            // Get the first available price for trial subscription
            $defaultPrice = 'price_1QbtKfGVcskF822y3QlF13vZ'; // Default to a known value if nothing else found
            if ($productsWithPrices->isNotEmpty()) {
                $firstProductWithPrices = $productsWithPrices->first();
                if (isset($firstProductWithPrices->prices) && $firstProductWithPrices->prices->isNotEmpty()) {
                    $defaultPrice = $firstProductWithPrices->prices->first()['id'];
                }
            }

            $user->subscriptions()->create([
                'type' => $plan,
                'stripe_id' => 'sub_' . Str::random(14),
                'stripe_status' => 'active',
                'stripe_price' => $defaultPrice,
                'quantity' => 1,
                'trial_ends_at' => null,
                'ends_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}

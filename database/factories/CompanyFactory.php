<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->regexify('07[0-9]{9}'),
            'address' => $this->faker->streetAddress(),
            'postcode' => $this->faker->regexify('[A-Z]{1,2}[0-9][0-9A-Z]? [0-9][A-Z]{2}'),
            'description' => $this->faker->optional()->paragraph(),
            'user_id' => User::factory(),
        ];
    }
}

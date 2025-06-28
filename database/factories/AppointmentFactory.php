<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service' => $this->faker->randomElement(['Consultation', 'Follow-up', 'Treatment', 'Assessment']),
            'date' => $this->faker->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'timeslot' => $this->faker->randomElement(['09:00', '10:00', '11:00', '14:00', '15:00', '16:00']),
            'user_id' => User::factory(),
            'status' => $this->faker->randomElement(['open', 'cancelled', 'closed']),
        ];
    }

    /**
     * Indicate that the appointment is open.
     */
    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'open',
        ]);
    }

    /**
     * Indicate that the appointment is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    /**
     * Indicate that the appointment is closed.
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
        ]);
    }
}

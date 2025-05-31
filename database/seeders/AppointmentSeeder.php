<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\User;

class AppointmentSeeder extends Seeder
{
  public function run(): void
  {
    $users = User::all();

    foreach ($users as $user) {
        $appointmentsCount = rand(1, 3);

        for ($i = 0; $i < $appointmentsCount; $i++) {
            Appointment::create([
                'user_id' => $user->id,
                'service' => fake()->word(),
                'date' => now()->addDays(rand(1, 30))->toDateString(),
                'timeslot' => now()->setTime(rand(9, 17), 0)->format('H:i'),
            ]);
        }
    }
  }
}

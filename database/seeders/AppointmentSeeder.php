<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
  public function run(): void
  {
    $users = User::all();
    
    // Generate realistic timeslots based on default settings
    $availableTimeslots = $this->generateTimeslots();
    
    // Sample services for realistic data
    $services = [
        'General Consultation',
        'Follow-up Appointment', 
        'Initial Assessment',
        'Therapy Session',
        'Medical Check-up',
        'Dental Cleaning',
        'Eye Examination',
        'Vaccination',
        'Health Screening',
        'Specialist Consultation'
    ];
    
    $statuses = ['open', 'closed', 'cancelled'];

    foreach ($users as $user) {
        $appointmentsCount = rand(2, 5); // More realistic number of appointments

        for ($i = 0; $i < $appointmentsCount; $i++) {
            // Generate dates between today and 60 days in the future
            $futureDate = now()->addDays(rand(1, 60))->toDateString();
            
            Appointment::create([
                'user_id' => $user->id,
                'service' => fake()->randomElement($services),
                'date' => $futureDate,
                'timeslot' => fake()->randomElement($availableTimeslots),
                'status' => fake()->randomElement($statuses),
            ]);
        }
    }
  }
  
  /**
   * Generate realistic timeslots based on default settings
   * 09:00 to 17:00 with 30-minute intervals
   */
  private function generateTimeslots(): array
  {
    $timeslots = [];
    $startTime = Carbon::createFromTimeString('09:00');
    $endTime = Carbon::createFromTimeString('17:00');
    $interval = 30; // minutes
    
    $current = $startTime->copy();
    
    while ($current->lessThan($endTime)) {
        $timeslots[] = $current->format('H:i');
        $current->addMinutes($interval);
    }
    
    return $timeslots;
  }
}

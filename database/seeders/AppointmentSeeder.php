<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Company;
use Carbon\Carbon;
use Faker\Factory as Faker;

class AppointmentSeeder extends Seeder
{
  public function run(): void
  {
    $faker = Faker::create();
    $users = User::all();
    $companies = Company::all();
    
    // Generate realistic timeslots based on default settings
    $availableTimeslots = $this->generateTimeslots();
    
    // Get company data from cache if available
    $companyData = cache()->get('seeded_companies', []);
    
    // Default general services
    $generalServices = [
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

    // Create personal appointments for users (not associated with companies)
    foreach ($users as $user) {
        // Some users will have personal appointments, others only company appointments
        if (rand(1, 10) <= 6) { // 60% chance of having personal appointments
            $personalAppointmentsCount = rand(1, 3);

            for ($i = 0; $i < $personalAppointmentsCount; $i++) {
                // Generate dates between today and 60 days in the future
                $futureDate = now()->addDays(rand(1, 60))->toDateString();
                
                Appointment::create([
                    'user_id' => $user->id,
                    'company_id' => null, // Personal appointment
                    'service' => $faker->randomElement($generalServices),
                    'date' => $futureDate,
                    'timeslot' => $faker->randomElement($availableTimeslots),
                    'status' => $faker->randomElement($statuses),
                ]);
            }
        }
    }

    // Create company-based appointments
    foreach ($companies as $company) {
        // Get company-specific services if available
        $companyInfo = collect($companyData)->firstWhere('id', $company->id);
        $companyServices = $companyInfo['services'] ?? $generalServices;
        
        // Get company members to book appointments
        $companyMembers = $company->activeMembers()->with('user')->get();
        
        if ($companyMembers->isNotEmpty()) {
            // Create appointments for company members
            foreach ($companyMembers as $member) {
                $companyAppointmentsCount = rand(2, 5);
                
                for ($i = 0; $i < $companyAppointmentsCount; $i++) {
                    // Generate dates between today and 90 days in the future
                    $futureDate = now()->addDays(rand(1, 90))->toDateString();
                    
                    Appointment::create([
                        'user_id' => $member->user_id,
                        'company_id' => $company->id,
                        'service' => $faker->randomElement($companyServices),
                        'date' => $futureDate,
                        'timeslot' => $faker->randomElement($availableTimeslots),
                        'status' => $faker->randomElement($statuses),
                    ]);
                }
            }
        }
        
        // Also create some appointments for external users (non-members)
        $externalUsers = $users->whereNotIn('id', $companyMembers->pluck('user_id'));
        if ($externalUsers->isNotEmpty()) {
            $externalAppointmentsCount = rand(1, 3);
            
            for ($i = 0; $i < $externalAppointmentsCount; $i++) {
                $externalUser = $externalUsers->random();
                $futureDate = now()->addDays(rand(1, 45))->toDateString();
                
                Appointment::create([
                    'user_id' => $externalUser->id,
                    'company_id' => $company->id,
                    'service' => $faker->randomElement($companyServices),
                    'date' => $futureDate,
                    'timeslot' => $faker->randomElement($availableTimeslots),
                    'status' => $faker->randomElement($statuses),
                ]);
            }
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

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use Faker\Factory as Faker;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get some users to be company owners
        $users = User::all();
        
        // Sample company types for realistic data
        $companyTypes = [
            'Medical' => [
                'names' => ['City Medical Center', 'Downtown Health Clinic', 'Family Medical Practice', 'Wellness Medical Group'],
                'services' => ['General Practice', 'Specialist Consultations', 'Preventive Care', 'Health Screenings']
            ],
            'Dental' => [
                'names' => ['Bright Smile Dentistry', 'Family Dental Care', 'Modern Dental Solutions', 'Premier Dental Group'],
                'services' => ['Dental Cleanings', 'Oral Examinations', 'Cosmetic Dentistry', 'Orthodontics']
            ],
            'Legal' => [
                'names' => ['Smith & Associates Law Firm', 'Legal Solutions Partners', 'Downtown Legal Services', 'Professional Legal Group'],
                'services' => ['Legal Consultations', 'Contract Review', 'Family Law', 'Business Law']
            ],
            'Consulting' => [
                'names' => ['Business Solutions Inc', 'Strategic Consulting Partners', 'Professional Advisory Group', 'Expert Consulting Services'],
                'services' => ['Business Consulting', 'Strategic Planning', 'Market Analysis', 'Process Optimization']
            ],
            'Beauty' => [
                'names' => ['Elegant Beauty Salon', 'Modern Style Studio', 'Premium Beauty Services', 'Luxury Spa & Wellness'],
                'services' => ['Hair Styling', 'Facial Treatments', 'Massage Therapy', 'Beauty Consultations']
            ]
        ];

        $createdCompanies = [];

        foreach ($companyTypes as $type => $data) {
            // Create 1-2 companies per type
            $companiesPerType = rand(1, 2);
            
            for ($i = 0; $i < $companiesPerType; $i++) {
                // Get a random user to be the owner (ensure they don't already own a company in this seed)
                $availableUsers = $users->filter(function($user) use ($createdCompanies) {
                    return !in_array($user->id, array_column($createdCompanies, 'user_id'));
                });
                
                if ($availableUsers->isEmpty()) {
                    break; // No more available users
                }
                
                $owner = $availableUsers->random();
                
                $company = Company::create([
                    'name' => $faker->randomElement($data['names']) . ($i > 0 ? ' ' . ($i + 1) : ''),
                    'email' => strtolower(str_replace([' ', '&'], ['', 'and'], $faker->randomElement($data['names']))) . '@example.com',
                    'phone_number' => $faker->phoneNumber(),
                    'address' => $faker->streetAddress() . ', ' . $faker->city(),
                    'postcode' => $faker->postcode(),
                    'description' => "Professional {$type} services offering " . implode(', ', $faker->randomElements($data['services'], rand(2, 3))) . '. ' . $faker->sentence(10),
                    'user_id' => $owner->id,
                ]);
                
                $createdCompanies[] = [
                    'id' => $company->id,
                    'user_id' => $owner->id,
                    'type' => $type,
                    'services' => $data['services']
                ];
            }
        }

        // Store created companies for use in other seeders
        cache()->put('seeded_companies', $createdCompanies, now()->addMinutes(10));
    }
}

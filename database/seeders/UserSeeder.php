<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
  public function run(): void
  {
    $faker = Faker::create();

    // Default settings structure
    $defaultSettings = [
        'navigation_style' => 'sidebar',
        'timeslot_start' => '09:00',
        'timeslot_end' => '17:00', 
        'timeslot_interval' => 30,
        'time_format' => '24',
        'timezone' => 'UTC'
    ];

    // Admin user with default settings
    User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => Hash::make('admin123'),
        'is_admin' => true,
        'telephone_number' => $faker->phoneNumber(),
        'email_verified_at' => now(),
        'settings' => json_encode($defaultSettings)
    ]);

    // Regular user with different timezone and 12-hour format
    $userSettings = array_merge($defaultSettings, [
        'navigation_style' => 'top_nav',
        'time_format' => '12',
        'timezone' => 'America/New_York'
    ]);
    
    User::create([
        'name' => 'Regular User',
        'email' => 'user@example.com',
        'password' => Hash::make('password123'),
        'is_admin' => false,
        'telephone_number' => $faker->phoneNumber(),
        'email_verified_at' => now(),
        'settings' => json_encode($userSettings)
    ]);

    // Create additional test users with various settings
    for ($i = 1; $i <= 3; $i++) {
        $randomSettings = array_merge($defaultSettings, [
            'navigation_style' => $faker->randomElement(['sidebar', 'top_nav']),
            'timeslot_start' => $faker->randomElement(['08:00', '09:00', '10:00']),
            'timeslot_end' => $faker->randomElement(['16:00', '17:00', '18:00']),
            'timeslot_interval' => $faker->randomElement([15, 30, 60]),
            'time_format' => $faker->randomElement(['12', '24']),
            'timezone' => $faker->randomElement(['UTC', 'America/New_York', 'Europe/London', 'Asia/Tokyo'])
        ]);
        
        User::create([
            'name' => $faker->name(),
            'email' => $faker->unique()->safeEmail(),
            'password' => Hash::make('password123'),
            'is_admin' => false,
            'telephone_number' => $faker->phoneNumber(),
            'email_verified_at' => now(),
            'settings' => json_encode($randomSettings)
        ]);
    }
  }
}

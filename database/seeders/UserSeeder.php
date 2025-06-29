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

    // Admin user with default settings and active subscription
    User::factory()->subscribed()->create([
      'name' => 'Admin User',
      'email' => 'admin@example.com',
      'password' => Hash::make('admin123'),
      'is_admin' => true,
      'telephone_number' => $faker->phoneNumber(),
      'email_verified_at' => now(),
      'settings' => $defaultSettings
    ]);

    // Regular user with different timezone and 12-hour format and active subscription
    $userSettings = array_merge($defaultSettings, [
      'navigation_style' => 'top_nav',
      'time_format' => '12',
      'timezone' => 'America/New_York'
    ]);

    User::factory()->subscribed()->create([
      'name' => 'Regular User',
      'email' => 'user@example.com',
      'password' => Hash::make('password123'),
      'is_admin' => false,
      'telephone_number' => $faker->phoneNumber(),
      'email_verified_at' => now(),
      'settings' => $userSettings
    ]);

    // Create additional test users with various settings and active subscriptions
    // We need more users to properly seed companies and appointments
    for ($i = 1; $i <= 15; $i++) {
      $randomSettings = array_merge($defaultSettings, [
        'navigation_style' => $faker->randomElement(['sidebar', 'top_nav']),
        'timeslot_start' => $faker->randomElement(['08:00', '09:00', '10:00']),
        'timeslot_end' => $faker->randomElement(['16:00', '17:00', '18:00']),
        'timeslot_interval' => $faker->randomElement([15, 30, 60]),
        'time_format' => $faker->randomElement(['12', '24']),
        'timezone' => $faker->randomElement(['UTC', 'America/New_York', 'Europe/London', 'Asia/Tokyo'])
      ]);

      // Mix of subscribed and unsubscribed users for realistic data
      $userFactory = rand(1, 10) <= 7 ? User::factory()->subscribed() : User::factory();
      
      $userFactory->create([
        'name' => $faker->name(),
        'email' => $faker->unique()->safeEmail(),
        'password' => Hash::make('password123'),
        'is_admin' => false,
        'telephone_number' => $faker->phoneNumber(),
        'email_verified_at' => rand(1, 10) <= 8 ? now() : null, // 80% verified
        'settings' => $randomSettings
      ]);
    }
  }
}

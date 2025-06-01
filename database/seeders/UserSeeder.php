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

    // Admin user
    User::create([
        'name' => 'John Doe',
        'email' => 'admin@example.com',
        'password' => Hash::make('admin123'),
        'is_admin' => true,
        'telephone_number' => $faker->phoneNumber(),
    ]);

    // Regular user
    User::create([
        'name' => 'John Doe',
        'email' => 'user@example.com',
        'password' => Hash::make('password123'),
        'is_admin' => false,
        'telephone_number' => $faker->phoneNumber(),
    ]);
  }
}

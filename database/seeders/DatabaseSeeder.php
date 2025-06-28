<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  public function run(): void
  {
    $this->call([
        UserSeeder::class,           // Create users first
        CompanySeeder::class,        // Create companies with user owners
        CompanyMemberSeeder::class,  // Assign users to companies
        AppointmentSeeder::class,    // Create appointments for users and companies
    ]);
  }
}

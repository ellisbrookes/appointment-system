<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyMember;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;

class CompanyMemberSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();
        $users = User::all();
        
        foreach ($companies as $company) {
            // First, create the owner relationship
            CompanyMember::create([
                'company_id' => $company->id,
                'user_id' => $company->user_id,
                'email' => $company->user->email,
                'role' => 'owner',
                'status' => 'active',
                'joined_at' => Carbon::now()->subDays(rand(30, 365)), // Owner joined some time ago
            ]);
            
            // Get remaining users (excluding the owner)
            $availableUsers = $users->where('id', '!=', $company->user_id);
            
            // Randomly assign 1-3 additional members to each company
            $memberCount = rand(1, 3);
            $selectedUsers = $availableUsers->random(min($memberCount, $availableUsers->count()));
            
            foreach ($selectedUsers as $user) {
                // Determine role - mostly members with occasional admin
                $role = rand(1, 10) <= 8 ? 'member' : 'admin';
                
                // Determine status - mostly active with some pending invites
                $status = rand(1, 10) <= 8 ? 'active' : 'invited';
                
                CompanyMember::create([
                    'company_id' => $company->id,
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'role' => $role,
                    'status' => $status,
                    'joined_at' => $status === 'active' ? Carbon::now()->subDays(rand(1, 180)) : null,
                ]);
            }
        }
    }
}

<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanyMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_their_own_company()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $user->id]);
        
        // Create company membership
        CompanyMember::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'role' => 'owner',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        $response = $this->actingAs($user)->get("/dashboard/companies/{$company->id}");

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.company.show');
        $response->assertViewHas('company', $company);
    }

    public function test_user_can_edit_their_own_company()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $user->id]);
        
        // Create company membership with admin access
        CompanyMember::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'role' => 'owner',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        $response = $this->actingAs($user)->get("/dashboard/companies/{$company->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.company.edit');
        $response->assertViewHas('company', $company);
    }

    public function test_user_cannot_view_other_users_company()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get("/dashboard/companies/{$company->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_create_company()
    {
        $user = User::factory()->create();

        $companyData = [
            'name' => 'Test Company',
            'email' => 'test@company.com',
            'phone_number' => '07123456789',
            'address' => '123 Test Street',
            'postcode' => 'SW1A 1AA',
            'description' => 'A test company',
        ];

        $response = $this->actingAs($user)->post('/dashboard/companies', $companyData);

        $response->assertRedirect('/dashboard/companies');
        $response->assertSessionHas('alert.type', 'success');

        $this->assertDatabaseHas('companies', [
            'name' => 'Test Company',
            'email' => 'test@company.com',
            'user_id' => $user->id,
        ]);
    }
}

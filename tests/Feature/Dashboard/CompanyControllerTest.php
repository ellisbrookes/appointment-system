<?php

namespace Tests\Feature\Dashboard;

use App\Models\Company;
use App\Models\CompanyMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authenticated_user_can_view_companies_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/companies');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.company.index');
        $response->assertViewHas('activeCompanies');
        $response->assertViewHas('pendingInvitations');
    }

    #[Test]
    public function authenticated_user_can_view_create_company_form()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/companies/create');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.company.create');
    }

    #[Test]
    public function authenticated_user_can_create_company()
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

    #[Test]
    public function company_creation_requires_name()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/companies', []);

        $response->assertSessionHasErrors(['name']);
    }

    #[Test]
    public function company_creation_validates_email_format()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/companies', [
            'name' => 'Test Company',
            'email' => 'invalid-email',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    #[Test]
    public function company_creation_validates_uk_phone_number()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/companies', [
            'name' => 'Test Company',
            'phone_number' => 'invalid-phone',
        ]);

        $response->assertSessionHasErrors(['phone_number']);
    }

    #[Test]
    public function company_creation_validates_uk_postcode()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/companies', [
            'name' => 'Test Company',
            'postcode' => 'invalid-postcode',
        ]);

        $response->assertSessionHasErrors(['postcode']);
    }

    #[Test]
    public function user_can_view_their_own_company()
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

    #[Test]
    public function user_cannot_view_other_users_company()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get("/dashboard/companies/{$company->id}");

        $response->assertStatus(403);
    }

    #[Test]
    public function user_can_edit_their_own_company()
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

    #[Test]
    public function user_cannot_edit_other_users_company()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get("/dashboard/companies/{$company->id}/edit");

        $response->assertStatus(403);
    }

    #[Test]
    public function user_can_update_their_own_company()
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

        $updateData = [
            'name' => 'Updated Company Name',
            'email' => 'updated@company.com',
            'phone_number' => '07987654321',
            'address' => '456 Updated Street',
            'postcode' => 'W1A 0AX',
            'description' => 'Updated description',
        ];

        $response = $this->actingAs($user)->put("/dashboard/companies/{$company->id}", $updateData);

        $response->assertRedirect('/dashboard/companies');
        $response->assertSessionHas('alert.type', 'success');

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'Updated Company Name',
            'email' => 'updated@company.com',
        ]);
    }

    #[Test]
    public function user_cannot_update_other_users_company()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->put("/dashboard/companies/{$company->id}", [
            'name' => 'Hacked Company',
        ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function user_can_delete_their_own_company()
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

        $response = $this->actingAs($user)->delete("/dashboard/companies/{$company->id}/destroy");

        $response->assertRedirect('/dashboard/companies');
        $response->assertSessionHas('alert.type', 'success');

        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }

    #[Test]
    public function user_cannot_delete_other_users_company()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->delete("/dashboard/companies/{$company->id}/destroy");

        $response->assertStatus(403);
        $this->assertDatabaseHas('companies', ['id' => $company->id]);
    }

    #[Test]
    public function unauthenticated_users_cannot_access_companies()
    {
        $response = $this->get('/dashboard/companies');

        $response->assertRedirect('/auth/login');
    }
}

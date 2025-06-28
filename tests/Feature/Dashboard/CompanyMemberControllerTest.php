<?php

namespace Tests\Feature\Dashboard;

use App\Models\Company;
use App\Models\CompanyMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyMemberControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private User $admin;
    private User $member;
    private User $outsider;
    private Company $company;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create();
        $this->admin = User::factory()->create();
        $this->member = User::factory()->create();
        $this->outsider = User::factory()->create();

        $this->company = Company::factory()->create(['user_id' => $this->owner->id]);

        // Create company members
        CompanyMember::create([
            'company_id' => $this->company->id,
            'user_id' => $this->owner->id,
            'role' => 'owner',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        CompanyMember::create([
            'company_id' => $this->company->id,
            'user_id' => $this->admin->id,
            'role' => 'admin',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        CompanyMember::create([
            'company_id' => $this->company->id,
            'user_id' => $this->member->id,
            'role' => 'member',
            'status' => 'active',
            'joined_at' => now(),
        ]);
    }

    public function test_owner_can_view_members_index(): void
    {
        $response = $this->actingAs($this->owner)->get(route('dashboard.companies.members.index', $this->company));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.company.members.index');
        $response->assertViewHas('company', $this->company);
        $response->assertViewHas('members');
    }

    public function test_admin_can_view_members_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard.companies.members.index', $this->company));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.company.members.index');
    }

    public function test_regular_member_cannot_view_members_index(): void
    {
        $response = $this->actingAs($this->member)->get(route('dashboard.companies.members.index', $this->company));

        $response->assertStatus(403);
    }

    public function test_outsider_cannot_view_members_index(): void
    {
        $response = $this->actingAs($this->outsider)->get(route('dashboard.companies.members.index', $this->company));

        $response->assertStatus(403);
    }

    public function test_admin_can_invite_user(): void
    {
        $newUser = User::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('dashboard.companies.members.invite', $this->company), [
            'email' => $newUser->email,
            'role' => 'member',
        ]);

        $this->assertDatabaseHas('company_members', [
            'company_id' => $this->company->id,
            'user_id' => $newUser->id,
            'role' => 'member',
            'status' => 'invited',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('alert.type', 'success');
    }

    public function test_invite_requires_valid_email_format(): void
    {
        $response = $this->actingAs($this->admin)->post(route('dashboard.companies.members.invite', $this->company), [
            'email' => 'invalid-email-format',
            'role' => 'member',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_invite_requires_valid_role(): void
    {
        $newUser = User::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('dashboard.companies.members.invite', $this->company), [
            'email' => $newUser->email,
            'role' => 'invalid-role',
        ]);

        $response->assertSessionHasErrors('role');
    }

    public function test_cannot_invite_existing_member(): void
    {
        $response = $this->actingAs($this->admin)->post(route('dashboard.companies.members.invite', $this->company), [
            'email' => $this->member->email,
            'role' => 'admin',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_accept_invite(): void
    {
        $invitedUser = User::factory()->create();
        CompanyMember::create([
            'company_id' => $this->company->id,
            'user_id' => $invitedUser->id,
            'role' => 'member',
            'status' => 'invited',
        ]);

        $response = $this->actingAs($invitedUser)->post(route('dashboard.companies.members.accept', $this->company));

        $this->assertDatabaseHas('company_members', [
            'company_id' => $this->company->id,
            'user_id' => $invitedUser->id,
            'status' => 'active',
        ]);

        $response->assertRedirect(route('dashboard.companies.show', $this->company));
        $response->assertSessionHas('alert.type', 'success');
    }

    public function test_admin_can_update_member_role(): void
    {
        // Get the actual CompanyMember record for this member
        $memberRecord = CompanyMember::where('company_id', $this->company->id)
            ->where('user_id', $this->member->id)
            ->where('role', 'member')
            ->first();

        $this->assertNotNull($memberRecord, 'Member record should exist');

        $response = $this->actingAs($this->admin)->patch(
            route('dashboard.companies.members.update-role', [$this->company, $memberRecord]),
            ['role' => 'admin']
        );

        $this->assertDatabaseHas('company_members', [
            'id' => $memberRecord->id,
            'user_id' => $this->member->id,
            'role' => 'admin',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('alert.type', 'success');
    }

    public function test_cannot_update_owner_role(): void
    {
        $ownerMember = CompanyMember::where('user_id', $this->owner->id)->first();

        $response = $this->actingAs($this->admin)->patch(
            route('dashboard.companies.members.update-role', [$this->company, $ownerMember]),
            ['role' => 'admin']
        );

        $response->assertSessionHasErrors('role');
    }

    public function test_admin_can_remove_member(): void
    {
        $memberToRemove = CompanyMember::where('user_id', $this->member->id)->first();

        $response = $this->actingAs($this->admin)->delete(
            route('dashboard.companies.members.remove', [$this->company, $memberToRemove])
        );

        $this->assertDatabaseMissing('company_members', [
            'user_id' => $this->member->id,
            'company_id' => $this->company->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('alert.type', 'success');
    }

    public function test_cannot_remove_owner(): void
    {
        $ownerMember = CompanyMember::where('user_id', $this->owner->id)->first();

        $response = $this->actingAs($this->admin)->delete(
            route('dashboard.companies.members.remove', [$this->company, $ownerMember])
        );

        $response->assertSessionHasErrors('error');
    }

    public function test_member_can_leave_company(): void
    {
        $response = $this->actingAs($this->member)->delete(route('dashboard.companies.members.leave', $this->company));

        $this->assertDatabaseMissing('company_members', [
            'user_id' => $this->member->id,
            'company_id' => $this->company->id,
        ]);

        $response->assertRedirect(route('dashboard.companies.index'));
        $response->assertSessionHas('alert.type', 'success');
    }

    public function test_owner_cannot_leave_company(): void
    {
        $response = $this->actingAs($this->owner)->delete(route('dashboard.companies.members.leave', $this->company));

        $response->assertSessionHasErrors('error');
    }

    public function test_current_user_company_members_view(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $user->id]);
        
        // Create the CompanyMember relationship
        CompanyMember::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'role' => 'owner',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard/company/members');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.company.members.index');
    }

    public function test_current_user_company_members_redirects_without_company(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/company/members');

        $response->assertRedirect(route('dashboard.companies.create'));
        $response->assertSessionHas('alert.type', 'error');
    }

    public function test_current_user_company_invite(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $user->id]);
        $newUser = User::factory()->create();
        
        // Create the CompanyMember relationship for the owner
        CompanyMember::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'role' => 'owner',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        $response = $this->actingAs($user)->post('/dashboard/company/members/invite', [
            'email' => $newUser->email,
            'role' => 'member',
        ]);

        $this->assertDatabaseHas('company_members', [
            'company_id' => $company->id,
            'user_id' => $newUser->id,
            'status' => 'invited',
        ]);

        $response->assertRedirect();
    }

    public function test_current_user_company_invite_redirects_without_company(): void
    {
        $user = User::factory()->create();
        $newUser = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/company/members/invite', [
            'email' => $newUser->email,
            'role' => 'member',
        ]);

        $response->assertRedirect(route('dashboard.companies.create'));
    }

    public function test_current_user_company_update_role(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $user->id]);
        $targetUser = User::factory()->create();
        
        // Create the CompanyMember relationship for the owner
        CompanyMember::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'role' => 'owner',
            'status' => 'active',
            'joined_at' => now(),
        ]);
        
        $member = CompanyMember::create([
            'company_id' => $company->id,
            'user_id' => $targetUser->id,
            'role' => 'member',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->patch("/dashboard/company/members/{$member->id}/role", [
            'role' => 'admin',
        ]);

        $this->assertDatabaseHas('company_members', [
            'id' => $member->id,
            'role' => 'admin',
        ]);

        $response->assertRedirect();
    }

    public function test_current_user_company_remove_member(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $user->id]);
        $targetUser = User::factory()->create();
        
        // Create the CompanyMember relationship for the owner
        CompanyMember::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'role' => 'owner',
            'status' => 'active',
            'joined_at' => now(),
        ]);
        
        $member = CompanyMember::create([
            'company_id' => $company->id,
            'user_id' => $targetUser->id,
            'role' => 'member',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->delete("/dashboard/company/members/{$member->id}");

        $this->assertDatabaseMissing('company_members', [
            'id' => $member->id,
        ]);

        $response->assertRedirect();
    }

    public function test_cannot_invite_user_with_pending_invite(): void
    {
        $newUser = User::factory()->create();
        
        // Create a pending invite
        CompanyMember::create([
            'company_id' => $this->company->id,
            'user_id' => $newUser->id,
            'role' => 'member',
            'status' => 'invited',
        ]);

        $response = $this->actingAs($this->admin)->post(route('dashboard.companies.members.invite', $this->company), [
            'email' => $newUser->email,
            'role' => 'member',
        ]);

        $response->assertSessionHasErrors('email');
    }
}

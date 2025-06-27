<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\CompanyMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyMemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_member_belongs_to_company(): void
    {
        $company = Company::factory()->create();
        $user = User::factory()->create();
        $member = CompanyMember::factory()->create([
            'company_id' => $company->id,
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(Company::class, $member->company);
        $this->assertEquals($company->id, $member->company->id);
    }

    public function test_company_member_belongs_to_user(): void
    {
        $company = Company::factory()->create();
        $user = User::factory()->create();
        $member = CompanyMember::factory()->create([
            'company_id' => $company->id,
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $member->user);
        $this->assertEquals($user->id, $member->user->id);
    }

    public function test_is_owner_returns_true_for_owner_role(): void
    {
        $member = CompanyMember::factory()->create(['role' => 'owner']);

        $this->assertTrue($member->isOwner());
    }

    public function test_is_owner_returns_false_for_non_owner_roles(): void
    {
        $adminMember = CompanyMember::factory()->create(['role' => 'admin']);
        $regularMember = CompanyMember::factory()->create(['role' => 'member']);

        $this->assertFalse($adminMember->isOwner());
        $this->assertFalse($regularMember->isOwner());
    }

    public function test_is_admin_returns_true_for_owner_role(): void
    {
        $member = CompanyMember::factory()->create(['role' => 'owner']);

        $this->assertTrue($member->isAdmin());
    }

    public function test_is_admin_returns_true_for_admin_role(): void
    {
        $member = CompanyMember::factory()->create(['role' => 'admin']);

        $this->assertTrue($member->isAdmin());
    }

    public function test_is_admin_returns_false_for_member_role(): void
    {
        $member = CompanyMember::factory()->create(['role' => 'member']);

        $this->assertFalse($member->isAdmin());
    }

    public function test_is_active_returns_true_for_active_status(): void
    {
        $member = CompanyMember::factory()->create(['status' => 'active']);

        $this->assertTrue($member->isActive());
    }

    public function test_is_active_returns_false_for_non_active_status(): void
    {
        $invitedMember = CompanyMember::factory()->create(['status' => 'invited']);
        $inactiveMember = CompanyMember::factory()->create(['status' => 'inactive']);

        $this->assertFalse($invitedMember->isActive());
        $this->assertFalse($inactiveMember->isActive());
    }

    public function test_is_pending_returns_true_for_invited_status(): void
    {
        $member = CompanyMember::factory()->create(['status' => 'invited']);

        $this->assertTrue($member->isPending());
    }

    public function test_is_pending_returns_false_for_non_invited_status(): void
    {
        $activeMember = CompanyMember::factory()->create(['status' => 'active']);
        $inactiveMember = CompanyMember::factory()->create(['status' => 'inactive']);

        $this->assertFalse($activeMember->isPending());
        $this->assertFalse($inactiveMember->isPending());
    }

    public function test_joined_at_is_cast_to_datetime(): void
    {
        $member = CompanyMember::factory()->create([
            'joined_at' => '2023-01-01 12:00:00'
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $member->joined_at);
    }

    public function test_fillable_attributes(): void
    {
        $fillable = [
            'company_id',
            'user_id',
            'email',
            'role',
            'status',
            'joined_at',
        ];

        $member = new CompanyMember();

        $this->assertEquals($fillable, $member->getFillable());
    }

    public function test_can_create_company_member_with_all_fillable_attributes(): void
    {
        $company = Company::factory()->create();
        $user = User::factory()->create();
        $joinedAt = now();

        $member = CompanyMember::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'role' => 'admin',
            'status' => 'active',
            'joined_at' => $joinedAt,
        ]);

        $this->assertDatabaseHas('company_members', [
            'company_id' => $company->id,
            'user_id' => $user->id,
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->assertEquals($joinedAt->toDateTimeString(), $member->joined_at->toDateTimeString());
    }
}

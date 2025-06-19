<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_login_without_email_verification()
    {
        // Register a new user
        $response = $this->post('/auth/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/auth/login');

        // Try logging in before verifying email
        $loginResponse = $this->post('/auth/login', [
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertRedirect('/dashboard');

        // Check user created but not verified
        $user = User::where('email', 'testuser@example.com')->first();
        $this->assertNotNull($user);
        $this->assertFalse($user->hasVerifiedEmail());
    }

    public function test_user_can_login_after_email_verification()
    {
        $user = User::factory()->unverified()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user->markEmailAsVerified();

        $this->assertTrue($user->hasVerifiedEmail());

        $loginResponse = $this->post('/auth/login', [
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_verification_notice_page_is_accessible_to_unverified_users()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/auth/email/verify');

        $response->assertStatus(200);

        // Check the page contains texts that are actually shown
        $response->assertSee('Verify Email');
        $response->assertSee('Verify your email to continue');
        $response->assertSee('Before proceeding, please check your email for a verification link. If you did not receive the email, you can request another by clicking the button below.');
    }

    public function test_unverified_user_cannot_access_dashboard()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/auth/email/verify');
    }
}

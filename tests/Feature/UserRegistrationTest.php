<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_cannot_login_without_email_verification()
    {
        // Create user but do NOT verify email
        $user = User::factory()->create([
            'email_verified_at' => null,
            'password' => bcrypt('password123'),
        ]);

        // Attempt login
        $loginResponse = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $loginResponse->assertRedirect('auth/email/verify');

        // Assert user is created and not verified
        $this->assertNull($user->fresh()->email_verified_at);
    }

    /** @test */
    public function verification_notice_page_is_accessible_to_unverified_users()
    {
        $user = User::factory()->unverified()->create();

        // Visit the default email verification notice page
        $response = $this->actingAs($user)->get('auth/email/verify');

        $response->assertStatus(200);

        // Check the important strings are present in the page (loosen assertion a bit)
        $response->assertSee('Before proceeding, please check your email for a verification link');
        $response->assertSee('If you did not receive the email');
        $response->assertSee('request another');
    }

    /** @test */
    public function unverified_user_cannot_access_dashboard()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        // Should redirect to email verification notice page
        $response->assertRedirect('auth/email/verify');
    }

    /** @test */
    public function user_can_login_after_email_verification()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
        ]);

        $loginResponse = $this->post('auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        // Verified user should be redirected to dashboard
        $loginResponse->assertRedirect('/dashboard');
    }
}

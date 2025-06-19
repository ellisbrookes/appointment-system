<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
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

        $response->assertRedirect('/auth/verify'); // Redirected to verify page

        // Try logging in before verifying email
        $loginResponse = $this->post('/auth/login', [
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);

        // Should redirect back with an error about verification
        $loginResponse->assertRedirect('/auth/login');
        $loginResponse->assertSessionHasErrors('email');

        // Check user is created and not verified yet
        $user = User::where('email', 'testuser@example.com')->first();
        $this->assertNotNull($user);
        $this->assertFalse($user->hasVerifiedEmail());
    }

    public function test_user_can_login_after_email_verification()
    {
        // Create user but unverified
        $user = User::factory()->unverified()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Manually mark email as verified
        $user->markEmailAsVerified();

        $this->assertTrue($user->hasVerifiedEmail());

        // Attempt login now
        $loginResponse = $this->post('/auth/login', [
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertRedirect('/dashoard');
        $this->assertAuthenticatedAs($user);
    }
}

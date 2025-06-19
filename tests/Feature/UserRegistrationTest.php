<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_register_successfully()
    {
        $response = $this->post('auth/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Assert the user was redirected after registration (usually home page)
        $response->assertRedirect('auth/login'); // or wherever your app redirects after register

        // Assert the user exists in the database
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
            'name' => 'Test User',
        ]);

        // Assert the user is authenticated
        $this->assertAuthenticated();
    }

    /** @test */
    public function registration_requires_a_valid_email()
    {
        $response = $this->post('auth/register', [
            'name' => 'Test User',
            'email' => 'not-an-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }
}

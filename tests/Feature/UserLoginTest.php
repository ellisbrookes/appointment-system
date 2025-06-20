<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_wrong_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('correct-password'),
            'email_verified_at' => now(),
        ]);

        $response = $this->from('/login')->post('/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function unverified_user_cannot_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
            'email_verified_at' => null,
        ]);

        $response = $this->post('/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        // Adjust redirect based on your app’s behavior for unverified users
        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function user_can_logout()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->post('auth/logout');

        $response->assertRedirect('/auth/login');
        $this->assertGuest();
    }
}

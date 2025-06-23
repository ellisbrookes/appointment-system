<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function password_reset_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->get('/auth/reset-password/'.$token.'?email='.$user->email);

        $response->assertStatus(200);
        $response->assertViewIs('auth.reset-password');
    }

    #[Test]
    public function password_can_be_reset_with_valid_token()
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->post('/auth/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('alert.type', 'success');
        $response->assertSessionHas('alert.message');

        $this->assertTrue(Hash::check('password123', $user->fresh()->password));
    }

    #[Test]
    public function password_reset_fails_with_invalid_token()
    {
        $user = User::factory()->create();

        $response = $this->post('/auth/reset-password', [
            'token' => 'invalid-token',
            'email' => $user->email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
    }

    #[Test]
    public function password_reset_requires_valid_email()
    {
        $response = $this->post('/auth/reset-password', [
            'token' => 'some-token',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    #[Test]
    public function password_reset_requires_password_confirmation()
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->post('/auth/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password123',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    #[Test]
    public function password_reset_requires_minimum_password_length()
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->post('/auth/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    #[Test]
    public function password_reset_requires_all_fields()
    {
        $response = $this->post('/auth/reset-password', []);

        $response->assertSessionHasErrors(['token', 'email', 'password']);
    }
}

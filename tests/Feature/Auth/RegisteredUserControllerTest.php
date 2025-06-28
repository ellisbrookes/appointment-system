<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Tests\TestCase;

class RegisteredUserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/auth/register');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    public function test_new_users_can_register(): void
    {
        Event::fake();

        $response = $this->post('/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'telephone_number' => '01234567890',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'telephone_number' => '01234567890',
        ]);

        Event::assertDispatched(Registered::class);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('alert.type', 'success');
        $response->assertSessionHas('alert.message', 'Account successfully created, please verify your email');
    }

    public function test_registration_requires_name(): void
    {
        $response = $this
            ->post('/auth/register', [
                'email' => 'test@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_registration_requires_email(): void
    {
        $response = $this
            ->post('/auth/register', [
                'name' => 'Test User',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_requires_valid_email(): void
    {
        $response = $this
            ->post('/auth/register', [
                'name' => 'Test User',
                'email' => 'invalid-email',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_requires_unique_email(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this
            ->post('/auth/register', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_requires_password(): void
    {
        $response = $this
            ->post('/auth/register', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_registration_requires_password_confirmation(): void
    {
        $response = $this
            ->post('/auth/register', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password123',
                'password_confirmation' => 'different-password',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_registration_with_null_telephone_number(): void
    {
        Event::fake();

        $response = $this
            ->post('/auth/register', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'telephone_number' => null,
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'telephone_number' => null,
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_registration_validates_name_max_length(): void
    {
        $response = $this
            ->post('/auth/register', [
                'name' => str_repeat('a', 256), // 256 characters, exceeds max of 255
                'email' => 'test@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_registration_validates_email_max_length(): void
    {
        $longEmail = str_repeat('a', 244) . '@example.com'; // 244 + 12 = 256 characters, exceeds max of 255

        $response = $this
            ->post('/auth/register', [
                'name' => 'Test User',
                'email' => $longEmail,
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_converts_email_to_lowercase(): void
    {
        Event::fake();

        $response = $this
            ->post('/auth/register', [
                'name' => 'Test User',
                'email' => 'TEST@EXAMPLE.COM',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect(route('login'));
    }
}

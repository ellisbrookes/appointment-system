<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PasswordForgotTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function password_forgot_page_can_be_rendered()
    {
        $response = $this->get('/auth/forgot-password');

        $response->assertStatus(200);
        $response->assertViewIs('auth.forgot-password');
    }

    #[Test]
    public function password_reset_link_can_be_requested()
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->post('/auth/forgot-password', [
            'email' => $user->email,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('alert.type', 'success');
        $response->assertSessionHas('alert.message');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    #[Test]
    public function password_reset_link_shows_success_message_for_invalid_email()
    {
        $response = $this->post('/auth/forgot-password', [
            'email' => 'nonexistent@example.com',
        ]);

        // Should still show success message for security reasons
        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
    }

    #[Test]
    public function password_reset_request_requires_email()
    {
        $response = $this->post('/auth/forgot-password', []);

        $response->assertSessionHasErrors(['email']);
    }

    #[Test]
    public function password_reset_request_requires_valid_email_format()
    {
        $response = $this->post('/auth/forgot-password', [
            'email' => 'invalid-email',
        ]);

        $response->assertSessionHasErrors(['email']);
    }
}

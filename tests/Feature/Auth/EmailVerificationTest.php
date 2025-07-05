<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function email_verification_notice_can_be_rendered()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/auth/email/verify');

        $response->assertStatus(200);
        $response->assertViewIs('auth.verify');
    }

    #[Test]
    public function email_can_be_verified()
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('alert.type', 'success');
    }

    #[Test]
    public function email_verification_redirects_if_already_verified()
    {
        $user = User::factory()->create(); // Already verified

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('alert.type', 'info');
    }

    #[Test]
    public function email_verification_can_be_resent()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)
            
            ->post('/auth/email/verification-notification');

        $response->assertRedirect();
        $response->assertSessionHas('alert.type', 'success');
        $response->assertSessionHas('alert.message');

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    #[Test]
    public function email_verification_resend_redirects_if_already_verified()
    {
        $user = User::factory()->create(); // Already verified

        $response = $this->actingAs($user)
            
            ->post('/auth/email/verification-notification');

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('alert.type', 'info');
    }

    #[Test]
    public function email_verification_notice_redirects_verified_users()
    {
        $user = User::factory()->create(); // Already verified

        $response = $this->actingAs($user)->get('/auth/email/verify');

        $response->assertRedirect(route('dashboard'));
    }

    #[Test]
    public function unauthenticated_users_cannot_access_verification_notice()
    {
        $response = $this->get('/auth/email/verify');

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function unauthenticated_users_cannot_resend_verification()
    {
        $response = $this
            ->post('/auth/email/verification-notification');

        $response->assertRedirect(route('login'));
    }
}

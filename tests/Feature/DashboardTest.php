<?php

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create([
            'settings' => ['onboarding_completed' => true]
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.index');
    }

    #[Test]
    public function dashboard_shows_correct_appointment_counts()
    {
        $user = User::factory()->create([
            'settings' => ['onboarding_completed' => true]
        ]);
        
        // Create appointments with different statuses
        Appointment::factory()->create([
            'user_id' => $user->id,
            'status' => 'open'
        ]);
        Appointment::factory()->create([
            'user_id' => $user->id,
            'status' => 'open'
        ]);
        Appointment::factory()->create([
            'user_id' => $user->id,
            'status' => 'cancelled'
        ]);
        Appointment::factory()->create([
            'user_id' => $user->id,
            'status' => 'closed'
        ]);

        // Create appointment for different user (should not be counted)
        $otherUser = User::factory()->create();
        Appointment::factory()->create([
            'user_id' => $otherUser->id,
            'status' => 'open'
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('appointmentsCount', 2);
        $response->assertViewHas('cancelledCount', 1);
        $response->assertViewHas('closedCount', 1);
    }

    #[Test]
    public function dashboard_shows_recent_appointments()
    {
        $user = User::factory()->create([
            'settings' => ['onboarding_completed' => true]
        ]);
        
        // Create 6 appointments (5 should be shown)
        for ($i = 0; $i < 6; $i++) {
            Appointment::factory()->create([
                'user_id' => $user->id,
                'status' => 'open'
            ]);
        }

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('recentAppointments');
        
        $recentAppointments = $response->viewData('recentAppointments');
        $this->assertCount(5, $recentAppointments);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/auth/login');
    }
}

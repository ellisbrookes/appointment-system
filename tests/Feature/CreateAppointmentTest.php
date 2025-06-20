<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CreateAppointmentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authenticated_user_can_view_appointment_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/appointments');

        $response->assertStatus(200);
    }

    #[Test]
    public function authenticated_user_can_create_appointment_through_multi_step_form()
    {
        $user = User::factory()->create();

        // Simulate step 1: user selects service and date
        Session::start();
        $this->actingAs($user)->withSession([
            'appointment.step1' => [
                'service_id' => 1,
                'date' => '2025-07-01',
            ]
        ]);

        // Step 2: user submits time and additional info
        $response = $this->post('/dashboard/appointments', [
            'time' => '14:30',
            'notes' => 'Please be on time',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect('/dashboard/appointments/confirmation');

        $this->assertDatabaseHas('appointements', [
            'user_id' => $user->id,
            'service_id' => 1,
            'date' => '2025-07-01',
            'time' => '14:30',
            'notes' => 'Please be on time',
        ]);
    }
}

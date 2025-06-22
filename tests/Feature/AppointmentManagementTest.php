<?php

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AppointmentManagementTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_view_appointments_list()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/appointments');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.appointments.index');
    }

    #[Test]
    public function user_can_filter_appointments_by_status()
    {
        $user = User::factory()->create();
        
        // Create appointments with different statuses
        Appointment::factory()->create(['status' => 'open']);
        Appointment::factory()->create(['status' => 'cancelled']);
        Appointment::factory()->create(['status' => 'closed']);

        $response = $this->actingAs($user)->get('/dashboard/appointments?status=open');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.appointments.index');
    }

    #[Test]
    public function user_can_view_edit_appointment_form()
    {
        $user = User::factory()->create();
        $appointment = Appointment::factory()->create();

        $response = $this->actingAs($user)->get("/dashboard/appointments/{$appointment->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.appointments.edit');
        $response->assertViewHas('appointment', $appointment);
    }

    #[Test]
    public function user_can_update_appointment()
    {
        $user = User::factory()->create();
        $appointment = Appointment::factory()->create();
        
        $updateData = [
            'service' => 'Updated Service',
            'date' => '2025-07-01',
            'timeslot' => '15:00',
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)->put("/dashboard/appointments/{$appointment->id}", $updateData);

        $response->assertRedirect('/dashboard/appointments');
        $response->assertSessionHas('alert.type', 'success');
        
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'service' => 'Updated Service',
            'date' => '2025-07-01',
            'timeslot' => '15:00',
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function user_cannot_update_appointment_with_invalid_data()
    {
        $user = User::factory()->create();
        $appointment = Appointment::factory()->create();
        
        $updateData = [
            'service' => '', // Required field left empty
            'date' => 'invalid-date',
            'timeslot' => '',
            'user_id' => 999, // Non-existent user
        ];

        $response = $this->actingAs($user)->put("/dashboard/appointments/{$appointment->id}", $updateData);

        $response->assertSessionHasErrors(['service', 'timeslot', 'user_id']);
    }

    #[Test]
    public function user_can_cancel_appointment()
    {
        $user = User::factory()->create();
        $appointment = Appointment::factory()->create(['status' => 'open']);

        $response = $this->actingAs($user)->delete("/dashboard/appointments/{$appointment->id}/destroy");

        $response->assertRedirect('/dashboard/appointments');
        $response->assertSessionHas('alert.type', 'success');
        
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'cancelled',
        ]);
    }

    #[Test]
    public function user_can_access_create_step_one()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/appointments/create-step-one');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.appointments.create-step-one');
    }

    #[Test]
    public function user_can_submit_create_step_one()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/appointments/create-step-one', [
            'service' => 'Test Service'
        ]);

        $response->assertRedirect('/dashboard/appointments/create-step-two');
        $response->assertSessionHas('appointment.service', 'Test Service');
    }

    #[Test]
    public function user_can_access_create_step_two()
    {
        $user = User::factory()->create();
        
        $sessionData = [
            'appointment' => ['service' => 'Test Service']
        ];

        $response = $this->actingAs($user)
                         ->withSession($sessionData)
                         ->get('/dashboard/appointments/create-step-two');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.appointments.create-step-two');
    }

    #[Test]
    public function user_can_submit_create_step_two()
    {
        $user = User::factory()->create();
        
        $sessionData = [
            'appointment' => ['service' => 'Test Service']
        ];

        $response = $this->actingAs($user)
                         ->withSession($sessionData)
                         ->post('/dashboard/appointments/create-step-two', [
                             'date' => '2025-07-01',
                             'timeslot' => '14:00'
                         ]);

        $response->assertRedirect('/dashboard/appointments/create-step-three');
        $response->assertSessionHas('appointment.service', 'Test Service');
        $response->assertSessionHas('appointment.date', '2025-07-01');
        $response->assertSessionHas('appointment.timeslot', '14:00');
    }

    #[Test]
    public function user_cannot_submit_step_one_with_empty_service()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/appointments/create-step-one', [
            'service' => ''
        ]);

        $response->assertSessionHasErrors(['service']);
    }

    #[Test]
    public function user_cannot_submit_step_one_without_service()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/appointments/create-step-one', []);

        $response->assertSessionHasErrors(['service']);
    }

    #[Test]
    public function user_cannot_submit_step_two_with_invalid_date()
    {
        $user = User::factory()->create();
        
        $sessionData = [
            'appointment' => ['service' => 'Test Service']
        ];

        $response = $this->actingAs($user)
                         ->withSession($sessionData)
                         ->post('/dashboard/appointments/create-step-two', [
                             'date' => 'invalid-date',
                             'timeslot' => '14:00'
                         ]);

        $response->assertSessionHasErrors(['date']);
    }

    #[Test]
    public function user_cannot_submit_step_two_without_required_fields()
    {
        $user = User::factory()->create();
        
        $sessionData = [
            'appointment' => ['service' => 'Test Service']
        ];

        $response = $this->actingAs($user)
                         ->withSession($sessionData)
                         ->post('/dashboard/appointments/create-step-two', []);

        $response->assertSessionHasErrors(['date', 'timeslot']);
    }

    #[Test]
    public function user_cannot_submit_step_two_with_empty_timeslot()
    {
        $user = User::factory()->create();
        
        $sessionData = [
            'appointment' => ['service' => 'Test Service']
        ];

        $response = $this->actingAs($user)
                         ->withSession($sessionData)
                         ->post('/dashboard/appointments/create-step-two', [
                             'date' => '2025-07-01',
                             'timeslot' => ''
                         ]);

        $response->assertSessionHasErrors(['timeslot']);
    }

    #[Test]
    public function step_two_merges_with_existing_session_data()
    {
        $user = User::factory()->create();
        
        $sessionData = [
            'appointment' => [
                'service' => 'Original Service',
                'extra_field' => 'should be preserved'
            ]
        ];

        $response = $this->actingAs($user)
                         ->withSession($sessionData)
                         ->post('/dashboard/appointments/create-step-two', [
                             'date' => '2025-07-01',
                             'timeslot' => '14:00'
                         ]);

        $response->assertRedirect('/dashboard/appointments/create-step-three');
        $response->assertSessionHas('appointment.service', 'Original Service');
        $response->assertSessionHas('appointment.extra_field', 'should be preserved');
        $response->assertSessionHas('appointment.date', '2025-07-01');
        $response->assertSessionHas('appointment.timeslot', '14:00');
    }

    #[Test]
    public function step_one_merges_with_existing_session_data()
    {
        $user = User::factory()->create();
        
        $sessionData = [
            'appointment' => [
                'existing_data' => 'should be preserved'
            ]
        ];

        $response = $this->actingAs($user)
                         ->withSession($sessionData)
                         ->post('/dashboard/appointments/create-step-one', [
                             'service' => 'New Service'
                         ]);

        $response->assertRedirect('/dashboard/appointments/create-step-two');
        $response->assertSessionHas('appointment.existing_data', 'should be preserved');
        $response->assertSessionHas('appointment.service', 'New Service');
    }

    #[Test]
    public function step_two_displays_calendar_with_current_date_parameters()
    {
        $user = User::factory()->create();
        
        $sessionData = [
            'appointment' => ['service' => 'Test Service']
        ];

        $response = $this->actingAs($user)
                         ->withSession($sessionData)
                         ->get('/dashboard/appointments/create-step-two?year=2025&month=7');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.appointments.create-step-two');
        $response->assertViewHas('year', 2025);
        $response->assertViewHas('month', 7);
    }

    #[Test]
    public function step_two_uses_default_year_and_month_when_not_provided()
    {
        $user = User::factory()->create();
        
        $sessionData = [
            'appointment' => ['service' => 'Test Service']
        ];

        $response = $this->actingAs($user)
                         ->withSession($sessionData)
                         ->get('/dashboard/appointments/create-step-two');

        $response->assertStatus(200);
        $response->assertViewHas('year');
        $response->assertViewHas('month');
        $response->assertViewHas('daysInMonth');
        $response->assertViewHas('startDayOfWeek');
        $response->assertViewHas('timeslots');
    }

    #[Test]
    public function step_one_handles_empty_session_gracefully()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/appointments/create-step-one');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.appointments.create-step-one');
        $response->assertViewHas('appointment');
    }

    #[Test]
    public function step_two_handles_empty_session_gracefully()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/appointments/create-step-two');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.appointments.create-step-two');
    }

    #[Test]
    public function appointment_create_without_session_data_fails_gracefully()
    {
        $user = User::factory()->create();

        // Try to post to step 3 without session data
        $response = $this->actingAs($user)
                         ->post('/dashboard/appointments/create-step-three', [
                             'user_id' => $user->id,
                         ]);

        // Should redirect back to appointments (session data missing means no appointment data to save)
        $response->assertRedirect('/dashboard/appointments');
        $response->assertSessionHas('alert.type', 'success');
    }
}

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
}

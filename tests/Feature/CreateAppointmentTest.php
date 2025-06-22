<?php 
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CreateAppointmentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_visit_appointment_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/appointments');

        $response->assertStatus(200);
    }

    #[Test]
    public function authenticated_user_can_create_appointment_through_multi_step_form()
    {
        $user = User::factory()->create();

        // Simulate the session data that would be stored in steps 1 and 2
        $sessionData = [
            'appointment' => [
                'service' => 'Test Service',
                'date' => '2025-06-25',
                'timeslot' => '14:00',
            ],
        ];

        // Visit step 3 (confirmation) page (GET)
        $response = $this->actingAs($user)
                         ->withSession($sessionData)
                         ->get('/dashboard/appointments/create-step-three');
        $response->assertStatus(200);

        // Post to confirm step 3 (final submission)
        $response = $this->actingAs($user)
                         ->withSession($sessionData)
                         ->post('/dashboard/appointments/create-step-three', [
                             'user_id' => $user->id,
                         ]);

        // Assert redirect after successful creation
        $response->assertRedirect('/dashboard/appointments');

        // Assert the appointment record is saved with the authenticated user's ID and correct data
        $this->assertDatabaseHas('appointments', [
            'user_id' => $user->id,
            'service' => 'Test Service',
            'date' => '2025-06-25',
            'timeslot' => '14:00',
        ]);
    }
}

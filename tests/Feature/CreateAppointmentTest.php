<?php

namespace Tests\Feature;

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
}

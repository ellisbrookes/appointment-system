<?php

namespace Tests\Feature\Console;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentReminder;
use Carbon\Carbon;

class SendAppointmentRemindersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Freeze time to make tests predictable
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 12, 0, 0));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    /** @test */
    public function it_sends_reminders_for_appointments_tomorrow()
    {
        Mail::fake();

        // Create company and user
        $company = Company::factory()->create();
        $user = User::factory()->create(['company_id' => $company->id]);

        // Create appointment for tomorrow
        $appointment = Appointment::factory()->create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'timeslot' => '10:00',
            'status' => 'confirmed',
        ]);

        $this->artisan('send:appointment-reminders')
            ->expectsOutput('Sending appointment reminders...')
            ->expectsOutput('Reminders sent successfully!')
            ->assertExitCode(0);

        Mail::assertSent(AppointmentReminder::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /** @test */
    public function it_does_not_send_reminders_for_appointments_not_tomorrow()
    {
        Mail::fake();

        $company = Company::factory()->create();
        $user = User::factory()->create(['company_id' => $company->id]);

        // Create appointments for today and day after tomorrow
        Appointment::factory()->create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'timeslot' => '10:00',
            'status' => 'confirmed',
        ]);

        Appointment::factory()->create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'date' => Carbon::tomorrow()->addDay()->format('Y-m-d'),
            'timeslot' => '14:00',
            'status' => 'confirmed',
        ]);

        $this->artisan('send:appointment-reminders')
            ->expectsOutput('Sending appointment reminders...')
            ->expectsOutput('Reminders sent successfully!')
            ->assertExitCode(0);

        Mail::assertNotSent(AppointmentReminder::class);
    }

    /** @test */
    public function it_does_not_send_reminders_for_cancelled_appointments()
    {
        Mail::fake();

        $company = Company::factory()->create();
        $user = User::factory()->create(['company_id' => $company->id]);

        // Create cancelled appointment for tomorrow
        Appointment::factory()->create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'timeslot' => '10:00',
            'status' => 'cancelled',
        ]);

        $this->artisan('send:appointment-reminders')
            ->expectsOutput('Sending appointment reminders...')
            ->expectsOutput('Reminders sent successfully!')
            ->assertExitCode(0);

        Mail::assertNotSent(AppointmentReminder::class);
    }

    /** @test */
    public function it_does_not_send_reminders_for_pending_appointments()
    {
        Mail::fake();

        $company = Company::factory()->create();
        $user = User::factory()->create(['company_id' => $company->id]);

        // Create pending appointment for tomorrow
        Appointment::factory()->create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'timeslot' => '10:00',
            'status' => 'pending',
        ]);

        $this->artisan('send:appointment-reminders')
            ->expectsOutput('Sending appointment reminders...')
            ->expectsOutput('Reminders sent successfully!')
            ->assertExitCode(0);

        Mail::assertNotSent(AppointmentReminder::class);
    }

    /** @test */
    public function it_runs_in_dry_run_mode_without_sending_emails()
    {
        Mail::fake();

        $company = Company::factory()->create();
        $user = User::factory()->create(['company_id' => $company->id]);

        Appointment::factory()->create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'timeslot' => '10:00',
            'status' => 'confirmed',
        ]);

        $this->artisan('send:appointment-reminders --dry-run')
            ->expectsOutput('Dry run mode enabled. No emails will be sent.')
            ->expectsOutput('Sending appointment reminders...')
            ->expectsOutput('Would send reminder to: ' . $user->email)
            ->expectsOutput('Dry run completed. No emails were sent.')
            ->assertExitCode(0);

        Mail::assertNotSent(AppointmentReminder::class);
    }

    /** @test */
    public function it_sends_multiple_reminders_for_multiple_appointments()
    {
        Mail::fake();

        $company = Company::factory()->create();
        $user1 = User::factory()->create(['company_id' => $company->id]);
        $user2 = User::factory()->create(['company_id' => $company->id]);

        // Create appointments for both users tomorrow
        Appointment::factory()->create([
            'company_id' => $company->id,
            'user_id' => $user1->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'timeslot' => '10:00',
            'status' => 'confirmed',
        ]);

        Appointment::factory()->create([
            'company_id' => $company->id,
            'user_id' => $user2->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'timeslot' => '14:00',
            'status' => 'confirmed',
        ]);

        $this->artisan('send:appointment-reminders')
            ->expectsOutput('Sending appointment reminders...')
            ->expectsOutput('Reminders sent successfully!')
            ->assertExitCode(0);

        Mail::assertSent(AppointmentReminder::class, 2);
        
        Mail::assertSent(AppointmentReminder::class, function ($mail) use ($user1) {
            return $mail->hasTo($user1->email);
        });
        
        Mail::assertSent(AppointmentReminder::class, function ($mail) use ($user2) {
            return $mail->hasTo($user2->email);
        });
    }

    /** @test */
    public function it_handles_appointments_without_users_gracefully()
    {
        Mail::fake();

        $company = Company::factory()->create();

        // Create appointment without user (orphaned appointment)
        Appointment::factory()->create([
            'company_id' => $company->id,
            'user_id' => null,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'timeslot' => '10:00',
            'status' => 'confirmed',
        ]);

        $this->artisan('send:appointment-reminders')
            ->expectsOutput('Sending appointment reminders...')
            ->expectsOutput('Reminders sent successfully!')
            ->assertExitCode(0);

        Mail::assertNotSent(AppointmentReminder::class);
    }

    /** @test */
    public function it_shows_count_of_reminders_sent_in_output()
    {
        Mail::fake();

        $company = Company::factory()->create();
        $user1 = User::factory()->create(['company_id' => $company->id]);
        $user2 = User::factory()->create(['company_id' => $company->id]);
        $user3 = User::factory()->create(['company_id' => $company->id]);

        // Create 3 appointments for tomorrow
        Appointment::factory()->create([
            'company_id' => $company->id,
            'user_id' => $user1->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'timeslot' => '10:00',
            'status' => 'confirmed',
        ]);

        Appointment::factory()->create([
            'company_id' => $company->id,
            'user_id' => $user2->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'timeslot' => '14:00',
            'status' => 'confirmed',
        ]);

        Appointment::factory()->create([
            'company_id' => $company->id,
            'user_id' => $user3->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'timeslot' => '16:00',
            'status' => 'confirmed',
        ]);

        $this->artisan('send:appointment-reminders')
            ->expectsOutput('Sending appointment reminders...')
            ->expectsOutput('Sent 3 reminder(s)')
            ->expectsOutput('Reminders sent successfully!')
            ->assertExitCode(0);

        Mail::assertSent(AppointmentReminder::class, 3);
    }

    /** @test */
    public function it_handles_no_appointments_to_remind()
    {
        Mail::fake();

        $this->artisan('send:appointment-reminders')
            ->expectsOutput('Sending appointment reminders...')
            ->expectsOutput('Sent 0 reminder(s)')
            ->expectsOutput('Reminders sent successfully!')
            ->assertExitCode(0);

        Mail::assertNotSent(AppointmentReminder::class);
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Mail\AppointmentReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders {--dry-run : Show what would be sent without actually sending}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send appointment reminder emails for appointments scheduled tomorrow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        $isDryRun = $this->option('dry-run');
        
        // Get all confirmed appointments for tomorrow
        $appointments = Appointment::with(['user', 'company'])
            ->where('date', $tomorrow)
            ->where('status', 'open') // Only send reminders for confirmed appointments
            ->get();
            
        if ($appointments->isEmpty()) {
            $this->info("No appointments found for tomorrow ({$tomorrow})");
            return 0;
        }
        
        $this->info("Found {$appointments->count()} appointment(s) for tomorrow ({$tomorrow})");
        
        $sentCount = 0;
        $errorCount = 0;
        
        foreach ($appointments as $appointment) {
            $emailAddress = null;
            $customerName = null;
            
            // Determine email address and customer name
            if ($appointment->user) {
                $emailAddress = $appointment->user->email;
                $customerName = $appointment->user->name;
            } elseif ($appointment->customer_email) {
                $emailAddress = $appointment->customer_email;
                $customerName = $appointment->customer_name;
            }
            
            if (!$emailAddress) {
                $this->warn("No email address found for appointment #{$appointment->id}");
                $errorCount++;
                continue;
            }
            
            if ($isDryRun) {
                $this->line("[DRY RUN] Would send reminder to: {$emailAddress} ({$customerName}) for {$appointment->service} at {$appointment->company->name}");
                $sentCount++;
            } else {
                try {
                    Mail::to($emailAddress)->send(new AppointmentReminder($appointment));
                    $this->line("✅ Sent reminder to: {$emailAddress} ({$customerName})");
                    $sentCount++;
                } catch (\Exception $e) {
                    $this->error("❌ Failed to send reminder to {$emailAddress}: {$e->getMessage()}");
                    $errorCount++;
                }
            }
        }
        
        if ($isDryRun) {
            $this->info("\n[DRY RUN] Would send {$sentCount} reminder(s), {$errorCount} error(s)");
        } else {
            $this->info("\nSent {$sentCount} reminder(s), {$errorCount} error(s)");
        }
        
        return 0;
    }
}

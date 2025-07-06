<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class TestEmail extends Command
{
    protected $signature = 'email:test {email? : The email address to send test email to}';
    protected $description = 'Send a test email to verify mail configuration';

    public function handle()
    {
        $email = $this->argument('email') ?? $this->ask('Enter email address to send test email to');
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address provided.');
            return 1;
        }

        $this->info('Sending test email...');
        $this->info('Current mailer: ' . config('mail.default'));
        $this->info('Mail host: ' . config('mail.mailers.' . config('mail.default') . '.host'));

        try {
            Mail::raw('This is a test email from your appointment system. If you received this, your email configuration is working correctly!', function (Message $message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - Appointment System')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

            $this->info("✅ Test email sent successfully to: {$email}");
            
            if (config('mail.default') === 'smtp' && str_contains(config('mail.mailers.smtp.host'), 'mailtrap')) {
                $this->info('💡 Check your Mailtrap inbox to see the email.');
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Failed to send test email: " . $e->getMessage());
            return 1;
        }
    }
}

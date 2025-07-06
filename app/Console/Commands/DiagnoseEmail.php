<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DiagnoseEmail extends Command
{
    protected $signature = 'email:diagnose';
    protected $description = 'Diagnose email configuration issues';

    public function handle()
    {
        $this->info('🔍 Email Configuration Diagnosis');
        $this->info('================================');
        
        // Check environment
        $this->info('Environment: ' . app()->environment());
        
        // Check mail configuration
        $mailer = config('mail.default');
        $this->info('Default Mailer: ' . $mailer);
        
        // Check specific mailer config
        $mailerConfig = config("mail.mailers.{$mailer}");
        if ($mailerConfig) {
            $this->info('Mailer Type: ' . ($mailerConfig['transport'] ?? 'unknown'));
            
            if ($mailerConfig['transport'] === 'sendgrid') {
                $apiKey = $mailerConfig['api_key'] ?? config('services.sendgrid.api_key');
                $this->info('SendGrid API Key: ' . ($apiKey ? '✅ Set (' . substr($apiKey, 0, 10) . '...)' : '❌ Missing'));
            } elseif ($mailerConfig['transport'] === 'smtp') {
                $this->info('SMTP Host: ' . ($mailerConfig['host'] ?? 'not set'));
                $this->info('SMTP Port: ' . ($mailerConfig['port'] ?? 'not set'));
                $this->info('SMTP Username: ' . ($mailerConfig['username'] ? '✅ Set' : '❌ Missing'));
            }
        }
        
        // Check from address
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');
        $this->info('From Address: ' . ($fromAddress ?? 'not set'));
        $this->info('From Name: ' . ($fromName ?? 'not set'));
        
        // Check if email verification is enabled
        $this->info('Email Verification: ' . (config('auth.verification.expire', 60) ? '✅ Enabled' : '❌ Disabled'));
        
        // Environment specific checks
        if (app()->environment('production')) {
            $this->warn('🚨 Production Environment - Make sure SendGrid is properly configured!');
            
            // Check for required production settings
            if ($mailer !== 'sendgrid') {
                $this->error('❌ Production should use SendGrid mailer, currently using: ' . $mailer);
            }
        } else {
            $this->info('🔧 Development Environment - Mailtrap configuration should be used');
        }
        
        return 0;
    }
}

<?php

namespace App\Providers;

use App\Mail\Transport\SendGridApiTransport;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;

class SendGridServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Mail::extend('sendgrid', function (array $config) {
            $apiKey = $config['api_key'] ?? config('services.sendgrid.api_key');
            
            // Don't register if no API key is provided (for development environments)
            if (!$apiKey) {
                throw new \Exception('SendGrid API key is required when using sendgrid mailer');
            }
            
            return new SendGridApiTransport($apiKey);
        });
    }
}

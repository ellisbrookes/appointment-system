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
            return new SendGridApiTransport(
                $config['api_key'] ?? config('services.sendgrid.api_key')
            );
        });
    }
}

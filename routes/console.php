<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\SendAppointmentReminders;

Artisan::command('inspire', function () {
  $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Register the SendAppointmentReminders command
Artisan::command('send:appointment-reminders', function () {
    $this->call(SendAppointmentReminders::class);
})->purpose('Send appointment reminders');

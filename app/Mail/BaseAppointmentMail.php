<?php

namespace App\Mail;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

abstract class BaseAppointmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(protected Appointment $appointment)
    {
    }

    protected function formatTimeslot(): string
    {
        $settings = $this->appointment->user->settings ?? [];
        $defaultSettings = [
            'time_format' => '24',
            'timezone' => 'UTC',
        ];
        $settings = array_merge($defaultSettings, $settings);

        $timezone = $settings['timezone'];
        $timeFormat = $settings['time_format'];

        $timeslot = Carbon::parse($this->appointment->timeslot)->setTimezone($timezone);

        return $timeFormat === '12'
            ? $timeslot->format('g:i A T')
            : $timeslot->format('H:i T');
    }

    public function attachments(): array
    {
        return [];
    }
}

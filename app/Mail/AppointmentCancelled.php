<?php

namespace App\Mail;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentCancelled extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected Appointment $appointment,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Appointment Cancelled',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Get user settings with defaults
        $settings = $this->appointment->user->settings ?? [];
        $defaultSettings = [
            'time_format' => '24',
            'timezone' => 'UTC'
        ];
        $settings = array_merge($defaultSettings, $settings);
        
        // Format time based on user's preference and timezone
        $timezone = $settings['timezone'];
        $timeFormat = $settings['time_format'];
        
        $timeslot = Carbon::parse($this->appointment->timeslot)->setTimezone($timezone);
        $formattedTimeslot = $timeFormat === '12' 
            ? $timeslot->format('g:i A T')
            : $timeslot->format('H:i T');

        return new Content(
            view: 'emails.appointment_cancelled',
            with: [
                'service' => $this->appointment->service,
                'date' => $this->appointment->date,
                'timeslot' => $this->appointment->timeslot,
                'formattedTimeslot' => $formattedTimeslot,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

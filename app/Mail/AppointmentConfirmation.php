<?php

namespace App\Mail;

use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AppointmentConfirmation extends BaseAppointmentMail
{

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Appointment Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment_confirmation',
            with: [
                'service' => $this->appointment->service,
                'date' => $this->appointment->date,
                'timeslot' => $this->appointment->timeslot,
                'formattedTimeslot' => $this->formatTimeslot(),
            ],
        );
    }
}

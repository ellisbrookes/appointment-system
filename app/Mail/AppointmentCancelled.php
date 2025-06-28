<?php

namespace App\Mail;

use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AppointmentCancelled extends BaseAppointmentMail
{

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
        return new Content(
            view: 'emails.appointment_cancelled',
            with: [
                'service' => $this->appointment->service,
                'date' => $this->appointment->date,
                'timeslot' => $this->appointment->timeslot,
                'formattedTimeslot' => $this->formatTimeslot(),
            ],
        );
    }
}

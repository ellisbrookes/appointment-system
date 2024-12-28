<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class AppointmentConfirmation extends Mailable
{
    public $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function build()
    {
        return $this->subject('Appointment Confirmation')
                    ->view('emails.appointment_confirmation')
                    ->with([
                        'service' => $this->appointment['service'],
                        'date' => $this->appointment['date'],
                        'timeslot' => $this->appointment['timeslot'],
                    ]);
    }
}

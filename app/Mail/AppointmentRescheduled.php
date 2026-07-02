<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Models\AppointmentReschedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentRescheduled extends Mailable
{
    use Queueable, SerializesModels;

    public Appointment $appointment;
    public AppointmentReschedule $reschedule;

    public function __construct(Appointment $appointment, AppointmentReschedule $reschedule)
    {
        $this->appointment = $appointment->load('counselor', 'counselType', 'counselee');
        $this->reschedule  = $reschedule;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Appointment Rescheduled – P2P Counselling',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.appointments.rescheduled');
    }

    public function attachments(): array { return []; }
}

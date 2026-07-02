<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public Appointment $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment->load('counselor', 'counselType', 'counselee');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Session Completed – P2P Counselling',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.appointments.completed');
    }

    public function attachments(): array { return []; }
}

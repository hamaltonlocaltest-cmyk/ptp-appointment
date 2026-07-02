<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentCancelled extends Mailable
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
            subject: 'Appointment Cancelled – P2P Counselling',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.appointments.cancelled');
    }

    public function attachments(): array { return []; }
}

<?php

namespace App\Mail;

use App\Models\AppointmentFeedback;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FeedbackReceived extends Mailable
{
    use Queueable, SerializesModels;

    public AppointmentFeedback $feedback;

    public function __construct(AppointmentFeedback $feedback)
    {
        $this->feedback = $feedback->load('appointment.counselType', 'counselee', 'counselor');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Session Feedback Received – P2P Counselling',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.feedback.received');
    }

    public function attachments(): array { return []; }
}

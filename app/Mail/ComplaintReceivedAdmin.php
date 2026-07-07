<?php

namespace App\Mail;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintReceivedAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public Complaint $complaint;

    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint->load('counselee', 'counselor', 'appointment.counselType');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Complaint Filed — ' . $this->complaint->reference_number,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.complaints.received-admin');
    }

    public function attachments(): array { return []; }
}

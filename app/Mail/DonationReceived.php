<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public Donation $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation->load('counselee');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thank You for Your Donation – P2P Counselling',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.donations.received');
    }

    public function attachments(): array { return []; }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CounseleeRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $email;
    public string $plainPassword;

    /**
     * Called as: new CounseleeRegistered($fullName, $email, $plainPassword)
     */
    public function __construct(string $name, string $email, string $plainPassword)
    {
        $this->name          = $name;
        $this->email         = $email;
        $this->plainPassword = $plainPassword;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to P2P Appointment – Your Registration is Confirmed',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.counselee.registered',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
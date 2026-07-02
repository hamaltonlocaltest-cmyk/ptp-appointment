<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CounselorRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public string $fullName;
    public string $email;
    public string $plainPassword;

    public function __construct(string $fullName, string $email, string $plainPassword)
    {
        $this->fullName      = $fullName;
        $this->email         = $email;
        $this->plainPassword = $plainPassword;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to P2P Appointment — Counselor Account Created',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.counselor.registered',
        );
    }
}

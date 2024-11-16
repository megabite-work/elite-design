<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMe extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public string $name, public string $phone)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'С сайта поступила новая заявка',
            to: [new Address('contact@elite-dd.com')]
            // to: [new Address('g.work.01.01.2000@gmail.com')]
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contact_me',
            with: [
                'name' => $this->name,
                'phone' => $this->phone
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

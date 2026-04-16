<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use App\Models\Setting;

class ContactMessageToRedaction extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $messageData;

    public function __construct(ContactMessage $messageData)
    {
        $this->messageData = $messageData;
    }

    public function envelope(): Envelope
    {
        // Envia do app padrão
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            replyTo: [
                new Address($this->messageData->email, $this->messageData->name),
            ],
            subject: 'Novo Contato/Denúncia: ' . ($this->messageData->subject ?? 'Sem Assunto'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

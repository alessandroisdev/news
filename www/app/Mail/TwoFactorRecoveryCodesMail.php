<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TwoFactorRecoveryCodesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $codes;

    public function __construct(User $user, array $codes)
    {
        $this->user = $user;
        $this->codes = $codes;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Seus Códigos de Recuperação 2FA (Guarde em segurança)',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.two-factor-recovery',
        );
    }
}

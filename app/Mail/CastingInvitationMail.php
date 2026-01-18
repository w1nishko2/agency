<?php

namespace App\Mail;

use App\Models\CastingApplication;
use App\Models\ModelProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CastingInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $model;
    public $casting;

    /**
     * Create a new message instance.
     */
    public function __construct(ModelProfile $model, CastingApplication $casting)
    {
        $this->model = $model;
        $this->casting = $casting;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Приглашение на кастинг - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.casting-invitation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

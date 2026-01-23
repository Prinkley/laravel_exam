<?php

namespace App\Mail;

use App\Models\Thing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ThingDescriptionChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Thing $thing, public ?string $oldDescription = null)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Описание вещи '{$this->thing->name}' было изменено"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.thing-description-changed',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

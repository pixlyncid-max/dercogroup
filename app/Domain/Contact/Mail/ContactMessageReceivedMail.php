<?php

declare(strict_types=1);

namespace App\Domain\Contact\Mail;

use App\Domain\Company\Models\Company;
use App\Domain\Contact\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceivedMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly ContactMessage $contactMessage,
        public readonly Company $company,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[{$this->company->name}] Pesan Baru: {$this->contactMessage->subject}",
            replyTo: [$this->contactMessage->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: sprintf(
                '<h2>Pesan Masuk — %s</h2><p><strong>Nama:</strong> %s</p><p><strong>Email:</strong> %s</p><p><strong>Telepon:</strong> %s</p><p><strong>Organisasi:</strong> %s</p><p><strong>Subjek:</strong> %s</p><hr><p>%s</p>',
                e($this->company->name),
                e($this->contactMessage->name),
                e($this->contactMessage->email),
                e($this->contactMessage->phone ?? '-'),
                e($this->contactMessage->organization ?? '-'),
                e($this->contactMessage->subject),
                nl2br(e($this->contactMessage->message)),
            ),
        );
    }
}

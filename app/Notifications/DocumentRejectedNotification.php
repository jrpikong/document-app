<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentRejectedNotification extends Notification
{
    public function __construct(
        public Document $document,
        public string $note
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Document Rejected')
            ->line("Your document '{$this->document->title}' was rejected.")
            ->line("Reason: {$this->note}")
            ->action('View Document', url('/admin/documents/' . $this->document->id));
    }
}


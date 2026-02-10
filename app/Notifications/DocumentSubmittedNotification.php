<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DocumentSubmittedNotification extends Notification
{
    public function __construct(public Document $document) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Document Awaiting Approval')
            ->line("Document '{$this->document->title}' has been submitted.")
            ->action('Review Document', url('/admin/documents/' . $this->document->id))
            ->line('Please review and approve.');
    }
}

<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentApprovedNotification extends Notification
{
    public function __construct(public Document $document) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Document Approved')
            ->line("Your document '{$this->document->title}' was approved.")
            ->action('View Document', url('/admin/documents/' . $this->document->id));
    }
}

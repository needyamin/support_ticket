<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\TicketAttachment;

class TicketAttachmentAdded extends Notification implements ShouldQueue
{
    use Queueable;

    public $attachment;

    public function __construct(TicketAttachment $attachment)
    {
        $this->attachment = $attachment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('A new attachment has been added to your ticket')
            ->line('A new attachment has been added to your ticket: ' . $this->attachment->ticket->subject)
            ->action('View Ticket', url(route('etricket.show', $this->attachment->ticket->id)))
            ->line('Thank you for using our support system!');
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->attachment->ticket->id,
            'subject' => $this->attachment->ticket->subject,
            'message' => 'A new attachment has been added.'
        ];
    }
}

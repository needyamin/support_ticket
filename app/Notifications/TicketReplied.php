<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\TicketReply;

class TicketReplied extends Notification implements ShouldQueue
{
    use Queueable;

    public $reply;

    public function __construct(TicketReply $reply)
    {
        $this->reply = $reply;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('A new reply has been added to your ticket')
            ->line('A new reply has been added to your ticket: ' . $this->reply->ticket->subject)
            ->action('View Ticket', url(route('etricket.show', $this->reply->ticket->id)))
            ->line('Thank you for using our support system!');
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->reply->ticket->id,
            'subject' => $this->reply->ticket->subject,
            'message' => 'A new reply has been added.'
        ];
    }
}

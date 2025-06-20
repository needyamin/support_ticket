<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Ticket;

class TicketStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;
    public $oldStatus;
    public $newStatus;

    public function __construct(Ticket $ticket, $oldStatus, $newStatus)
    {
        $this->ticket = $ticket;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Ticket status changed')
            ->line('The status of ticket "' . $this->ticket->subject . '" has changed from ' . ucfirst($this->oldStatus) . ' to ' . ucfirst($this->newStatus) . '.')
            ->action('View Ticket', url(route('etricket.show', $this->ticket->id)))
            ->line('Thank you for using our support system!');
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'subject' => $this->ticket->subject,
            'message' => 'Ticket status changed from ' . ucfirst($this->oldStatus) . ' to ' . ucfirst($this->newStatus) . '.',
        ];
    }
}

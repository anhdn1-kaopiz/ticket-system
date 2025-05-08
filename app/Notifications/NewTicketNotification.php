<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTicketNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = config('app.frontend_url') . '/api/tickets/' . $this->ticket->id;

        return (new MailMessage)
            ->subject('New Ticket Created: ' . $this->ticket->title)
            ->greeting('Hello ' . $notifiable->name)
            ->line('A new ticket has been created that requires your attention.')
            ->line('Ticket Title: ' . $this->ticket->title)
            ->line('Priority: ' . ucfirst($this->ticket->priority))
            ->line('Created by: ' . $this->ticket->creator->name)
            ->action('View Ticket', $url)
            ->line('Please review and take appropriate action.');
    }
}

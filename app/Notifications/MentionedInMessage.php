<?php

namespace App\Notifications;

use App\Models\Collaboration\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MentionedInMessage extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Message $message)
    {
    }
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }
    public function toArray($notifiable)
    {
        return [
            'type' => 'mention',
            'message_id' => $this->message->id,
            'channel_id' => $this->message->channel_id,
            'preview' => mb_strimwidth((string) $this->message->content, 0, 180, '…'),
        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }
}

<?php

namespace App\Events;

use App\Models\Collaboration\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message)
    {
        $this->message->load('user');
    }
    public function broadcastOn()
    {
        return new PresenceChannel('channel.' . $this->message->channel_id);
    }
    public function broadcastAs()
    {
        return 'message.sent';
    }
    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'content' => $this->message->content,
            'user' => ['id' => $this->message->user->id, 'name' => $this->message->user->name ?? $this->message->user->email],
            'parent_id' => $this->message->parent_id,
            'created_at' => $this->message->created_at?->toIsoString(),
        ];
    }
}

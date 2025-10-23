<?php

namespace Tests\Feature;

use App\Models\Collaboration\Message;
use App\Models\User;
use App\Notifications\MentionedInMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Tests\TestCase;

class MentionedInMessageTest extends TestCase
{

    /** @test */
    public function to_array_y_to_broadcast_devuelven_el_payload_esperado()
    {
        $author = User::factory()->create();
        $msg = Message::create([
            'user_id' => $author->id,
            'content' => 'Hola @alguien@example.com',
            'channel_id' => 1
        ]);

        $n = new MentionedInMessage($msg->id);

        $payload = $n->toArray($author);
        $this->assertIsArray($payload);
        $this->assertSame($msg->id, $payload['message_id']);
        $this->assertSame('mention', $payload['type']);
        $this->assertArrayHasKey('preview', $payload);

        $broadcast = $n->toBroadcast($author);
        $this->assertInstanceOf(BroadcastMessage::class, $broadcast);
        $this->assertArrayHasKey('message_id', $broadcast->data);
    }
}
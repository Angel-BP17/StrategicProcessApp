<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Collaboration\Channel;
use App\Models\Collaboration\Message;
use App\Notifications\MentionedInMessage;
use App\Events\MessageSent;

class MentionEndToEndTest extends TestCase
{

    /** @test */
    public function crea_mencion_y_notifica_por_broadcast_y_base_de_datos_y_emite_el_evento_de_mensaje()
    {
        // Evita drivers externos reales durante el test
        config(['queue.default' => 'sync']);           // las notificaciones ShouldQueue se ejecutan inmediatamente
        config(['broadcasting.default' => 'log']);     // broadcast "inofensivo" para pruebas

        // --- Arrange: usuarios y canal ---
        // Usuario mencionado (ID 1, Angel Bustamante)
        $angel = User::create([
            'id' => 1,
            'first_name' => 'Angel',
            'last_name' => 'Bustamante',
            'full_name' => 'Angel Bustamante',
            'dni' => 'TEMP-00001',
            'email' => 'angel@example.test',
            'role' => ["planner", "admin"],
            'password' => bcrypt('secret'),
        ]);

        // Autor del mensaje (ID 2) - debe ser miembro del canal y NO estar "banned"
        $author = User::create([
            'id' => 2,
            'first_name' => 'Autor',
            'last_name' => 'Pruebas',
            'full_name' => 'Autor Pruebas',
            'dni' => 'TEMP-00002',
            'email' => 'autor@example.test',
            'role' => ["planner", "admin"],
            'password' => bcrypt('secret'),
        ]);

        // Canal (ID 1) con ambos como miembros
        $channel = Channel::create([
            'id' => 1,
            'name' => 'Canal de pruebas',
            'created_by_user_id' => $author->id,
            'members' => [
                ['user_id' => $author->id, 'role' => 'admin'],
                ['user_id' => $angel->id, 'role' => 'member'],
            ],
        ]);

        // Falsifica SOLO el evento MessageSent para poder asertar que se despacha
        Event::fake([MessageSent::class]);

        // --- Act: el autor publica un mensaje con una mención válida ---
        $this->actingAs($author)
            ->post(route('collab.messages.store', ['channel' => $channel->id]), [
                'content' => 'Hola @Angel_Bustamante, bienvenido a la conversación.',
            ])
            ->assertRedirect(); // el controller hace "return back()"

        // Recupera el mensaje recién creado
        $message = Message::query()->latest('id')->first();
        $this->assertNotNull($message, 'No se creó el mensaje');
        $this->assertSame($channel->id, $message->channel_id);
        $this->assertSame($author->id, $message->user_id);

        // --- Assert 1: Observer + Notification (BD) ---
        // Debe existir una notificación en BD para el usuario 1 del tipo MentionedInMessage
        $row = DB::table('notifications')
            ->where('notifiable_id', $angel->id)
            ->where('notifiable_type', User::class)
            ->where('type', MentionedInMessage::class)
            ->first();

        $this->assertNotNull($row, 'No se encontró la notificación en BD para el usuario mencionado');

        $data = json_decode($row->data ?? '{}', true);
        $this->assertSame('mention', $data['type'] ?? null);
        $this->assertSame($message->id, $data['message_id'] ?? null);
        $this->assertSame($channel->id, $data['channel_id'] ?? null);
        $this->assertIsString($data['preview'] ?? null);

        // Además, la notificación declara canales 'database' y 'broadcast'
        $notif = new MentionedInMessage($message);
        $via = $notif->via($angel);
        $this->assertContains('database', $via, 'La notificación no declara canal "database"');
        $this->assertContains('broadcast', $via, 'La notificación no declara canal "broadcast"');

        // --- Assert 2: Evento de broadcast del mensaje (MessageSent) ---
        Event::assertDispatched(MessageSent::class, function (MessageSent $e) use ($channel, $author, $message) {
            // Canal correcto
            $on = $e->broadcastOn();
            $this->assertEquals('channel.' . $channel->id, method_exists($on, 'name') ? $on->name : (string) $on);

            // Nombre del evento
            $this->assertEquals('message.sent', $e->broadcastAs());

            // Payload
            $payload = $e->broadcastWith();
            $this->assertSame($message->id, $payload['id']);
            $this->assertSame($message->content, $payload['content']);
            $this->assertSame($author->id, $payload['user']['id']);
            $this->assertArrayHasKey('created_at', $payload);

            return true;
        });
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Collaboration\Message;
use App\Notifications\MentionedInMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MentionedInMessageObserverTest extends TestCase
{

    /** @test */
    public function dispara_la_notificacion_con_payload_correcto_y_canales_database_y_broadcast()
    {
        Notification::fake();

        // Usuarios fijos
        DB::table('users')->updateOrInsert(['id'=>1],[
            'first_name'=>'Angel','last_name'=>'Bustamante','email'=>'angel@example.com','password'=>bcrypt('secret'),
            'created_at'=>now(),'updated_at'=>now(),
        ]);
        DB::table('users')->updateOrInsert(['id'=>2],[
            'first_name'=>'Carlos','last_name'=>'Lopez','email'=>'carlos@example.com','password'=>bcrypt('secret'),
            'created_at'=>now(),'updated_at'=>now(),
        ]);

        // Canal id=1 con miembros [{user_id:1},{user_id:2}]
        DB::table('channels')->updateOrInsert(['id'=>1],[
            'name'=>'Canal General',
            'members'=>json_encode([['user_id'=>1],['user_id'=>2]]),
            'created_at'=>now(),'updated_at'=>now(),
        ]);

        // Mensaje que menciona por nombre, apellido y combinado
        $content = "Hola @Angel, ping @Bustamante y @Angel_Bustamante.";
        Message::unguard();
        $msg = Message::create([
            'user_id'=>2, // autor
            'channel_id'=>1,
            'content'=>$content,
            'created_at'=>now(),
        ]);

        $angel  = User::findOrFail(1);
        $author = User::findOrFail(2);

        Notification::assertSentTo(
            $angel,
            MentionedInMessage::class,
            function (MentionedInMessage $notification, array $channels) use ($msg, $angel) {
                // 1) Canales
                $this->assertContains('database', $channels);
                $this->assertContains('broadcast', $channels);

                // 2) ShouldQueue
                $this->assertInstanceOf(ShouldQueue::class, $notification);

                // 3) Payload
                $data = $notification->toArray($angel);
                $this->assertSame('mention', $data['type'] ?? null);
                $this->assertSame($msg->id, $data['message_id'] ?? null);
                $this->assertSame($msg->channel_id, $data['channel_id'] ?? null);
                $this->assertIsString($data['preview'] ?? '');
                $this->assertNotSame('', $data['preview']);

                // 4) Sanity: via() devuelve lo esperado
                $this->assertEqualsCanonicalizing(['database','broadcast'], $notification->via($angel));

                return true;
            }
        );

        // El autor NO debe recibir nada
        Notification::assertNotSentTo($author, MentionedInMessage::class);
    }

    /** @test */
    public function no_envia_notificacion_si_no_hay_menciones_validas()
    {
        Notification::fake();

        DB::table('users')->updateOrInsert(['id'=>1],[
            'first_name'=>'Angel','last_name'=>'Bustamante','email'=>'angel@example.com','password'=>bcrypt('secret'),
            'created_at'=>now(),'updated_at'=>now(),
        ]);
        DB::table('users')->updateOrInsert(['id'=>2],[
            'first_name'=>'Carlos','last_name'=>'Lopez','email'=>'carlos@example.com','password'=>bcrypt('secret'),
            'created_at'=>now(),'updated_at'=>now(),
        ]);
        DB::table('channels')->updateOrInsert(['id'=>1],[
            'name'=>'Canal General',
            'members'=>json_encode([['user_id'=>1],['user_id'=>2]]),
            'created_at'=>now(),'updated_at'=>now(),
        ]);

        Message::unguard();
        Message::create([
            'user_id'=>2,
            'channel_id'=>1,
            'content'=>'Mensaje sin menciones válidas (@no_vale-por-espacios ni @123).',
            'created_at'=>now(),
        ]);

        $angel  = User::findOrFail(1);
        $author = User::findOrFail(2);

        Notification::assertNotSentTo($angel, MentionedInMessage::class);
        Notification::assertNotSentTo($author, MentionedInMessage::class);
    }
}

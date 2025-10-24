<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Collaboration\Message;
use App\Notifications\MentionedInMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MentionedInMessagePersistsTest extends TestCase
{

    /** @test */
    public function guarda_la_notificacion_en_la_tabla_notifications()
    {
        // Forzar colas síncronas en pruebas (sin worker)
        config(['queue.default' => 'sync']);

        // IMPORTANTÍSIMO: no interceptar esta notificación
        Notification::fakeExcept([MentionedInMessage::class]);

        // Datos mínimos
        DB::table('users')->insert([
            ['id'=>1,'first_name'=>'Angel','last_name'=>'Bustamante','email'=>'angel@example.com','password'=>bcrypt('x'),'created_at'=>now(),'updated_at'=>now()],
            ['id'=>2,'first_name'=>'Carlos','last_name'=>'Lopez','email'=>'carlos@example.com','password'=>bcrypt('x'),'created_at'=>now(),'updated_at'=>now()],
        ]);

        DB::table('channels')->insert([
            'id'=>1,
            'name'=>'General',
            'members'=>json_encode([['user_id'=>1],['user_id'=>2]]),
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // Dispara el observer (menciona a Angel)
        Message::unguard();
        $msg = Message::create([
            'user_id'=>2,
            'channel_id'=>1,
            'content'=>'Hola @Angel y @Bustamante',
            'created_at'=>now(),
        ]);

        // Verifica que se haya persistido en BD
        // type = clase FQCN de la notificación, notifiable_id = 1 (Angel), notifiable_type = modelo User
        $this->assertDatabaseHas('notifications', [
            'notifiable_id'   => 1,
            'notifiable_type' => User::class,
            'type'            => MentionedInMessage::class,
        ]);

        // (Opcional) Verifica estructura del data JSON
        $row = DB::table('notifications')
            ->where('notifiable_id', 1)
            ->where('type', MentionedInMessage::class)
            ->first();

        $this->assertNotNull($row);
        $data = json_decode($row->data, true);
        $this->assertSame('mention', $data['type'] ?? null);
        $this->assertSame($msg->id, $data['message_id'] ?? null);
        $this->assertSame($msg->channel_id, $data['channel_id'] ?? null);
    }
}

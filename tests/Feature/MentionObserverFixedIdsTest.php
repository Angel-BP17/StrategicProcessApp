<?php

namespace Tests\Feature;

use App\Models\Collaboration\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Notifications\MentionedInMessage;

class MentionObserverFixedIdsTest extends TestCase
{
 /** @test */
    public function notifica_por_first_y_last_name_con_limite_y_ids_fijos()
    {
        Notification::fake();

        // Users fijos
        DB::table('users')->updateOrInsert(['id'=>1],[
            'first_name'=>'Angel','last_name'=>'Bustamante','email'=>'angel@example.com','password'=>bcrypt('secret'),
            'created_at'=>now(),'updated_at'=>now(),
        ]);
        DB::table('users')->updateOrInsert(['id'=>2],[
            'first_name'=>'Carlos','last_name'=>'Lopez','email'=>'carlos@example.com','password'=>bcrypt('secret'),
            'created_at'=>now(),'updated_at'=>now(),
        ]);

        // 20 usuarios extra para forzar límite
        $extraUsers=[];
        for($i=0;$i<20;$i++){
            $uid=100+$i;
            $extraUsers[]=[
                'id'=>$uid,'first_name'=>"extra{$i}",'last_name'=>"demo{$i}",'email'=>"extra{$i}@example.com",
                'password'=>bcrypt('secret'),'created_at'=>now(),'updated_at'=>now(),
            ];
        }
        DB::table('users')->upsert($extraUsers,['id'],['first_name','last_name','email','password','updated_at']);

        // Canal id=1 con miembros (estructura JSON [{user_id:int},...])
        $members = collect([1,2])->merge(array_column($extraUsers,'id'))
                    ->map(fn($id)=>['user_id'=>(int)$id])->values()->all();

        DB::table('channels')->updateOrInsert(['id'=>1],[
            'name'=>'Canal General','members'=>json_encode($members),
            'created_at'=>now(),'updated_at'=>now(),
        ]);

        // Contenido con @Angel, @Bustamante, @Angel_Bustamante y 20 extra
        $extraTokens = implode(' ', array_map(fn($i)=>"@extra{$i}", range(0,19)));
        $content = "Hola @Angel, revisa esto. Ping @Bustamante y @Angel_Bustamante. {$extraTokens}";

        // Crear mensaje (dispara el observer registrado para App\Models\Collaboration\Message)
        Message::unguard();
        $msg = Message::create([
            'user_id'=>2,'channel_id'=>1,'content'=>$content,'created_at'=>now(),
        ]);

        // Aserciones: usar modelos reales
        $angel = User::findOrFail(1);
        $author= User::findOrFail(2);

        // Angel debe haber sido notificado (una sola vez es suficiente como verificación)
        Notification::assertSentTo($angel, MentionedInMessage::class);

        // Autor NO debe ser notificado
        Notification::assertNotSentTo($author, MentionedInMessage::class);

        // No más de 10 usuarios notificados
        $candidateIds = array_merge([1], array_column($extraUsers,'id'));
        $notified = 0;
        foreach ($candidateIds as $cid) {
            $u = User::find($cid);
            if (!$u) continue;
            try {
                Notification::assertSentTo($u, MentionedInMessage::class);
                $notified++;
            } catch (\Throwable $e) { /* no notificado */ }
        }
        $this->assertLessThanOrEqual(10, $notified, "Se notificó a más de 10 usuarios ({$notified}).");
    }
}

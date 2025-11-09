<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('messages')->insert([
            ['conversation_id' => 1, 'user_id' => 2, 'content' => 'Adjunto la última matriz de seguimiento de acreditación.'],
            ['conversation_id' => 1, 'user_id' => 5, 'content' => 'Gracias, reviso KPI de satisfacción y reporto el viernes.'],
            ['conversation_id' => 2, 'user_id' => 3, 'content' => 'Se firmó el convenio con TechCorp. Actualizo el registro.'],
            ['conversation_id' => 3, 'user_id' => 4, 'content' => 'Propuesta piloto para aprendizaje basado en retos 2025.'],
        ]);
    }
}

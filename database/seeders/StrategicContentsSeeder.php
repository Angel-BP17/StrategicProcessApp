<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StrategicContentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('strategic_contents')->insert([
            ['type' => 'mission', 'content' => 'Brindar formación técnica de alta calidad con enfoque en empleabilidad y ética profesional.'],
            ['type' => 'vision', 'content' => 'Ser referente nacional en innovación educativa y vinculación con la industria.'],
            ['type' => 'objective', 'content' => 'Incrementar en 20% la tasa de empleabilidad de egresados al 2026.'],
            ['type' => 'objective', 'content' => 'Acreditar el 100% de los programas activos en 3 años.'],
            ['type' => 'plan', 'content' => 'Plan estratégico 2025-2027 enfocado en calidad académica, alianzas y transformación digital.'],
        ]);
    }
}

<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConversationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('conversations')->insert([
            ['name' => 'Línea Estratégica: Calidad Educativa'],
            ['name' => 'Línea Estratégica: Alianzas y Convenios'],
            ['name' => 'Línea Estratégica: Innovación y Mejora Continua'],
        ]);
    }
}

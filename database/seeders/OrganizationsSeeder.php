<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('organizations')->insert([
            ['ruc' => '20123456789', 'name' => 'Universidad Horizonte del Norte', 'type' => 'Universidad', 'contact_phone' => '+51 01 5550001', 'contact_email' => 'convenios@uhn.edu.pe'],
            ['ruc' => '20654321987', 'name' => 'TechCorp Perú S.A.C.', 'type' => 'Empresa', 'contact_phone' => '+51 01 5550002', 'contact_email' => 'alianzas@techcorp.pe'],
            ['ruc' => '20567891234', 'name' => 'Gobierno Regional Áncash', 'type' => 'Organismo Público', 'contact_phone' => '+51 43 5550100', 'contact_email' => 'gestionconvenios@gr-ancash.gob.pe'],
            ['ruc' => '20987654321', 'name' => 'Cámara de Comercio de Chimbote', 'type' => 'Asociación', 'contact_phone' => '+51 43 5550200', 'contact_email' => 'contacto@ccc.org.pe'],
        ]);
    }
}

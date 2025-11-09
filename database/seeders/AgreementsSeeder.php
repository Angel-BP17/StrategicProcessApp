<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgreementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('agreements')->insert([
            ['organization_id' => 1, 'name' => 'Convenio de prácticas y pasantías', 'start_date' => '2025-01-15', 'renewal_date' => '2026-01-15', 'purpose' => 'Inserción laboral y pasantías pre-profesionales.', 'status' => 'vigente'],
            ['organization_id' => 2, 'name' => 'Alianza de innovación y co-diseño curricular', 'start_date' => '2025-03-01', 'renewal_date' => '2027-03-01', 'purpose' => 'Actualización de contenidos y proyectos reales.', 'status' => 'vigente'],
            ['organization_id' => 3, 'name' => 'Programa de becas y formación dual', 'start_date' => '2025-02-10', 'renewal_date' => '2026-02-10', 'purpose' => 'Becas parciales y aprendizaje en servicio.', 'status' => 'en evaluación'],
            ['organization_id' => 4, 'name' => 'Convenio de empleabilidad y ferias laborales', 'start_date' => '2025-04-20', 'renewal_date' => '2026-04-20', 'purpose' => 'Vinculación con empresas locales y ferias.', 'status' => 'vigente'],
        ]);
    }
}

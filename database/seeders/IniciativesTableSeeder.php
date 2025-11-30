<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use IncadevUns\CoreDomain\Models\Iniciative;

class IniciativesTableSeeder extends Seeder
{
    public function run(): void
    {
        Iniciative::create([
            'title' => 'Programa de practicas con empresas locales',
            'plan_id' => 1,
            'summary' => 'Fortalecer insercion laboral mediante convenios de practicas.',
            'user_id' => 2,
            'status' => 'en_ejecucion',
            'start_date' => '2025-02-01',
            'end_date' => '2025-12-15',
            'estimated_impact' => 'Alto',
        ]);

        Iniciative::create([
            'title' => 'Celula de acreditacion por escuela',
            'plan_id' => 1,
            'summary' => 'Equipos dedicados a evidencias y mejora continua.',
            'user_id' => 4,
            'status' => 'propuesta',
            'start_date' => '2025-03-01',
            'end_date' => '2026-03-01',
            'estimated_impact' => 'Medio',
        ]);

        Iniciative::create([
            'title' => 'Migracion de servicios a la nube',
            'plan_id' => 2,
            'summary' => 'Priorizar sistemas academicos y portal institucional.',
            'user_id' => 2,
            'status' => 'en_ejecucion',
            'start_date' => '2025-04-01',
            'end_date' => '2026-01-31',
            'estimated_impact' => 'Alto',
        ]);

        Iniciative::create([
            'title' => 'Capacitacion docente en aulas virtuales',
            'plan_id' => 2,
            'summary' => 'Talleres mensuales y certificaciones.',
            'user_id' => 4,
            'status' => 'propuesta',
            'start_date' => '2025-05-15',
            'end_date' => '2025-11-30',
            'estimated_impact' => 'Medio',
        ]);

        Iniciative::create([
            'title' => 'Revision de procesos academicos',
            'plan_id' => 1,
            'summary' => 'Auditar flujos actuales y documentar mejoras rapidas.',
            'user_id' => 2,
            'status' => 'en_revision',
            'start_date' => '2025-06-01',
            'end_date' => '2025-09-30',
            'estimated_impact' => 'Medio',
        ]);

        Iniciative::create([
            'title' => 'Automatizacion de reportes gerenciales',
            'plan_id' => 2,
            'summary' => 'Implementar pipelines de datos para reportes recurrentes.',
            'user_id' => 4,
            'status' => 'aprobada',
            'start_date' => '2025-07-01',
            'end_date' => '2025-12-15',
            'estimated_impact' => 'Alto',
        ]);

        Iniciative::create([
            'title' => 'Portal de transparencia institucional',
            'plan_id' => 2,
            'summary' => 'Publicar indicadores y avances en un portal abierto.',
            'user_id' => 2,
            'status' => 'finalizada',
            'start_date' => '2025-01-15',
            'end_date' => '2025-05-30',
            'estimated_impact' => 'Alto',
        ]);

        Iniciative::create([
            'title' => 'Sistema de feedback estudiantil',
            'plan_id' => 1,
            'summary' => 'Encuestas y panel de seguimiento de mejoras.',
            'user_id' => 4,
            'status' => 'evaluada',
            'start_date' => '2024-11-01',
            'end_date' => '2025-03-31',
            'estimated_impact' => 'Medio',
        ]);

        Iniciative::create([
            'title' => 'Central de compras de licencias',
            'plan_id' => 1,
            'summary' => 'Modelo unico de adquisicion de software para todas las facultades.',
            'user_id' => 2,
            'status' => 'rechazada',
            'start_date' => '2025-02-01',
            'end_date' => '2025-04-30',
            'estimated_impact' => 'Medio',
        ]);
    }
}

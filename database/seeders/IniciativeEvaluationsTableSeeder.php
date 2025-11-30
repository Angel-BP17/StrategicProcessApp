<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use IncadevUns\CoreDomain\Models\IniciativeEvaluation;

class IniciativeEvaluationsTableSeeder extends Seeder
{
    public function run(): void
    {
        // En ejecución (id 1) - evaluación principal
        IniciativeEvaluation::create([
            'iniciative_id' => 1,
            'evaluator_user' => 5,
            'summary' => 'Se evidencian avances en convenios y primeras incorporaciones.',
            'score' => 85.50,
            'document_id' => 2,
        ]);

        // En ejecución (id 1) - seguimiento
        IniciativeEvaluation::create([
            'iniciative_id' => 1,
            'evaluator_user' => 6,
            'summary' => 'Seguimiento: acuerdos firmados y primeros practicantes asignados.',
            'score' => 88.00,
            'document_id' => null,
        ]);

        // En ejecución (id 3)
        IniciativeEvaluation::create([
            'iniciative_id' => 3,
            'evaluator_user' => 6,
            'summary' => 'Migracion con hitos cumplidos; pendiente optimizar costos.',
            'score' => 78.25,
            'document_id' => 1,
        ]);

        // En ejecución (id 3) - seguimiento
        IniciativeEvaluation::create([
            'iniciative_id' => 3,
            'evaluator_user' => 5,
            'summary' => 'Seguimiento: se estabilizaron servicios, ahorro moderado logrado.',
            'score' => 80.50,
            'document_id' => null,
        ]);

        // Finalizada (id 7) — al crearla podría pasar a evaluada
        IniciativeEvaluation::create([
            'iniciative_id' => 7,
            'evaluator_user' => 5,
            'summary' => 'Portal publicado y accesible; pendiente agregar serie historica.',
            'score' => 90.00,
            'document_id' => null,
        ]);

        // Finalizada (id 7) — evaluación final
        IniciativeEvaluation::create([
            'iniciative_id' => 7,
            'evaluator_user' => 6,
            'summary' => 'Evaluacion final: portal completo con historicos y dashboards basicos.',
            'score' => 93.50,
            'document_id' => null,
        ]);

        // Evaluada (id 8) — ya en estado evaluada
        IniciativeEvaluation::create([
            'iniciative_id' => 8,
            'evaluator_user' => 6,
            'summary' => 'Feedback capturado y acciones de mejora registradas.',
            'score' => 88.00,
            'document_id' => null,
        ]);
    }
}

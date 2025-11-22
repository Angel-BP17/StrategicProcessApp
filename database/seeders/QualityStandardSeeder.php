<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use IncadevUns\CoreDomain\Models\QualityStandard;

class QualityStandardSeeder extends Seeder
{
    public function run(): void
    {
        // Estándar 1: Infraestructura (Para todos)
        QualityStandard::create([
            'name' => 'Conectividad en Laboratorios (SINEACE E.4)',
            'description' => 'Evalúa la velocidad y estabilidad del internet en los laboratorios de cómputo.',
            'category' => 'INFRAESTRUCTURA',
            'target_score' => 4.5,
            'target_roles' => null, // Visible para todos
            'is_active' => true
        ]);

        // Estándar 2: Servicios (Solo Estudiantes)
        QualityStandard::create([
            'name' => 'Satisfacción con Bienestar Estudiantil',
            'description' => 'Calidad de la atención psicológica y tópicos de salud.',
            'category' => 'SERVICIOS',
            'target_score' => 4.0,
            'target_roles' => ['student'],
            'is_active' => true
        ]);

        // Estándar 3: Procesos (Solo Docentes)
        QualityStandard::create([
            'name' => 'Soporte Técnico Docente',
            'description' => 'Tiempo de respuesta ante fallas en equipos de aula.',
            'category' => 'PROCESOS',
            'target_score' => 4.8,
            'target_roles' => ['teacher'],
            'is_active' => true
        ]);
    }
}
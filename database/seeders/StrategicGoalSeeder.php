<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Incadev\CoreDomain\Models\StrategicGoal;

class StrategicGoalSeeder extends Seeder
{
    public function run(): void
    {
        // Meta 1: Para TODOS (Infraestructura)
        StrategicGoal::create([
            'title' => 'Mejora de Velocidad de Internet',
            'description' => 'Lograr una velocidad estable de 100mbps en todos los laboratorios.',
            'category' => 'INFRAESTRUCTURA',
            'target_score' => 4.5,
            'target_roles' => null, // Null significa "Para todos"
            'is_active' => true
        ]);

        // Meta 2: Solo para ESTUDIANTES (Servicios)
        StrategicGoal::create([
            'title' => 'Calidad de Atención en Cafetería',
            'description' => 'Mejorar la variedad y precio de los menús estudiantiles.',
            'category' => 'SERVICIOS',
            'target_score' => 4.0,
            'target_roles' => ['student'], // Solo estudiantes verán esto
            'is_active' => true
        ]);

        // Meta 3: Solo para DOCENTES (Clima)
        StrategicGoal::create([
            'title' => 'Disponibilidad de Recursos Didácticos',
            'description' => 'Asegurar proyectores funcionales en el 100% de las aulas.',
            'category' => 'DOCENCIA',
            'target_score' => 4.8,
            'target_roles' => ['teacher'], // Solo profes verán esto
            'is_active' => true
        ]);
    }
}
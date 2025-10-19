<?php

namespace App\Http\Controllers\InnovacionMejoraContinua;

use App\Http\Controllers\Controller;
use App\Models\InnovacionMejoraContinua\Initiative;
use App\Models\InnovacionMejoraContinua\InitiativeEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InnovacionMejoraController extends Controller
{
    /**
     * Página principal del módulo de Innovación y Mejora Continua
     * Muestra estadísticas generales y accesos rápidos
     */
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'total_initiatives' => Initiative::count(),
            'active_initiatives' => Initiative::whereIn('status', ['propuesta', 'evaluada', 'aprobada', 'implementada'])->count(),
            'approved_initiatives' => Initiative::where('status', 'aprobada')->count(),
            'implemented_initiatives' => Initiative::where('status', 'implementada')->count(),
            'total_evaluations' => InitiativeEvaluation::count(),
        ];

        // Iniciativas por estado
        $initiativesByStatus = Initiative::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        // Últimas iniciativas creadas
        $recentInitiatives = Initiative::with(['responsibleUser', 'evaluations'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Últimas evaluaciones realizadas
        $recentEvaluations = InitiativeEvaluation::with(['initiative', 'evaluator'])
            ->orderBy('evaluation_date', 'desc')
            ->limit(5)
            ->get();

        // Promedio de puntuación de evaluaciones
        $averageScore = InitiativeEvaluation::avg('score');

        return view('innovacion_mejora_continua.index', compact(
            'stats',
            'initiativesByStatus',
            'recentInitiatives',
            'recentEvaluations',
            'averageScore'
        ));
    }

    /**
     * Dashboard con métricas y gráficos
     */
    public function dashboard()
    {
        // Iniciativas por mes (últimos 12 meses)
        $initiativesByMonth = Initiative::select(
            DB::raw("TO_CHAR(created_at, 'YYYY-MM') as month"),
            DB::raw('count(*) as total')
        )
        ->where('created_at', '>=', now()->subMonths(12))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Evaluaciones por mes (últimos 12 meses)
        $evaluationsByMonth = InitiativeEvaluation::select(
            DB::raw("TO_CHAR(evaluation_date, 'YYYY-MM') as month"),
            DB::raw('count(*) as total'),
            DB::raw('AVG(score) as avg_score')
        )
        ->where('evaluation_date', '>=', now()->subMonths(12))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Top 5 iniciativas mejor evaluadas
        $topInitiatives = Initiative::select('initiatives.*', DB::raw('AVG(initiative_evaluations.score) as avg_score'))
            ->join('initiative_evaluations', 'initiatives.id', '=', 'initiative_evaluations.initiative_id')
            ->groupBy('initiatives.id')
            ->orderBy('avg_score', 'desc')
            ->limit(5)
            ->with('responsibleUser')
            ->get();

        // Distribución de estados
        $statusDistribution = Initiative::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Tiempo promedio de evaluación (desde creación hasta primera evaluación)
        $avgEvaluationTime = Initiative::select(
            DB::raw('AVG(EXTRACT(DAY FROM (initiative_evaluations.evaluation_date - initiatives.created_at))) as avg_days')
        )
        ->join('initiative_evaluations', 'initiatives.id', '=', 'initiative_evaluations.initiative_id')
        ->value('avg_days');

        return view('innovacion_mejora_continua.dashboard', compact(
            'initiativesByMonth',
            'evaluationsByMonth',
            'topInitiatives',
            'statusDistribution',
            'avgEvaluationTime'
        ));
    }
}

<?php

namespace App\Http\Controllers\InnovacionMejoraContinua;

use App\Http\Controllers\Controller;
use App\Models\InnovacionMejoraContinua\Initiative;
use App\Models\InnovacionMejoraContinua\InitiativeEvaluation;
use Illuminate\Support\Facades\DB;

class InnovacionMejoraController extends Controller
{
    public function index()
    {
        $stats = [
            'total_initiatives' => Initiative::count(),
            'active_initiatives' => Initiative::whereIn('status', ['propuesta', 'evaluada', 'aprobada', 'implementada'])->count(),
            'approved_initiatives' => Initiative::where('status', 'aprobada')->count(),
            'implemented_initiatives' => Initiative::where('status', 'implementada')->count(),
            'total_evaluations' => InitiativeEvaluation::count(),
        ];

        $initiativesByStatus = Initiative::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        $recentInitiatives = Initiative::with(['responsibleUser', 'evaluations'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentEvaluations = InitiativeEvaluation::with(['initiative', 'evaluator'])
            ->orderBy('evaluation_date', 'desc')
            ->limit(5)
            ->get();

        $averageScore = InitiativeEvaluation::avg('score');

        return response()->json([
            'stats' => $stats,
            'initiativesByStatus' => $initiativesByStatus,
            'recentInitiatives' => $recentInitiatives,
            'recentEvaluations' => $recentEvaluations,
            'averageScore' => $averageScore,
        ]);
    }

    public function dashboard()
    {
        $initiativesByMonth = Initiative::select(
            DB::raw("TO_CHAR(created_at, 'YYYY-MM') as month"),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $evaluationsByMonth = InitiativeEvaluation::select(
            DB::raw("TO_CHAR(evaluation_date, 'YYYY-MM') as month"),
            DB::raw('count(*) as total'),
            DB::raw('AVG(score) as avg_score')
        )
            ->where('evaluation_date', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topInitiatives = Initiative::select('initiatives.*', DB::raw('AVG(initiative_evaluations.score) as avg_score'))
            ->join('initiative_evaluations', 'initiatives.id', '=', 'initiative_evaluations.initiative_id')
            ->groupBy('initiatives.id')
            ->orderBy('avg_score', 'desc')
            ->limit(5)
            ->with('responsibleUser')
            ->get();

        $statusDistribution = Initiative::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $avgEvaluationTime = Initiative::select(
            DB::raw('AVG(EXTRACT(DAY FROM (initiative_evaluations.evaluation_date - initiatives.created_at))) as avg_days')
        )
            ->join('initiative_evaluations', 'initiatives.id', '=', 'initiative_evaluations.initiative_id')
            ->value('avg_days');

        return response()->json([
            'initiativesByMonth' => $initiativesByMonth,
            'evaluationsByMonth' => $evaluationsByMonth,
            'topInitiatives' => $topInitiatives,
            'statusDistribution' => $statusDistribution,
            'avgEvaluationTime' => $avgEvaluationTime,
        ]);
    }
}

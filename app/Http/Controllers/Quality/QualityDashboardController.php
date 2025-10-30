<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Quality\Accreditation;
use App\Models\Quality\Audit;
use App\Models\Quality\EvaluationCriterion;
use App\Models\Quality\Survey;
use Carbon\Carbon;

class QualityDashboardController extends Controller
{
    /**
     * Muestra el dashboard principal del Módulo de Calidad.
     */
    public function index()
    {
        $kpiAuditsInProgress = Audit::where('state', 'in_progress')->count();

        $kpiAccreditationsExpiring = Accreditation::where('expiration_date', '>', Carbon::now())
            ->where('expiration_date', '<', Carbon::now()->addMonths(6))
            ->count();

        $kpiSurveysActive = Survey::where('status', 'active')->count();

        $kpiCriteriaActive = EvaluationCriterion::where('state', 'active')->count();

        return response()->json([
            'kpi_audits_in_progress' => $kpiAuditsInProgress,
            'kpi_accreditations_expiring' => $kpiAccreditationsExpiring,
            'kpi_surveys_active' => $kpiSurveysActive,
            'kpi_criteria_active' => $kpiCriteriaActive,
        ]);
    }
}

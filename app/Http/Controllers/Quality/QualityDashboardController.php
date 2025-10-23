<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller; // <-- Importante
use Illuminate\Http\Request;
use App\Models\Quality\Audit;
use App\Models\Quality\Accreditation;
use App\Models\Quality\Survey;
use App\Models\Quality\EvaluationCriterion;
use App\Models\Quality\CorrectiveAction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 

class QualityDashboardController extends Controller
{
    /**
     * Muestra el dashboard principal del Módulo de Calidad.
     */
    /**
 * Muestra el dashboard principal del Módulo de Calidad.
 */
public function index()
{
    // --- Cálculo de KPIs ---

    // 1. KPI Auditorías: Contar cuántas están 'en progreso'.
    $kpi_audits_in_progress = Audit::where('state', 'in_progress')->count();

    // 2. KPI Acreditaciones: Contar cuántas vencen en los próximos 6 meses.
    $kpi_accreditations_expiring = Accreditation::where('expiration_date', '>', Carbon::now())
                                                ->where('expiration_date', '<', Carbon::now()->addMonths(6))
                                                ->count();

    // 3. KPI Encuestas: Contar cuántas encuestas están 'activas'.
    $kpi_surveys_active = Survey::where('status', 'active')->count();

    // 4. KPI Criterios: Contar el total de criterios activos.
    $kpi_criteria_active = EvaluationCriterion::where('state', 'active')->count();


    // --- Pasamos los datos a la vista ---
    return view('quality.index', [
        'kpi_audits_in_progress' => $kpi_audits_in_progress,
        'kpi_accreditations_expiring' => $kpi_accreditations_expiring,
        'kpi_surveys_active' => $kpi_surveys_active,
        'kpi_criteria_active' => $kpi_criteria_active,
    ]);
}
}
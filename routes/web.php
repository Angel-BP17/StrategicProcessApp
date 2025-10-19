<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Planning\KpiController;
use App\Http\Controllers\Planning\PlanningController;
use App\Http\Controllers\Planning\StrategicObjectiveController;
use App\Http\Controllers\Planning\StrategicPlanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;


use App\Http\Controllers\Quality\AuditController;
use App\Http\Controllers\Quality\QualityDashboardController;
use App\Http\Controllers\Quality\FindingController;
use App\Http\Controllers\Quality\CorrectiveActionController;
use App\Http\Controllers\Quality\AccreditationController;
use App\Http\Controllers\Quality\SurveyController;
use App\Http\Controllers\Quality\SurveyQuestionController;
use App\Http\Controllers\Quality\EvaluationCriterionController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Register Routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Password Reset Routes
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    // Logout Route
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard Route (ejemplo)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/collaboration', function () {
        return view('collaboration');
    })->name('collaboration');
});

Route::middleware(['auth', 'role:any,admin,planner'])->prefix('planning')->name('planning.')->group(function () {

    // Home del módulo
    Route::get('/', [PlanningController::class, 'index'])->name('index');

    // Planes de desarrollo (Planificación institucional)
    Route::prefix('plans')->name('plans.')->group(function () {
        Route::get('/', [StrategicPlanController::class, 'index'])->name('index');      // listado de planes
        Route::get('/create', [StrategicPlanController::class, 'create'])->name('create');
        Route::post('/', [StrategicPlanController::class, 'store'])->name('store');
        Route::get('/{plan}', [StrategicPlanController::class, 'show'])->name('show');
        Route::get('/{plan}/edit', [StrategicPlanController::class, 'edit'])->name('edit');
        Route::put('/{plan}', [StrategicPlanController::class, 'update'])->name('update');
        Route::delete('/{plan}', [StrategicPlanController::class, 'destroy'])->name('destroy');
    });

    // Objetivos estratégicos (por plan)
    Route::prefix('plans/{plan}/objectives')->name('objectives.')->group(function () {
        Route::get('/', [StrategicObjectiveController::class, 'index'])->name('index');
        Route::get('/create', [StrategicObjectiveController::class, 'create'])->name('create');
        Route::post('/', [StrategicObjectiveController::class, 'store'])->name('store');
        Route::get('/{objective}', [StrategicObjectiveController::class, 'show'])->name('show');
        Route::get('/{objective}/edit', [StrategicObjectiveController::class, 'edit'])->name('edit');
        Route::put('/{objective}', [StrategicObjectiveController::class, 'update'])->name('update');
        Route::delete('/{objective}', [StrategicObjectiveController::class, 'destroy'])->name('destroy');
    });

    // KPIs (por objetivo)
    Route::prefix('objectives/{objective}/kpis')->name('kpis.')->group(function () {
        Route::get('/', [KpiController::class, 'index'])->name('index');
        Route::get('/create', [KpiController::class, 'create'])->name('create');
        Route::post('/', [KpiController::class, 'store'])->name('store');
        Route::get('/{kpi}', [KpiController::class, 'show'])->name('show');
        Route::get('/{kpi}/edit', [KpiController::class, 'edit'])->name('edit');
        Route::put('/{kpi}', [KpiController::class, 'update'])->name('update');
        Route::delete('/{kpi}', [KpiController::class, 'destroy'])->name('destroy');

        // Mediciones de KPIs (opcional, si quieres subrutas)
        Route::get('/{kpi}/measurements', [KpiController::class, 'measurements'])->name('measurements.index');
        Route::post('/{kpi}/measurements', [KpiController::class, 'storeMeasurement'])->name('measurements.store');
    });

    // Dashboards del módulo
    Route::prefix('dashboards')->name('dashboards.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/{dashboard}', [DashboardController::class, 'show'])->name('show');
    });
});




/*
|--------------------------------------------------------------------------
| MÓDULO: GESTIÓN DE CALIDAD
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('quality')->name('quality.')->group(function () {

    // Dashboard Principal de Calidad
    Route::get('/', [QualityDashboardController::class, 'index'])->name('index');

    // Sub-módulo: Auditorías
    Route::get('/audits', [AuditController::class, 'index'])->name('audits.index');
    Route::get('/audits/create', [AuditController::class, 'create'])->name('audits.create'); 
    Route::post('/audits', [AuditController::class, 'store'])->name('audits.store'); 
    Route::get('/audits/{audit}', [AuditController::class, 'show'])->name('audits.show'); 

    // --- HALLAZGOS (Findings) ---
    // Ruta para guardar un nuevo hallazgo para una auditoría específica
    Route::post('/audits/{audit}/findings', [FindingController::class, 'store'])->name('audits.findings.store');
    // --- ACCIONES CORRECTIVAS (Corrective Actions) ---
    // Ruta para guardar una nueva acción correctiva para un hallazgo
    Route::post('/findings/{finding}/corrective-actions', [CorrectiveActionController::class, 'store'])->name('findings.corrective-actions.store');

    // --- SUB-MÓDULO: ACREDITACIONES ---
    Route::get('/accreditations', [AccreditationController::class, 'index'])->name('accreditations.index');
    Route::get('/accreditations/create', [AccreditationController::class, 'create'])->name('accreditations.create');
    Route::post('/accreditations', [AccreditationController::class, 'store'])->name('accreditations.store');
    Route::get('/accreditations/{accreditation}/edit', [AccreditationController::class, 'edit'])->name('accreditations.edit');
    Route::put('/accreditations/{accreditation}', [AccreditationController::class, 'update'])->name('accreditations.update');
    Route::delete('/accreditations/{accreditation}', [AccreditationController::class, 'destroy'])->name('accreditations.destroy');

    // --- SUB-MÓDULO: ENCUESTAS ---
    Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys.index');
    Route::get('/surveys/create', [SurveyController::class, 'create'])->name('surveys.create');
    Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');
    // La ruta 'design' ahora usa el modelo Survey automáticamente
    Route::get('/surveys/{survey}/design', [SurveyController::class, 'design'])->name('surveys.design');
    // Ruta para GUARDAR una nueva pregunta para una encuesta específica
    Route::post('/surveys/{survey}/questions', [SurveyQuestionController::class, 'store'])->name('surveys.questions.store');
    // Ruta para ELIMINAR una pregunta específica
    Route::delete('/surveys/{survey}/questions/{question}', [SurveyQuestionController::class, 'destroy'])->name('surveys.questions.destroy');
    // Ruta para MOSTRAR el formulario de edición de pregunta
    Route::get('/surveys/{survey}/questions/{question}/edit', [SurveyQuestionController::class, 'edit'])->name('surveys.questions.edit'); 
    // Ruta para ACTUALIZAR una pregunta existente
    Route::put('/surveys/{survey}/questions/{question}', [SurveyQuestionController::class, 'update'])->name('surveys.questions.update'); 
    // Ruta para ELIMINAR una encuesta completa
    Route::delete('/surveys/{survey}', [SurveyController::class, 'destroy'])->name('surveys.destroy');
    Route::get('/surveys/{survey}/edit', [SurveyController::class, 'edit'])->name('surveys.edit'); 
    // Ruta para ACTUALIZAR la encuesta
    Route::put('/surveys/{survey}', [SurveyController::class, 'update'])->name('surveys.update');
    // Ruta para MOSTRAR la interfaz de asignación de una encuesta
    Route::get('/surveys/{survey}/assign', [SurveyController::class, 'showAssignForm'])->name('surveys.assign.show');
    Route::post('/surveys/{survey}/assign', [SurveyController::class, 'storeAssignments'])->name('surveys.assign.store');

    // --- SUB-MÓDULO: CRITERIOS DE EVALUACIÓN ---
    Route::get('/evaluation-criteria', [EvaluationCriterionController::class, 'index'])->name('evaluation-criteria.index');
    Route::get('/evaluation-criteria/create', [EvaluationCriterionController::class, 'create'])->name('evaluation-criteria.create');
    Route::post('/evaluation-criteria', [EvaluationCriterionController::class, 'store'])->name('evaluation-criteria.store');
    Route::get('/evaluation-criteria/{criterion}/edit', [EvaluationCriterionController::class, 'edit'])->name('evaluation-criteria.edit');
    Route::put('/evaluation-criteria/{criterion}', [EvaluationCriterionController::class, 'update'])->name('evaluation-criteria.update');
    Route::delete('/evaluation-criteria/{criterion}', [EvaluationCriterionController::class, 'destroy'])->name('evaluation-criteria.destroy');
    // Ruta para ELIMINAR un criterio
    Route::delete('/evaluation-criteria/{criterion}', [EvaluationCriterionController::class, 'destroy'])->name('evaluation-criteria.destroy');

});





Route::get('/debug/roles', function () {
    $u = auth()->user();
    return response()->json([
        'user_id' => $u?->id,
        'raw_roles' => $u->roles ?? $u->role ?? null,
        'as_array' => is_array($u->roles ?? $u->role ?? null),
    ]);
})->middleware('auth');
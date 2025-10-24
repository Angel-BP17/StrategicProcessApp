<?php

use App\Http\Controllers\Collaboration\ChannelController;
use App\Http\Controllers\Collaboration\CollaborationController;
use App\Http\Controllers\Collaboration\MessageController;
use App\Http\Controllers\Collaboration\SearchController;
use App\Http\Controllers\Collaboration\TaskController;
use App\Http\Controllers\Collaboration\TeamController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Documentation\DocumentController;
use App\Http\Controllers\Planning\KpiController;
use App\Http\Controllers\Planning\PlanningController;
use App\Http\Controllers\Planning\StrategicObjectiveController;
use App\Http\Controllers\Planning\StrategicPlanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AllianceController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\AgreementController;


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

    Route::prefix('documents')->name('documents.')->group(function () {
        Route::middleware(['role:any,admin,quality_manager,auditor,consultant'])->group(function () {
            Route::get('/', [DocumentController::class, 'index'])->name('index');
            Route::get('/{document}/versions/{version}/download', [DocumentVersionController::class, 'download'])
                ->whereNumber('document')
                ->whereNumber('version')
                ->name('versions.download');
            Route::get('/{document}', [DocumentController::class, 'show'])
                ->whereNumber('document')
                ->name('show');
        });

        Route::middleware(['role:any,admin,quality_manager'])->group(function () {
            Route::get('/create', [DocumentController::class, 'create'])->name('create');
            Route::post('/', [DocumentController::class, 'store'])->name('store');
            Route::get('/{document}/edit', [DocumentController::class, 'edit'])
                ->whereNumber('document')
                ->name('edit');
            Route::put('/{document}', [DocumentController::class, 'update'])
                ->whereNumber('document')
                ->name('update');
            Route::delete('/{document}', [DocumentController::class, 'destroy'])
                ->whereNumber('document')
                ->name('destroy');
            Route::post('/{document}/versions', [DocumentVersionController::class, 'store'])
                ->whereNumber('document')
                ->name('versions.store');
            Route::post('/{document}/evidences', [EvidenceController::class, 'store'])
                ->whereNumber('document')
                ->name('evidences.store');
            Route::delete('/{document}/evidences/{evidence}', [EvidenceController::class, 'destroy'])
                ->whereNumber('document')
                ->whereNumber('evidence')
                ->name('evidences.destroy');
        });
    });
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
        Route::delete('/{kpi}/measurements', [KpiController::class, 'deleteMeasurement'])->name('measurements.destroy');
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
    Route::delete('/audits/{audit}', [AuditController::class, 'destroy'])->name('audits.destroy');
    Route::put('/audits/{audit}', [AuditController::class, 'update'])->name('audits.update');
    Route::get('/audits/{audit}/edit', [AuditController::class, 'edit'])->name('audits.edit');
    // --- HALLAZGOS (Findings) ---
    Route::post('/audits/{audit}/findings', [FindingController::class, 'store'])->name('audits.findings.store');
    Route::delete('/findings/{finding}', [FindingController::class, 'destroy'])->name('findings.destroy');
    Route::get('/findings/{finding}/edit', [FindingController::class, 'edit'])->name('findings.edit');
    Route::put('/findings/{finding}', [FindingController::class, 'update'])->name('findings.update');
    // --- ACCIONES CORRECTIVAS (Corrective Actions) ---
    Route::post('/findings/{finding}/corrective-actions', [CorrectiveActionController::class, 'store'])->name('findings.corrective-actions.store');
    Route::delete('/corrective-actions/{action}', [CorrectiveActionController::class, 'destroy'])->name('corrective-actions.destroy');
    Route::get('/corrective-actions/{action}/edit', [CorrectiveActionController::class, 'edit'])->name('corrective-actions.edit');
    Route::put('/corrective-actions/{action}', [CorrectiveActionController::class, 'update'])->name('corrective-actions.update');
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
    Route::post('/surveys/{survey}/questions', [SurveyQuestionController::class, 'store'])->name('surveys.questions.store');
    Route::delete('/surveys/{survey}/questions/{question}', [SurveyQuestionController::class, 'destroy'])->name('surveys.questions.destroy');
    Route::get('/surveys/{survey}/questions/{question}/edit', [SurveyQuestionController::class, 'edit'])->name('surveys.questions.edit');
    Route::put('/surveys/{survey}/questions/{question}', [SurveyQuestionController::class, 'update'])->name('surveys.questions.update');
    Route::delete('/surveys/{survey}', [SurveyController::class, 'destroy'])->name('surveys.destroy');
    Route::get('/surveys/{survey}/edit', [SurveyController::class, 'edit'])->name('surveys.edit');
    Route::put('/surveys/{survey}', [SurveyController::class, 'update'])->name('surveys.update');
    Route::get('/surveys/{survey}/assign', [SurveyController::class, 'showAssignForm'])->name('surveys.assign.show');
    Route::post('/surveys/{survey}/assign', [SurveyController::class, 'storeAssignments'])->name('surveys.assign.store');

    // --- SUB-MÓDULO: CRITERIOS DE EVALUACIÓN ---
    Route::get('/evaluation-criteria', [EvaluationCriterionController::class, 'index'])->name('evaluation-criteria.index');
    Route::get('/evaluation-criteria/create', [EvaluationCriterionController::class, 'create'])->name('evaluation-criteria.create');
    Route::post('/evaluation-criteria', [EvaluationCriterionController::class, 'store'])->name('evaluation-criteria.store');
    Route::get('/evaluation-criteria/{criterion}/edit', [EvaluationCriterionController::class, 'edit'])->name('evaluation-criteria.edit');
    Route::put('/evaluation-criteria/{criterion}', [EvaluationCriterionController::class, 'update'])->name('evaluation-criteria.update');
    Route::delete('/evaluation-criteria/{criterion}', [EvaluationCriterionController::class, 'destroy'])->name('evaluation-criteria.destroy');
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

Route::middleware(['auth'])->group(function () {
    // Vista general
    Route::get('/alliances', [AllianceController::class, 'index'])->name('alliances.index');

    // CRUD de Socios
    Route::resource('partners', PartnerController::class);

    // CRUD de Convenios
    Route::resource('agreements', AgreementController::class);
});

use App\Http\Controllers\InnovacionMejoraContinua\InnovacionMejoraController;
use App\Http\Controllers\InnovacionMejoraContinua\InitiativeController;
use App\Http\Controllers\InnovacionMejoraContinua\InitiativeEvaluationController;

Route::middleware(['auth', 'role:any,admin,planner'])->prefix('innovacion-mejora-continua')->name('innovacion-mejora-continua.')->group(function () {

    // Home del módulo de Innovación y Mejora Continua
    Route::get('/', [InnovacionMejoraController::class, 'index'])->name('index');

    // Iniciativas (Gestión de iniciativas)
    Route::prefix('initiatives')->name('initiatives.')->group(function () {
        Route::get('/', [InitiativeController::class, 'index'])->name('index');           // listado de iniciativas
        Route::get('/create', [InitiativeController::class, 'create'])->name('create');   // formulario crear
        Route::post('/', [InitiativeController::class, 'store'])->name('store');          // guardar
        Route::get('/{initiative}', [InitiativeController::class, 'show'])->name('show'); // ver detalle
        Route::get('/{initiative}/edit', [InitiativeController::class, 'edit'])->name('edit'); // formulario editar
        Route::put('/{initiative}', [InitiativeController::class, 'update'])->name('update');  // actualizar
        Route::delete('/{initiative}', [InitiativeController::class, 'destroy'])->name('destroy'); // eliminar

        // Evaluaciones de iniciativas (por iniciativa)
        Route::prefix('{initiative}/evaluations')->name('evaluations.')->group(function () {
            Route::get('/', [InitiativeEvaluationController::class, 'index'])->name('index');        // listado
            Route::get('/create', [InitiativeEvaluationController::class, 'create'])->name('create'); // formulario
            Route::post('/', [InitiativeEvaluationController::class, 'store'])->name('store');       // guardar
            Route::get('/{evaluation}', [InitiativeEvaluationController::class, 'show'])->name('show'); // detalle
            Route::get('/{evaluation}/edit', [InitiativeEvaluationController::class, 'edit'])->name('edit'); // editar
            Route::put('/{evaluation}', [InitiativeEvaluationController::class, 'update'])->name('update'); // actualizar
            Route::delete('/{evaluation}', [InitiativeEvaluationController::class, 'destroy'])->name('destroy'); // eliminar
        });
    });

    // Dashboards del módulo (opcional)
    Route::prefix('dashboards')->name('dashboards.')->group(function () {
        Route::get('/', [InnovacionMejoraController::class, 'dashboard'])->name('index');
    });
});

/*
|--------------------------------------------------------------------------
| MÓDULO: COLABORACIÓN Y COMUNICACIÓN DIGITAL
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/collaboration', [CollaborationController::class, 'index'])->name('collab.index');

    // Equipos
    Route::post('/collaboration/teams', [TeamController::class, 'store'])->name('collab.teams.store');
    Route::post('/collaboration/teams/{team}/join', [TeamController::class, 'join'])->name('collab.teams.join');
    Route::post('/collaboration/teams/{team}/leave', [TeamController::class, 'leave'])->name('collab.teams.leave');

    // Canales
    Route::get('/collaboration/channels/create', [ChannelController::class, 'create'])->name('collab.channels.create');
    Route::post('/collaboration/channels', [ChannelController::class, 'store'])->name('collab.channels.store');
    Route::post('/collaboration/channels/{channel}/join', [ChannelController::class, 'join'])->name('collab.channels.join');
    Route::post('/collaboration/channels/{channel}/leave', [ChannelController::class, 'leave'])->name('collab.channels.leave');
    // Moderación (bloquear/desbloquear)
    Route::post('/collaboration/channels/{channel}/ban/{user}', [ChannelController::class, 'ban'])->name('collab.channels.ban');
    Route::post('/collaboration/channels/{channel}/unban/{user}', [ChannelController::class, 'unban'])->name('collab.channels.unban');

    // Mensajes (con adjuntos)
    Route::post('/collaboration/channels/{channel}/messages', [MessageController::class, 'store'])->name('collab.messages.store');
    Route::delete('/collaboration/messages/{message}', [MessageController::class, 'destroy'])->name('collab.messages.destroy');
    Route::post('/collaboration/messages/{message}/pin', [MessageController::class, 'pin'])->name('collab.messages.pin');
    Route::post('/collaboration/messages/{message}/report', [MessageController::class, 'report'])->name('collab.messages.report');

    // Tareas
    Route::post('/collaboration/channels/{channel}/tasks', [TaskController::class, 'store'])->name('collab.tasks.store');
    Route::patch('/collaboration/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('collab.tasks.toggle');
    Route::delete('/collaboration/tasks/{task}', [TaskController::class, 'destroy'])->name('collab.tasks.destroy');

    // Búsqueda
    Route::get('/collaboration/search', [SearchController::class, 'index'])->name('collab.search');
});


/*
|--------------------------------------------------------------------------
| MÓDULO: DOCUMENTACIÓN Y EVIDENCIAS
|--------------------------------------------------------------------------
*/

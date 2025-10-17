<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\Planning\PlanningController;
use App\Http\Controllers\Planning\StrategicObjectiveController;
use App\Http\Controllers\Planning\StrategicPlanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

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

Route::get('/debug/roles', function () {
    $u = auth()->user();
    return response()->json([
        'user_id' => $u?->id,
        'raw_roles' => $u->roles ?? $u->role ?? null,
        'as_array' => is_array($u->roles ?? $u->role ?? null),
    ]);
})->middleware('auth');
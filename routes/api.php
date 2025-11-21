<?php

use App\Http\Controllers\AgreementController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\ConversationUserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MessageFileController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\StrategicDocumentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Strategic\GoalController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('strategic-contents', StrategicContentController::class);
    Route::apiResource('organizations', OrganizationController::class);
    Route::apiResource('agreements', AgreementController::class);
    Route::apiResource('strategic-documents', StrategicDocumentController::class);
    Route::apiResource('conversations', ConversationController::class);
    Route::apiResource('conversation-users', ConversationUserController::class);
    Route::apiResource('messages', MessageController::class);
    Route::apiResource('message-files', MessageFileController::class);
    Route::prefix('strategic')->group(function () {
        Route::get('/goals', [GoalController::class, 'index']); // Ver tarjetas
        Route::post('/goals/{id}/rate', [GoalController::class, 'rate']); // Votar
    });
});

Route::apiResource('candidates', CandidateController::class)->except(['update', 'edit']);

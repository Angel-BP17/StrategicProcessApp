<?php

use App\Http\Controllers\AgreementController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MessageFileController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\StrategicContentController; 
use App\Http\Controllers\StrategicDocumentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user || !\Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('auth-token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token,
        'token_type' => 'Bearer'
    ]);
});

Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logged out successfully']);
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('strategic-contents', StrategicContentController::class);
    Route::apiResource('organizations', OrganizationController::class);
    Route::apiResource('agreements', AgreementController::class);
    Route::apiResource('strategic-documents', StrategicDocumentController::class);
    Route::apiResource('conversations', ConversationController::class);
    Route::apiResource('messages', MessageController::class);
    Route::apiResource('message-files', MessageFileController::class);
});

Route::apiResource('candidates', CandidateController::class)->except(['update', 'edit']);

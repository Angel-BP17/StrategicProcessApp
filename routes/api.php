<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/tablas', function () {
    $tablas = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public';");
    return response()->json($tablas);
});
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

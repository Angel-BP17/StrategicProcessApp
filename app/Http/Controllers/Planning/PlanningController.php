<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;

class PlanningController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Bienvenido al módulo de planificación.',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller; // <-- Importante
use Illuminate\Http\Request;

class QualityDashboardController extends Controller
{
    /**
     * Muestra el dashboard principal del Módulo de Calidad.
     */
    public function index()
    {
        // Por ahora, solo mostramos la vista.
        // En el futuro, aquí puedes cargar KPIs o resúmenes.
        return view('quality.index');
    }
}
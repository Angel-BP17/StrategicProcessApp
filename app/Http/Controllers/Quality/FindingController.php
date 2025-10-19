<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Quality\Audit; 
use App\Models\Quality\Finding; 
use Illuminate\Http\Request;

class FindingController extends Controller
{
    /**
     * Guarda un nuevo hallazgo para una auditoría específica.
     */
    public function store(Request $request, Audit $audit)
    {
        // 1. Validamos los datos del formulario
        $validatedData = $request->validate([
            'description' => 'required|string',
            'classification' => 'required|string|max:255',
            'severity' => 'required|in:low,medium,high',
            'discovery_date' => 'required|date',
            'evidence' => 'nullable|string', // 'evidence' es opcional
        ]);

        // 2. Asignamos el 'audit_id' automáticamente
        $validatedData['audit_id'] = $audit->id;

        // 3. Creamos el hallazgo
        Finding::create($validatedData);

        // 4. Redirigimos de vuelta a la página de detalle de la auditoría
        return redirect()->route('quality.audits.show', $audit)->with('success', 'Hallazgo registrado con éxito.');
    }
}
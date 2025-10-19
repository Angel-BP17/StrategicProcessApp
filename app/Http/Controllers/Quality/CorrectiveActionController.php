<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Quality\Finding; // Importamos Finding
use App\Models\Quality\CorrectiveAction; // Importamos CorrectiveAction
use App\Models\User; // Importamos User
use Illuminate\Http\Request;

class CorrectiveActionController extends Controller
{
    /**
     * Guarda una nueva acción correctiva para un hallazgo.
     */
    public function store(Request $request, Finding $finding)
    {
        // 1. Validamos los datos
        $validatedData = $request->validate([
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id', // Validamos que el ID del usuario exista
            'due_date' => 'required|date',
        ]);

        // 2. Asignamos el 'finding_id' y el estado por defecto
        $validatedData['finding_id'] = $finding->id;
        $validatedData['status'] = 'pending'; // Toda nueva acción empieza 'pending'

        // 3. Creamos la acción
        CorrectiveAction::create($validatedData);

        // 4. Redirigimos de vuelta a la página de detalle de la auditoría
        return redirect()->route('quality.audits.show', $finding->audit_id)
                         ->with('success', 'Acción correctiva registrada con éxito.');
    }
}
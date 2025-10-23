<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Quality\Finding; 
use App\Models\Quality\CorrectiveAction; 
use App\Models\User;
use Illuminate\Http\Request;

class CorrectiveActionController extends Controller
{
    
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
                         ->with('success', 'Acción correctiva registrada con éxito.')->withFragment('correctiveActionsSection');
    }

    
    public function destroy(CorrectiveAction $action) // Laravel inyecta el modelo automáticamente
    {
        
        $auditId = $action->finding->audit_id; // Accedemos al audit_id a través de la relación finding()

        // Eliminamos la acción correctiva
        $action->delete();

        // Redirigimos de vuelta a la página de detalle de la auditoría original
        return redirect()->route('quality.audits.show', $auditId)
                        ->with('success', 'Acción correctiva eliminada con éxito.')->withFragment('correctiveActionsSection');
    }


    
    public function edit(CorrectiveAction $action)
    {
        // Cargamos la lista de usuarios para el <select> de responsable
        $users = User::orderBy('full_name')->get();
        // Cargamos la relación con el hallazgo para mostrar contexto (opcional)
        $action->load('finding');

        return view('quality.audits.corrective-actions.edit', [ // Crearemos esta vista
            'action' => $action,
            'users' => $users,
            'finding' => $action->finding // Pasamos el hallazgo para contexto
        ]);
    }

    public function update(Request $request, CorrectiveAction $action)
    {
        // 1. Validamos los datos (similar a 'store', añadimos 'status')
        $validatedData = $request->validate([
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled', // Permitir cambiar estado
            // Podríamos añadir validación para engagement_date y completion_date si los incluimos en el form
            'engagement_date' => 'nullable|date',
            'completion_date' => 'nullable|date|after_or_equal:engagement_date',
        ]);

        // Si el estado es 'completed', registrar la fecha de completado automáticamente
        if ($validatedData['status'] === 'completed' && empty($validatedData['completion_date'])) {
             $validatedData['completion_date'] = now();
        } elseif ($validatedData['status'] !== 'completed') {
             $validatedData['completion_date'] = null; // Limpiar fecha si ya no está completado
        }

        // 2. Actualizamos el registro
        $action->update($validatedData);

        // 3. Redirigimos de vuelta a la página de detalle de la auditoría original
        return redirect()->route('quality.audits.show', $action->finding->audit_id)
                         ->with('success', 'Acción correctiva actualizada con éxito.');
    }


}
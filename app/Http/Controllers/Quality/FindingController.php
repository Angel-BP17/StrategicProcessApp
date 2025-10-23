<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Quality\Audit; 
use App\Models\Quality\Finding; 
use Illuminate\Http\Request;

class FindingController extends Controller
{
    
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
        return redirect()->route('quality.audits.show', $audit)->with('success', 'Hallazgo registrado con éxito.')->withFragment('findingsSection');
    }

   
    public function destroy(Finding $finding)
    {
        // Guardamos el ID de la auditoría a la que pertenece ANTES de borrarlo
        $auditId = $finding->audit_id;

        // Borrar acciones correctivas asociadas PRIMERO
        // (Si la FK en corrective_actions tiene ON DELETE CASCADE, esto es opcional)
        $finding->correctiveActions()->delete();

        // Eliminar el hallazgo
        $finding->delete();

        // Redirigir de vuelta a la página de detalle de la auditoría original
        return redirect()->route('quality.audits.show', $auditId)
                        ->with('success', 'Hallazgo eliminado con éxito.')->withFragment('findingsSection');
    }


    /**
     * Muestra el formulario para editar un hallazgo específico.
     */
    public function edit(Finding $finding)
    {
        // Pasamos el hallazgo a editar a la nueva vista
        return view('quality.audits.findings.edit', [ // Crearemos esta vista
            'finding' => $finding
        ]);
    }

    /**
     * Actualiza el hallazgo especificado en la base de datos.
     */
    public function update(Request $request, Finding $finding)
    {
        // 1. Validamos los datos (similar a 'store')
        $validatedData = $request->validate([
            'description' => 'required|string',
            'classification' => 'required|string|max:255',
            'severity' => 'required|in:low,medium,high',
            'discovery_date' => 'required|date',
            'evidence' => 'nullable|string',
        ]);

        // 2. Actualizamos el registro del hallazgo
        $finding->update($validatedData);

        // 3. Redirigimos de vuelta a la página de detalle de la auditoría original
        return redirect()->route('quality.audits.show', $finding->audit_id)
                         ->with('success', 'Hallazgo actualizado con éxito.');
    }


}
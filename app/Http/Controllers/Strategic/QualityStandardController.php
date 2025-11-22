<?php

namespace App\Http\Controllers\Strategic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// Importamos los modelos del Core Domain (Namespace corregido según tu composer.json)
use IncadevUns\CoreDomain\Models\QualityStandard;
use IncadevUns\CoreDomain\Models\StandardRating;

class QualityStandardController extends Controller
{
    /**
     * LISTAR ESTÁNDARES (Endpoint: GET /api/strategic/quality-standards)
     */
    public function index()
    {
        $user = auth()->user();
        
        // Obtenemos el rol del usuario usando Spatie (del Core)
        // getRoleNames() devuelve una colección, tomamos el primero
        $userRole = $user->getRoleNames()->first(); 

        // Consultamos los estándares activos filtrando por "Target Roles"
        $standards = QualityStandard::where('is_active', true)
            ->where(function($query) use ($userRole) {
                // Lógica: Trae el estándar SI la columna 'target_roles' contiene mi rol
                // O SI es para todos (null)
                $query->whereJsonContains('target_roles', $userRole)
                      ->orWhereNull('target_roles');
            })
            ->withCount('ratings') // Para saber cuántos han votado (opcional)
            ->get()
            // Activamos el atributo calculado "current_score" del Modelo
            ->append('current_score'); 

        return response()->json([
            'success' => true,
            'data' => $standards
        ]);
    }

    /**
     * VOTAR (Endpoint: POST /api/strategic/quality-standards/{id}/rate)
     */
    public function rate(Request $request, $id)
    {
        // 1. Validación
        $request->validate([
            'score' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        // 2. Guardar o Actualizar voto
        StandardRating::updateOrCreate(
            [
                'quality_standard_id' => $id,
                'user_id' => auth()->id()
            ],
            [
                'score' => $request->score,
                'comment' => $request->comment
            ]
        );

        return response()->json([
            'success' => true, 
            'message' => '¡Gracias! Tu evaluación de calidad ha sido registrada.'
        ]);
    }

    // --- MÉTODOS DE GESTIÓN (PARA EL ADMINISTRADOR) ---

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string', // Ej: "Infraestructura"
            'target_score' => 'required|numeric|min:1|max:5',
            'target_roles' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $standard = QualityStandard::create($validated);

        return response()->json(['success' => true, 'data' => $standard]);
    }

    public function update(Request $request, $id)
    {
        $standard = QualityStandard::findOrFail($id);
        $standard->update($request->all());
        return response()->json(['success' => true, 'message' => 'Estándar actualizado']);
    }

    public function destroy($id)
    {
        $standard = QualityStandard::findOrFail($id);
        $standard->delete();
        return response()->json(['success' => true, 'message' => 'Estándar eliminado']);
    }
}
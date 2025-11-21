<?php

namespace App\Http\Controllers\Strategic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// Importamos los modelos que le pediste al encargado del Core
use Incadev\CoreDomain\Models\StrategicGoal;
use Incadev\CoreDomain\Models\GoalRating;

class GoalController extends Controller
{
    /**
     * LISTAR METAS (Endpoint: GET /api/strategic/goals)
     * Muestra las tarjetas disponibles para el usuario logueado.
     */
    public function index()
    {
        $user = auth()->user();
        
        // 1. Obtenemos el rol del usuario (ej. 'student', 'admin')
        // Si usan Spatie, esto devuelve el primer rol.
        $userRole = $user->getRoleNames()->first(); 

        // 2. Consultamos las metas activas filtrando por "Target"
        $goals = StrategicGoal::where('is_active', true)
            ->where(function($query) use ($userRole) {
                // Lógica: Trae la meta SI la columna 'target_roles' contiene mi rol
                $query->whereJsonContains('target_roles', $userRole)
                      // O SI es para todos (null)
                      ->orWhereNull('target_roles');
            })
            ->withCount('ratings') // Opcional: para saber cuántos han votado
            ->get()
            // Esto activa el cálculo del promedio en el Modelo (ver paso anterior)
            ->append('current_average'); 

        return response()->json([
            'success' => true,
            'data' => $goals
        ]);
    }

    /**
     * VOTAR (Endpoint: POST /api/strategic/goals/{id}/rate)
     * Guarda las estrellitas que el usuario manda.
     */
    public function rate(Request $request, $id)
    {
        // 1. Validamos que nos manden datos correctos
        $request->validate([
            'score' => 'required|integer|min:1|max:5', // De 1 a 5 estrellas
            'comment' => 'nullable|string|max:500'
        ]);

        // 2. Guardamos o actualizamos el voto
        // updateOrCreate es mágico: Si ya voté, actualiza mi voto. Si no, crea uno nuevo.
        GoalRating::updateOrCreate(
            [
                'strategic_goal_id' => $id,
                'user_id' => auth()->id()
            ],
            [
                'score' => $request->score,
                'comment' => $request->comment
            ]
        );

        return response()->json([
            'success' => true, 
            'message' => '¡Gracias! Tu opinión ha sido registrada.'
        ]);
    }
}
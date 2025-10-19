<?php

namespace App\Http\Controllers\InnovacionMejoraContinua;

use App\Http\Controllers\Controller;
use App\Models\InnovacionMejoraContinua\Initiative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InitiativeController extends Controller
{
    // Listar todas las iniciativas
    public function index(Request $request)
    {
        $query = Initiative::with(['responsibleUser', 'responsibleTeam', 'evaluations']);

        // Filtro por búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'ILIKE', "%{$search}%")
                  ->orWhere('summary', 'ILIKE', "%{$search}%")
                  ->orWhere('description', 'ILIKE', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por usuario responsable
        if ($request->filled('responsible_user_id')) {
            $query->where('responsible_user_id', $request->responsible_user_id);
        }

        // Filtro por equipo responsable
        if ($request->filled('responsible_team_id')) {
            $query->where('responsible_team_id', $request->responsible_team_id);
        }

        $initiatives = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('innovacion_mejora_continua.initiatives.index', compact('initiatives'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        // Obtener usuarios y equipos para los selects
        $users = \App\Models\User::orderBy('name')->get();
        $teams = \App\Models\Team::orderBy('name')->get();
        
        return view('innovacion_mejora_continua.initiatives.create', compact('users', 'teams'));
    }

    // Guardar nueva iniciativa
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'description' => 'nullable|string',
            'responsible_team_id' => 'nullable|exists:teams,id',
            'responsible_user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:propuesta,evaluada,aprobada,implementada,cerrada',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'estimated_impact' => 'nullable|string|max:255'
        ], [
            'plan_id.required' => 'El ID del plan es obligatorio',
            'title.required' => 'El título es obligatorio',
            'summary.required' => 'El resumen es obligatorio',
            'end_date.after_or_equal' => 'La fecha de fin debe ser posterior a la fecha de inicio',
            'status.in' => 'El estado seleccionado no es válido'
        ]);

        $initiative = Initiative::create($validated);

        return redirect()
            ->route('innovacion-mejora-continua.initiatives.show', $initiative)
            ->with('success', 'Iniciativa creada exitosamente');
    }

    // Mostrar detalle de iniciativa
    public function show(Initiative $initiative)
    {
        $initiative->load([
            'responsibleUser', 
            'responsibleTeam', 
            'evaluations' => function($query) {
                $query->orderBy('evaluation_date', 'desc');
            },
            'evaluations.evaluator'
        ]);

        return view('innovacion_mejora_continua.initiatives.show', compact('initiative'));
    }

    // Mostrar formulario de edición
    public function edit(Initiative $initiative)
    {
        // Obtener usuarios y equipos para los selects
        $users = \App\Models\User::orderBy('name')->get();
        $teams = \App\Models\Team::orderBy('name')->get();
        
        return view('innovacion_mejora_continua.initiatives.edit', compact('initiative', 'users', 'teams'));
    }

    // Actualizar iniciativa
    public function update(Request $request, Initiative $initiative)
    {
        $validated = $request->validate([
            'plan_id' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'description' => 'nullable|string',
            'responsible_team_id' => 'nullable|exists:teams,id',
            'responsible_user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:propuesta,evaluada,aprobada,implementada,cerrada',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'estimated_impact' => 'nullable|string|max:255'
        ], [
            'plan_id.required' => 'El ID del plan es obligatorio',
            'title.required' => 'El título es obligatorio',
            'summary.required' => 'El resumen es obligatorio',
            'end_date.after_or_equal' => 'La fecha de fin debe ser posterior a la fecha de inicio',
            'status.in' => 'El estado seleccionado no es válido'
        ]);

        $initiative->update($validated);

        return redirect()
            ->route('innovacion-mejora-continua.initiatives.show', $initiative)
            ->with('success', 'Iniciativa actualizada exitosamente');
    }

    // Eliminar iniciativa (soft delete)
    public function destroy(Initiative $initiative)
    {
        // Verificar si tiene evaluaciones antes de eliminar
        if ($initiative->evaluations()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'No se puede eliminar una iniciativa que tiene evaluaciones registradas');
        }

        $initiative->delete();

        return redirect()
            ->route('innovacion-mejora-continua.initiatives.index')
            ->with('success', 'Iniciativa eliminada exitosamente');
    }
}

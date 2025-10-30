<?php

namespace App\Http\Controllers\InnovacionMejoraContinua;

use App\Http\Controllers\Controller;
use App\Models\Collaboration\Team;
use App\Models\InnovacionMejoraContinua\Initiative;
use App\Models\Planning\StrategicPlan;
use App\Models\User;
use Illuminate\Http\Request;

class InitiativeController extends Controller
{
    public function index(Request $request)
    {
        $query = Initiative::with(['responsibleUser', 'responsibleTeam', 'evaluations']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ILIKE', "%{$search}%")
                    ->orWhere('summary', 'ILIKE', "%{$search}%")
                    ->orWhere('description', 'ILIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('responsible_user_id')) {
            $query->where('responsible_user_id', $request->responsible_user_id);
        }

        if ($request->filled('responsible_team_id')) {
            $query->where('responsible_team_id', $request->responsible_team_id);
        }

        $initiatives = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'data' => $initiatives,
        ]);
    }

    public function create()
    {
        $users = User::orderBy('full_name')->get();
        $teams = Team::orderBy('name')->get();
        $plans = StrategicPlan::orderBy('title')->get();

        return response()->json([
            'users' => $users,
            'teams' => $teams,
            'plans' => $plans,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'responsible_team_id' => 'nullable|exists:teams,id',
            'responsible_user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:propuesta,evaluada,aprobada,implementada,cerrada',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'estimated_impact' => 'nullable|string|max:255',
        ]);

        $initiative = Initiative::create($validated);

        return response()->json([
            'message' => 'Iniciativa creada exitosamente.',
            'data' => $initiative,
        ], 201);
    }

    public function show(Initiative $initiative)
    {
        $initiative->load([
            'responsibleUser',
            'responsibleTeam',
            'evaluations' => function ($query) {
                $query->orderBy('evaluation_date', 'desc');
            },
            'evaluations.evaluator',
        ]);

        return response()->json([
            'initiative' => $initiative,
        ]);
    }

    public function edit(Initiative $initiative)
    {
        $users = User::orderBy('full_name')->get();
        $teams = Team::orderBy('name')->get();
        $plans = StrategicPlan::orderBy('title')->get();

        return response()->json([
            'initiative' => $initiative,
            'users' => $users,
            'teams' => $teams,
            'plans' => $plans,
        ]);
    }

    public function update(Request $request, Initiative $initiative)
    {
        $validated = $request->validate([
            'plan_id' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'responsible_team_id' => 'nullable|exists:teams,id',
            'responsible_user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:propuesta,evaluada,aprobada,implementada,cerrada',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'estimated_impact' => 'nullable|string|max:255',
        ]);

        $initiative->update($validated);

        return response()->json([
            'message' => 'Iniciativa actualizada exitosamente.',
            'data' => $initiative->fresh(),
        ]);
    }

    public function destroy(Initiative $initiative)
    {
        if ($initiative->evaluations()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar una iniciativa que tiene evaluaciones registradas.',
            ], 422);
        }

        $initiative->delete();

        return response()->json([
            'message' => 'Iniciativa eliminada exitosamente.',
        ]);
    }
}

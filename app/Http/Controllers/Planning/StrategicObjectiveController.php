<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use App\Http\Requests\Planning\StrategicObjectives\CreateStrategicObjectiveRequest;
use App\Http\Requests\Planning\StrategicObjectives\UpdateStrategicObjectiveRequest;
use App\Models\Planning\StrategicObjective;
use App\Models\Planning\StrategicPlan;
use App\Models\User;

class StrategicObjectiveController extends Controller
{
    public function create(StrategicPlan $plan)
    {
        $this->authorize('objective.manage');
        $users = User::orderBy('full_name')->get(['id', 'full_name']);

        return response()->json([
            'plan' => $plan,
            'users' => $users,
        ]);
    }

    public function store(CreateStrategicObjectiveRequest $request)
    {
        $objective = StrategicObjective::create($request->validated());

        return response()->json([
            'message' => 'Objetivo creado.',
            'data' => $objective,
        ], 201);
    }

    public function show(StrategicPlan $plan, StrategicObjective $objective)
    {
        $objective->load(['plan', 'kpis.measurements' => fn ($q) => $q->orderBy('measured_at')]);
        $firstKpi = $objective->kpis->first();

        $chart = [
            'labels' => $firstKpi?->measurements->sortBy('measured_at')->pluck('measured_at')->values() ?? [],
            'values' => $firstKpi?->measurements->sortBy('measured_at')->pluck('value')->values() ?? [],
            'target' => $firstKpi?->target_value ?? null,
            'unit' => $firstKpi?->unit ?? '',
            'kpiName' => $firstKpi?->name ?? 'KPI',
        ];

        return response()->json([
            'objective' => $objective,
            'chart' => $chart,
            'plan' => $objective->plan,
        ]);
    }

    public function edit(StrategicPlan $plan, StrategicObjective $objective)
    {
        $this->authorize('objective.manage');
        $users = User::orderBy('full_name')->get(['id', 'full_name']);

        return response()->json([
            'plan' => $plan,
            'objective' => $objective,
            'users' => $users,
        ]);
    }

    public function update(UpdateStrategicObjectiveRequest $request, StrategicPlan $plan, StrategicObjective $objective)
    {
        $objective->update($request->validated());

        return response()->json([
            'message' => 'Objetivo actualizado.',
            'data' => $objective->fresh(),
        ]);
    }

    public function destroy(StrategicPlan $plan, StrategicObjective $objective)
    {
        $this->authorize('objective.manage');

        if ($objective->kpis()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar: el objetivo tiene KPIs asociados.',
            ], 422);
        }

        $objective->delete();

        return response()->json([
            'message' => 'Objetivo eliminado.',
        ]);
    }
}

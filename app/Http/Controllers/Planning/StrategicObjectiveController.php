<?php

namespace App\Http\Controllers\Planning;

use App\Http\Requests\Planning\StrategicObjectives\CreateStrategicObjectiveRequest;
use App\Http\Requests\Planning\StrategicObjectives\UpdateStrategicObjectiveRequest;
use App\Models\Planning\StrategicObjective;
use App\Models\Planning\StrategicPlan;
use App\Http\Controllers\Controller;
use App\Models\User;

class StrategicObjectiveController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create(StrategicPlan $plan)
    {
        $this->authorize('objective.manage');
        $users = User::orderBy('name')->get(['id', 'name']);
        return view('planning.objectives.create', compact('plan', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateStrategicObjectiveRequest $request)
    {
        StrategicObjective::create($request->validated());
        return redirect()->route('planning.plans.show', $request->input('plan_id'))->with('ok', 'Objetivo creado');
    }

    /**
     * Display the specified resource.
     */
    public function show(StrategicPlan $plan, StrategicObjective $objective)
    {
        $objective->load(['plan', 'kpis.measurements' => fn($q) => $q->orderBy('measured_at')]);

        // Preparamos dataset para Chart.js con el primer KPI (ejemplo)
        $firstKpi = $objective->kpis->first();
        $chart = [
            'labels' => $firstKpi?->measurements->sortBy('measured_at')->pluck('measured_at')->values() ?? [],
            'values' => $firstKpi?->measurements->sortBy('measured_at')->pluck('value')->values() ?? [],
            'target' => $firstKpi?->target_value ?? null,
            'unit' => $firstKpi?->unit ?? '',
            'kpiName' => $firstKpi?->name ?? 'KPI',
        ];

        return view('planning.objectives.show', compact('objective', 'chart'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StrategicPlan $plan, StrategicObjective $objective)
    {
        $this->authorize('objective.manage');
        $users = User::orderBy('name')->get(['id', 'name']);
        return view('planning.objectives.edit', compact('plan', 'objective', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStrategicObjectiveRequest $request, StrategicPlan $plan, StrategicObjective $objective)
    {
        $objective->update($request->validated());
        return redirect()->route('planning.plans.show', $plan)->with('ok', 'Objetivo actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StrategicPlan $plan, StrategicObjective $objective)
    {
        $this->authorize('objective.manage');

        // Regla: si el objetivo tiene KPIs/mediciones, no se puede borrar
        if ($objective->kpis()->exists()) {
            return back()->with('error', 'No se puede eliminar: el objetivo tiene KPIs asociados.');
        }
        $objective->delete();
        return redirect()->route('planning.plans.show', $plan)->with('ok', 'Objetivo eliminado');
    }
}

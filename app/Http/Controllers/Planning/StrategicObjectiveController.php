<?php

namespace App\Http\Controllers\Planning;

use App\Models\Planning\StrategicObjective;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StrategicObjectiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(StrategicObjective $objective)
    {
        $objective->load(['plan', 'kpis.measurements' => fn($q) => $q->orderByDesc('measured_at')]);

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Planning;

use App\Http\Requests\CreateKpiMeasurementRequest;
use App\Http\Requests\Planning\Kpis\CreateKpiRequest;
use App\Http\Requests\Planning\Kpis\UpdateKpiRequest;
use App\Models\Planning\Kpi;
use App\Models\Planning\KpiMeasurement;
use App\Models\Planning\StrategicObjective;
use App\Models\Planning\StrategicPlan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KpiController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(StrategicPlan $plan, StrategicObjective $objective)
    {
        $this->authorize('objective.manage');
        return view('planning.kpis.create', compact('plan', 'objective'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateKpiRequest $request, StrategicPlan $plan, StrategicObjective $objective)
    {
        $data = $request->validated();
        $kpi = Kpi::create($data);
        return redirect()->route('planning.kpis.show', [$plan->id, $objective->id, $kpi->id])
            ->with('ok', 'KPI creado');
    }

    /**
     * Display the specified resource.
     */
    public function show(StrategicPlan $plan, StrategicObjective $objective, Kpi $kpi)
    {
        $kpi->load(['measurements' => fn($q) => $q->orderByDesc('measured_at')->limit(20),'measurements.user']);
        return view('planning.kpis.show', compact('plan', 'objective', 'kpi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StrategicPlan $plan, StrategicObjective $objective, Kpi $kpi)
    {
        $this->authorize('objective.manage');
        return view('planning.kpis.edit', compact('plan', 'objective', 'kpi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKpiRequest $request, StrategicPlan $plan, StrategicObjective $objective, Kpi $kpi)
    {
        $kpi->update($request->validated());
        return redirect()->route('planning.kpis.show', [$plan->id, $objective->id, $kpi->id])
            ->with('ok', 'KPI actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StrategicPlan $plan, StrategicObjective $objective, Kpi $kpi)
    {
        $this->authorize('objective.manage');

        if ($kpi->measurements()->exists()) {
            return back()->with('error', 'No se puede eliminar: el KPI tiene mediciones.');
        }
        $kpi->delete();
        return redirect()->route('planning.objectives.show', [$plan->id, $objective->id])
            ->with('ok', 'KPI eliminado');
    }
    public function storeMeasurement(CreateKpiMeasurementRequest $request, StrategicPlan $plan, StrategicObjective $objective, Kpi $kpi)
    {
        KpiMeasurement::create($request->validated() + [
            'recorded_by_user_id' => $request->user()->id,
        ]);

        return redirect()->route('planning.kpis.show', [$plan->id, $objective->id, $kpi->id])
            ->with('ok', 'Medición registrada');
    }

    public function deleteMeasurement(StrategicPlan $plan, StrategicObjective $objective, Kpi $kpi, KpiMeasurement $measurement)
    {
        $this->authorize('objective.manage');
        abort_if($measurement->kpi_id !== $kpi->id, 404);
        $measurement->delete();
        return back()->with('ok', 'Medición eliminada');
    }
}

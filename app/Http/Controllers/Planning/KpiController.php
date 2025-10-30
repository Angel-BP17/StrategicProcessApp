<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateKpiMeasurementRequest;
use App\Http\Requests\Planning\Kpis\CreateKpiRequest;
use App\Http\Requests\Planning\Kpis\UpdateKpiRequest;
use App\Models\Planning\Kpi;
use App\Models\Planning\KpiMeasurement;
use App\Models\Planning\StrategicObjective;
use App\Models\Planning\StrategicPlan;

class KpiController extends Controller
{
    public function create(StrategicPlan $plan, StrategicObjective $objective)
    {
        $this->authorize('objective.manage');

        return response()->json([
            'plan' => $plan,
            'objective' => $objective,
        ]);
    }

    public function store(CreateKpiRequest $request, StrategicPlan $plan, StrategicObjective $objective)
    {
        $kpi = Kpi::create($request->validated());

        return response()->json([
            'message' => 'KPI creado.',
            'data' => $kpi,
        ], 201);
    }

    public function show(StrategicPlan $plan, StrategicObjective $objective, Kpi $kpi)
    {
        $kpi->load(['measurements' => fn ($q) => $q->orderByDesc('measured_at')->limit(20), 'measurements.user']);

        return response()->json([
            'plan' => $plan,
            'objective' => $objective,
            'kpi' => $kpi,
        ]);
    }

    public function edit(StrategicPlan $plan, StrategicObjective $objective, Kpi $kpi)
    {
        $this->authorize('objective.manage');

        return response()->json([
            'plan' => $plan,
            'objective' => $objective,
            'kpi' => $kpi,
        ]);
    }

    public function update(UpdateKpiRequest $request, StrategicPlan $plan, StrategicObjective $objective, Kpi $kpi)
    {
        $kpi->update($request->validated());

        return response()->json([
            'message' => 'KPI actualizado.',
            'data' => $kpi->fresh(),
        ]);
    }

    public function destroy(StrategicPlan $plan, StrategicObjective $objective, Kpi $kpi)
    {
        $this->authorize('objective.manage');

        if ($kpi->measurements()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar: el KPI tiene mediciones.',
            ], 422);
        }

        $kpi->delete();

        return response()->json([
            'message' => 'KPI eliminado.',
        ]);
    }

    public function storeMeasurement(CreateKpiMeasurementRequest $request, StrategicPlan $plan, StrategicObjective $objective, Kpi $kpi)
    {
        $measurement = KpiMeasurement::create($request->validated() + [
            'recorded_by_user_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Medición registrada.',
            'data' => $measurement,
        ], 201);
    }

    public function deleteMeasurement(StrategicPlan $plan, StrategicObjective $objective, Kpi $kpi, KpiMeasurement $measurement)
    {
        $this->authorize('objective.manage');
        abort_if($measurement->kpi_id != $kpi->id, 404);
        $measurement->delete();

        return response()->json([
            'message' => 'Medición eliminada.',
        ]);
    }
}

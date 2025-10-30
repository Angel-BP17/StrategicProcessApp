<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use App\Http\Requests\Planning\StrategicPlans\CreateStrategicPlanRequest;
use App\Http\Requests\Planning\StrategicPlans\UpdateStrategicPlanRequest;
use App\Models\Planning\StrategicPlan;

class StrategicPlanController extends Controller
{
    public function index()
    {
        $plans = StrategicPlan::withCount('objectives')->latest()->paginate(10);

        return response()->json([
            'data' => $plans,
        ]);
    }

    public function create()
    {
        $this->authorize('plan.manage');

        return response()->json();
    }

    public function show(StrategicPlan $plan)
    {
        $plan->load(['objectives.kpis.measurements' => fn ($q) => $q->latest('measured_at')->limit(12)]);

        return response()->json([
            'plan' => $plan,
        ]);
    }

    public function edit(StrategicPlan $plan)
    {
        $this->authorize('plan.manage');

        return response()->json([
            'plan' => $plan,
        ]);
    }

    public function store(CreateStrategicPlanRequest $request)
    {
        $plan = StrategicPlan::create($request->validated() + ['created_by_user_id' => auth()->id()]);

        return response()->json([
            'message' => 'Plan creado.',
            'data' => $plan,
        ], 201);
    }

    public function update(UpdateStrategicPlanRequest $request, StrategicPlan $plan)
    {
        $plan->update($request->validated());

        return response()->json([
            'message' => 'Plan actualizado.',
            'data' => $plan->fresh(),
        ]);
    }

    public function destroy(StrategicPlan $plan)
    {
        $this->authorize('plan.manage');

        if ($plan->objectives()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar: el plan tiene objetivos asociados.',
            ], 422);
        }

        $plan->delete();

        return response()->json([
            'message' => 'Plan eliminado.',
        ]);
    }
}

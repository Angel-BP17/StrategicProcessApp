<?php

namespace App\Http\Controllers\Planning;

use App\Http\Requests\Planning\StrategicPlans\CreateStrategicPlanRequest;
use App\Http\Requests\Planning\StrategicPlans\UpdateStrategicPlanRequest;
use App\Models\Planning\StrategicPlan;
use App\Http\Controllers\Controller;

class StrategicPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = StrategicPlan::withCount('objectives')->latest()->paginate(10);
        return view('planning.plans.index', compact('plans'));
    }
    public function create()
    {
        $this->authorize('plan.manage');
        return view('planning.plans.create');
    }
    public function show(StrategicPlan $plan)
    {
        $plan->load(['objectives.kpis.measurements' => fn($q) => $q->latest('measured_at')->limit(12)]);
        return view('planning.plans.show', compact('plan'));
    }
    public function edit(StrategicPlan $plan)
    {
        $this->authorize('plan.manage');
        return view('planning.plans.edit', compact('plan'));
    }
    public function store(CreateStrategicPlanRequest $request)
    {
        StrategicPlan::create($request->validated() + ['created_by_user_id' => auth()->id()]);
        return redirect()->route('planning.plans.index')->with('ok', 'Plan creado');
    }
    public function update(UpdateStrategicPlanRequest $request, StrategicPlan $plan)
    { /* actualizar */
        $plan->update($request->validated());
        return redirect()->route('planning.plans.index')->with('ok', 'Plan actualizado');
    }
    public function destroy(StrategicPlan $plan)
    { /* eliminar */
        $this->authorize('plan.manage');

        // Regla: solo eliminar si NO tiene objetivos
        if ($plan->objectives()->exists()) {
            return back()->with('error', 'No se puede eliminar: el plan tiene objetivos asociados.');
        }
        $plan->delete();
        return redirect()->route('planning.plans.index')->with('ok', 'Plan eliminado');
    }
}

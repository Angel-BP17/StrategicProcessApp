<?php

namespace App\Http\Controllers\Planning;

use App\Models\Planning\StrategicPlan;
use Illuminate\Http\Request;
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
        //$this->authorize('plan.manage');
        return view('planning.plans.create');
    }
    public function show(StrategicPlan $plan)
    {
        $plan->load(['objectives.kpis']);
        return view('planning.plans.show', compact('plan'));
    }
    public function edit($plan)
    {
        return view('planning.plans.edit', compact('plan'));
    }
    public function store(Request $request)
    {
        StrategicPlan::create($request->validated() + ['created_by_user_id' => auth()->id()]);
        return redirect()->route('planning.plans.index')->with('ok', 'Plan creado');
    }
    public function update(Request $r, $plan)
    { /* actualizar */
        return back();
    }
    public function destroy($plan)
    { /* eliminar */
        return back();
    }
}

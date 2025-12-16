<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use IncadevUns\CoreDomain\Models\KpiGoal;
use IncadevUns\CoreDomain\Models\StrategicPlan;
use IncadevUns\CoreDomain\Models\StrategicObjective;

class StrategicObjectiveController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
        /*
        $this->middleware('permission:strategic_objectives.view')->only(['index','show']);
        $this->middleware('permission:strategic_objectives.create')->only(['store']);
        $this->middleware('permission:strategic_objectives.update')->only(['update']);
        $this->middleware('permission:strategic_objectives.delete')->only(['destroy']);*/
    }

    public function index(StrategicPlan $strategicPlan)
    {
        $objectives = $strategicPlan->objectives()->with(['plan', 'user'])->latest('id')->paginate(10);

        return response()->json($objectives);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, StrategicPlan $strategicPlan)
    {
        $data = $request->validate([
            'plan_id' => ['sometimes', 'integer', 'exists:strategic_plans,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'goal_value' => ['required', 'numeric'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'weight' => ['nullable', 'integer', 'min:0'],
            'kpis' => ['nullable', 'array'],
        ]);

        if (isset($data['plan_id']) && (int) $data['plan_id'] !== $strategicPlan->id) {
            abort(422, 'El plan proporcionado no coincide con la ruta.');
        }

        $obj = StrategicObjective::create($data + ['plan_id' => $strategicPlan->id]);
        return response()->json($obj->load(['plan', 'user']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(StrategicPlan $strategicPlan, StrategicObjective $strategicObjective)
    {
        $objective = $this->getObjectiveForPlan($strategicPlan, $strategicObjective);
        $kpis = KpiGoal::whereIn('id', $objective->kpis ?? [])->get();

        return response()->json([
            $objective->load(['plan', 'user']),
            'kpis-contend' => $kpis
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StrategicPlan $strategicPlan, StrategicObjective $strategicObjective)
    {
        $data = $request->validate([
            'plan_id' => ['sometimes', 'integer', 'exists:strategic_plans,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'goal_value' => ['sometimes', 'numeric'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'weight' => ['sometimes', 'integer', 'min:0'],
            'kpis' => ['sometimes', 'nullable', 'array'],
        ]);

        if (isset($data['plan_id']) && (int) $data['plan_id'] !== $strategicPlan->id) {
            abort(422, 'El plan proporcionado no coincide con la ruta.');
        }

        $objective = $this->getObjectiveForPlan($strategicPlan, $strategicObjective);
        $objective->update($data + ['plan_id' => $strategicPlan->id]);

        return response()->json($objective->load(['plan', 'user']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StrategicPlan $strategicPlan, StrategicObjective $strategicObjective)
    {
        $objective = $this->getObjectiveForPlan($strategicPlan, $strategicObjective);
        $objective->delete();
        return response()->json(['message' => 'Deleted'], 204);
    }

    private function getObjectiveForPlan(StrategicPlan $strategicPlan, StrategicObjective $strategicObjective): StrategicObjective
    {
        abort_if($strategicObjective->plan_id !== $strategicPlan->id, 404, 'Objective not found for plan.');

        return $strategicObjective;
    }
}

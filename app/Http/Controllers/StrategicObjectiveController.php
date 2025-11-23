<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function index()
    {
        return response()->json(StrategicObjective::query()->with(['plan', 'user'])->latest('id')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:strategic_plans,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'goal_value' => ['required', 'numeric'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'weight' => ['nullable', 'integer', 'min:0'],
            'kpis' => ['nullable', 'array'],
        ]);

        $obj = StrategicObjective::create($data);
        return response()->json($obj->load(['plan', 'user']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(StrategicObjective $strategicObjective)
    {
        return response()->json($strategicObjective->load(['plan', 'user']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StrategicObjective $strategicObjective)
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

        $strategicObjective->update($data);
        return response()->json($strategicObjective->load(['plan', 'user']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StrategicObjective $strategicObjective)
    {
        $strategicObjective->delete();
        return response()->json(['message' => 'Deleted'], 204);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use IncadevUns\CoreDomain\Models\Iniciative;

class IniciativeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
        $this->middleware(['role:planner_admin']);
        /*
        $this->middleware('permission:iniciatives.view')->only(['index','show']);
        $this->middleware('permission:iniciatives.create')->only(['store']);
        $this->middleware('permission:iniciatives.update')->only(['update']);
        $this->middleware('permission:iniciatives.delete')->only(['destroy']);*/
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 20);
        $q = Iniciative::query()->with(['plan', 'user'])->latest('id');
        return response()->json($q->paginate($perPage));
    }

    public function show(Iniciative $iniciative)
    {
        return response()->json($iniciative->load(['plan', 'user', 'evaluations']));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'plan_id' => ['required', 'integer', 'exists:strategic_plans,id'],
            'summary' => ['required', 'string'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'status' => ['required', 'string', 'max:50'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'estimated_impact' => ['required', 'string', 'max:50'],
        ]);

        $ini = Iniciative::create($data);
        return response()->json($ini->load(['plan', 'user']), 201);
    }

    public function update(Request $request, Iniciative $iniciative)
    {
        $data = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'plan_id' => ['sometimes', 'integer', 'exists:strategic_plans,id'],
            'summary' => ['sometimes', 'string'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'status' => ['sometimes', 'string', 'max:50'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date', 'after_or_equal:start_date'],
            'estimated_impact' => ['sometimes', 'string', 'max:50'],
        ]);

        $iniciative->update($data);
        return response()->json($iniciative->load(['plan', 'user']));
    }

    public function destroy(Iniciative $iniciative)
    {
        $iniciative->delete();
        return response()->json(['message' => 'Deleted'], 204);
    }
}

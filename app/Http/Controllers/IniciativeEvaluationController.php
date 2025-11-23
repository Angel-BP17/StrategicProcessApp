<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use IncadevUns\CoreDomain\Models\IniciativeEvaluation;

class IniciativeEvaluationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
        $this->middleware(['role:planner_admin']);
        /*
        $this->middleware('permission:iniciative_evaluations.view')->only(['index', 'show']);
        $this->middleware('permission:iniciative_evaluations.create')->only(['store']);
        $this->middleware('permission:iniciative_evaluations.update')->only(['update']);
        $this->middleware('permission:iniciative_evaluations.delete')->only(['destroy']);*/
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 20);
        $q = IniciativeEvaluation::query()->with(['iniciative', 'evaluator', 'document'])->latest('id');
        return response()->json($q->paginate($perPage));
    }

    public function show(IniciativeEvaluation $iniciativeEvaluation)
    {
        return response()->json(
            $iniciativeEvaluation->load(['iniciative', 'evaluator', 'document'])
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'iniciative_id' => ['required', 'integer', 'exists:iniciatives,id'],
            'evaluator_user' => ['required', 'integer', 'exists:users,id'],
            'summary' => ['required', 'string'],
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'document_id' => ['nullable', 'integer', 'exists:strategic_documents,id'],
        ]);

        $ev = IniciativeEvaluation::create($data);
        return response()->json($ev->load(['iniciative', 'evaluator', 'document']), 201);
    }

    public function update(Request $request, IniciativeEvaluation $iniciativeEvaluation)
    {
        $data = $request->validate([
            'iniciative_id' => ['sometimes', 'integer', 'exists:iniciatives,id'],
            'evaluator_user' => ['sometimes', 'integer', 'exists:users,id'],
            'summary' => ['sometimes', 'string'],
            'score' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'document_id' => ['sometimes', 'nullable', 'integer', 'exists:strategic_documents,id'],
        ]);

        $iniciativeEvaluation->update($data);
        return response()->json($iniciativeEvaluation->load(['iniciative', 'evaluator', 'document']));
    }

    public function destroy(IniciativeEvaluation $iniciativeEvaluation)
    {
        $iniciativeEvaluation->delete();
        return response()->json(['message' => 'Deleted'], 204);
    }
}

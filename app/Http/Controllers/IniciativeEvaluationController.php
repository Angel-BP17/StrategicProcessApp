<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use IncadevUns\CoreDomain\Models\Iniciative;
use IncadevUns\CoreDomain\Models\IniciativeEvaluation;

class IniciativeEvaluationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 20);

        $q = IniciativeEvaluation::query()
            ->with(['iniciative', 'evaluator', 'document'])
            ->latest('id');

        if ($request->filled('iniciative_id')) {
            $q->where('iniciative_id', $request->integer('iniciative_id'));
        }

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

        $iniciative = Iniciative::findOrFail($data['iniciative_id']);

        $evaluation = IniciativeEvaluation::create($data);

        if ($iniciative->status === 'finalizada') {
            $iniciative->update(['status' => 'evaluada']);
        }

        return response()->json($evaluation->load(['iniciative', 'evaluator', 'document']), 201);
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

        if (isset($data['iniciative_id'])) {
            Iniciative::findOrFail($data['iniciative_id']);
        }

        $iniciativeEvaluation->update($data);

        return response()->json($iniciativeEvaluation->load(['iniciative', 'evaluator', 'document']));
    }

    public function destroy(IniciativeEvaluation $iniciativeEvaluation)
    {
        $iniciativeEvaluation->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}

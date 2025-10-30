<?php

namespace App\Http\Controllers\InnovacionMejoraContinua;

use App\Http\Controllers\Controller;
use App\Models\InnovacionMejoraContinua\Initiative;
use App\Models\InnovacionMejoraContinua\InitiativeEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InitiativeEvaluationController extends Controller
{
    public function index(Initiative $initiative)
    {
        $evaluations = $initiative->evaluations()
            ->with('evaluator')
            ->orderBy('evaluation_date', 'desc')
            ->paginate(10);

        return response()->json([
            'initiative' => $initiative,
            'evaluations' => $evaluations,
        ]);
    }

    public function create(Initiative $initiative)
    {
        return response()->json([
            'initiative' => $initiative,
        ]);
    }

    public function store(Request $request, Initiative $initiative)
    {
        $validated = $request->validate([
            'evaluation_date' => 'required|date',
            'summary' => 'required|string',
            'score' => 'required|numeric|min:0|max:10',
            'report_document_version_id' => 'nullable|exists:document_versions,id',
        ]);

        $validated['initiative_id'] = $initiative->id;
        $validated['evaluator_user_id'] = Auth::id();

        $evaluation = InitiativeEvaluation::create($validated);

        if ($initiative->status === 'propuesta') {
            $initiative->update(['status' => 'evaluada']);
        }

        return response()->json([
            'message' => 'Evaluación registrada exitosamente.',
            'data' => $evaluation->load('evaluator'),
        ], 201);
    }

    public function show(Initiative $initiative, InitiativeEvaluation $evaluation)
    {
        if ($evaluation->initiative_id !== $initiative->id) {
            abort(404);
        }

        $evaluation->load('evaluator');

        return response()->json([
            'initiative' => $initiative,
            'evaluation' => $evaluation,
        ]);
    }

    public function edit(Initiative $initiative, InitiativeEvaluation $evaluation)
    {
        if ($evaluation->initiative_id !== $initiative->id) {
            abort(404);
        }

        if (Auth::id() !== $evaluation->evaluator_user_id && !Auth::user()?->hasRole('admin')) {
            abort(403, 'No tienes permisos para editar esta evaluación');
        }

        return response()->json([
            'initiative' => $initiative,
            'evaluation' => $evaluation,
        ]);
    }

    public function update(Request $request, Initiative $initiative, InitiativeEvaluation $evaluation)
    {
        if ($evaluation->initiative_id !== $initiative->id) {
            abort(404);
        }

        if (Auth::id() !== $evaluation->evaluator_user_id && !Auth::user()?->hasRole('admin')) {
            abort(403, 'No tienes permisos para actualizar esta evaluación');
        }

        $validated = $request->validate([
            'evaluation_date' => 'required|date',
            'summary' => 'required|string',
            'score' => 'required|numeric|min:0|max:10',
            'report_document_version_id' => 'nullable|exists:document_versions,id',
        ]);

        $evaluation->update($validated);

        return response()->json([
            'message' => 'Evaluación actualizada exitosamente.',
            'data' => $evaluation->fresh()->load('evaluator'),
        ]);
    }

    public function destroy(Initiative $initiative, InitiativeEvaluation $evaluation)
    {
        if ($evaluation->initiative_id !== $initiative->id) {
            abort(404);
        }

        if (Auth::id() !== $evaluation->evaluator_user_id && !Auth::user()?->hasRole('admin')) {
            abort(403, 'No tienes permisos para eliminar esta evaluación');
        }

        $evaluation->delete();

        if ($initiative->evaluations()->count() === 0 && $initiative->status === 'evaluada') {
            $initiative->update(['status' => 'propuesta']);
        }

        return response()->json([
            'message' => 'Evaluación eliminada exitosamente.',
        ]);
    }
}

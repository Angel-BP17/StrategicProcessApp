<?php

namespace App\Http\Controllers\InnovacionMejoraContinua;

use App\Http\Controllers\Controller;
use App\Models\InnovacionMejoraContinua\Initiative;
use App\Models\InnovacionMejoraContinua\InitiativeEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InitiativeEvaluationController extends Controller
{
    // Listar evaluaciones de una iniciativa
    public function index(Initiative $initiative)
    {
        $evaluations = $initiative->evaluations()
            ->with('evaluator')
            ->orderBy('evaluation_date', 'desc')
            ->paginate(10);

        return view('innovacion_mejora_continua.evaluations.index', compact('initiative', 'evaluations'));
    }

    // Mostrar formulario de creación de evaluación
    public function create(Initiative $initiative)
    {
        return view('innovacion_mejora_continua.evaluations.create', compact('initiative'));
    }

    // Guardar nueva evaluación
    public function store(Request $request, Initiative $initiative)
    {
        $validated = $request->validate([
            'evaluation_date' => 'required|date',
            'summary' => 'required|string',
            'score' => 'required|numeric|min:0|max:10',
            'report_document_version_id' => 'nullable|exists:document_versions,id'
        ], [
            'evaluation_date.required' => 'La fecha de evaluación es obligatoria',
            'summary.required' => 'El resumen de la evaluación es obligatorio',
            'score.required' => 'La puntuación es obligatoria',
            'score.min' => 'La puntuación mínima es 0',
            'score.max' => 'La puntuación máxima es 10',
            'report_document_version_id.exists' => 'El documento seleccionado no existe'
        ]);

        $validated['initiative_id'] = $initiative->id;
        $validated['evaluator_user_id'] = Auth::id();

        $evaluation = InitiativeEvaluation::create($validated);

        // Actualizar estado de la iniciativa si es necesario
        if ($initiative->status === 'propuesta') {
            $initiative->update(['status' => 'evaluada']);
        }

        return redirect()
            ->route('innovacion-mejora-continua.initiatives.show', $initiative)
            ->with('success', 'Evaluación registrada exitosamente');
    }

    // Mostrar detalle de una evaluación
    public function show(Initiative $initiative, InitiativeEvaluation $evaluation)
    {
        // Verificar que la evaluación pertenece a la iniciativa
        if ($evaluation->initiative_id !== $initiative->id) {
            abort(404);
        }

        $evaluation->load('evaluator');

        return view('innovacion_mejora_continua.evaluations.show', compact('initiative', 'evaluation'));
    }

    // Mostrar formulario de edición de evaluación
    public function edit(Initiative $initiative, InitiativeEvaluation $evaluation)
    {
        // Verificar que la evaluación pertenece a la iniciativa
        if ($evaluation->initiative_id !== $initiative->id) {
            abort(404);
        }

        // Solo el evaluador original o un admin puede editar
        if (Auth::id() !== $evaluation->evaluator_user_id && !Auth::user()->hasRole('admin')) {
            return redirect()
                ->back()
                ->with('error', 'No tienes permisos para editar esta evaluación');
        }

        return view('innovacion_mejora_continua.evaluations.edit', compact('initiative', 'evaluation'));
    }

    // Actualizar evaluación
    public function update(Request $request, Initiative $initiative, InitiativeEvaluation $evaluation)
    {
        // Verificar que la evaluación pertenece a la iniciativa
        if ($evaluation->initiative_id !== $initiative->id) {
            abort(404);
        }

        // Solo el evaluador original o un admin puede actualizar
        if (Auth::id() !== $evaluation->evaluator_user_id && !Auth::user()->hasRole('admin')) {
            return redirect()
                ->back()
                ->with('error', 'No tienes permisos para actualizar esta evaluación');
        }

        $validated = $request->validate([
            'evaluation_date' => 'required|date',
            'summary' => 'required|string',
            'score' => 'required|numeric|min:0|max:10',
            'report_document_version_id' => 'nullable|exists:document_versions,id'
        ], [
            'evaluation_date.required' => 'La fecha de evaluación es obligatoria',
            'summary.required' => 'El resumen de la evaluación es obligatorio',
            'score.required' => 'La puntuación es obligatoria',
            'score.min' => 'La puntuación mínima es 0',
            'score.max' => 'La puntuación máxima es 10',
            'report_document_version_id.exists' => 'El documento seleccionado no existe'
        ]);

        $evaluation->update($validated);

        return redirect()
            ->route('innovacion-mejora-continua.initiatives.show', $initiative)
            ->with('success', 'Evaluación actualizada exitosamente');
    }

    // Eliminar evaluación
    public function destroy(Initiative $initiative, InitiativeEvaluation $evaluation)
    {
        // Verificar que la evaluación pertenece a la iniciativa
        if ($evaluation->initiative_id !== $initiative->id) {
            abort(404);
        }

        // Solo el evaluador original o un admin pueden eliminar
        if (Auth::id() !== $evaluation->evaluator_user_id && !Auth::user()->hasRole('admin')) {
            return redirect()
                ->back()
                ->with('error', 'No tienes permisos para eliminar esta evaluación');
        }

        $evaluation->delete();

        // Si la iniciativa ya no tiene evaluaciones y está en estado "evaluada",
        // regresarla a "propuesta"
        if ($initiative->evaluations()->count() === 0 && $initiative->status === 'evaluada') {
            $initiative->update(['status' => 'propuesta']);
        }

        return redirect()
            ->route('innovacion-mejora-continua.initiatives.show', $initiative)
            ->with('success', 'Evaluación eliminada exitosamente');
    }
}

<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Quality\EvaluationCriterion;
use App\Models\Quality\OptionCriterion;
use Illuminate\Http\Request;

class EvaluationCriterionController extends Controller
{
    public function index()
    {
        $criteria = EvaluationCriterion::with('options')->orderBy('criterion_name')->get();

        return response()->json([
            'data' => $criteria,
        ]);
    }

    public function create()
    {
        return response()->json();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'criterion_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'response_type' => 'required|in:numeric,text,option',
            'percentage_weight' => 'nullable|numeric|min:0|max:100',
            'state' => 'required|string|max:255',
            'options_text' => 'required_if:response_type,option|nullable|string',
        ]);

        $criterion = EvaluationCriterion::create(
            collect($validatedData)->except('options_text')->toArray()
        );

        if ($criterion && $validatedData['response_type'] === 'option' && !empty($validatedData['options_text'])) {
            $optionsArray = array_filter(preg_split("/\r\n|\r|\n/", $validatedData['options_text']));
            $optionsToInsert = collect($optionsArray)->map(function ($optionText) use ($criterion) {
                return [
                    'id_evaluation_criteria' => $criterion->id,
                    'option_text' => trim($optionText),
                ];
            })->all();

            if (!empty($optionsToInsert)) {
                OptionCriterion::insert($optionsToInsert);
            }
        }

        return response()->json([
            'message' => 'Criterio de evaluación creado con éxito.',
            'data' => $criterion->load('options'),
        ], 201);
    }

    public function edit(EvaluationCriterion $criterion)
    {
        $criterion->load('options');
        $optionsText = $criterion->response_type === 'option'
            ? $criterion->options->pluck('option_text')->implode("\n")
            : '';

        return response()->json([
            'criterion' => $criterion,
            'optionsText' => $optionsText,
        ]);
    }

    public function update(Request $request, EvaluationCriterion $criterion)
    {
        $validatedData = $request->validate([
            'criterion_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'response_type' => 'required|in:numeric,text,option',
            'percentage_weight' => 'nullable|numeric|min:0|max:100',
            'state' => 'required|string|max:255',
            'options_text' => 'required_if:response_type,option|nullable|string',
        ]);

        $criterion->update(collect($validatedData)->except('options_text')->toArray());

        $criterion->options()->delete();

        if ($validatedData['response_type'] === 'option' && !empty($validatedData['options_text'])) {
            $optionsArray = array_filter(preg_split("/\r\n|\r|\n/", $validatedData['options_text']));
            $optionsToInsert = collect($optionsArray)->map(function ($optionText) use ($criterion) {
                return [
                    'id_evaluation_criteria' => $criterion->id,
                    'option_text' => trim($optionText),
                ];
            })->all();

            if (!empty($optionsToInsert)) {
                OptionCriterion::insert($optionsToInsert);
            }
        }

        return response()->json([
            'message' => 'Criterio de evaluación actualizado con éxito.',
            'data' => $criterion->fresh()->load('options'),
        ]);
    }

    public function destroy(EvaluationCriterion $criterion)
    {
        $criterion->options()->delete();
        $criterion->delete();

        return response()->json([
            'message' => 'Criterio de evaluación eliminado con éxito.',
        ]);
    }
}

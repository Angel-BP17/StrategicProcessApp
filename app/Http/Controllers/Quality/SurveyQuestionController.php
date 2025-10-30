<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Quality\Survey;
use App\Models\Quality\SurveyOption;
use App\Models\Quality\SurveyQuestion;
use Illuminate\Http\Request;

class SurveyQuestionController extends Controller
{
    public function store(Request $request, Survey $survey)
    {
        $validatedData = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:text,option,rating_1_5',
            'options_text' => 'required_if:question_type,option|nullable|string',
        ]);

        $validatedData['survey_id'] = $survey->id;

        $question = SurveyQuestion::create(
            collect($validatedData)->except('options_text')->toArray()
        );

        if ($validatedData['question_type'] === 'option' && !empty($validatedData['options_text'])) {
            $optionsArray = array_filter(preg_split("/\r\n|\r|\n/", $validatedData['options_text']));
            $optionsToInsert = collect($optionsArray)->map(function ($optionText, $index) use ($question) {
                return [
                    'survey_question_id' => $question->id,
                    'option_text' => trim($optionText),
                    'order' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->all();

            if (!empty($optionsToInsert)) {
                SurveyOption::insert($optionsToInsert);
            }
        }

        return response()->json([
            'message' => 'Pregunta añadida con éxito.',
            'data' => $question->load('options'),
        ], 201);
    }

    public function edit(Survey $survey, SurveyQuestion $question)
    {
        if ($question->survey_id !== $survey->id) {
            abort(403, 'Acción no autorizada.');
        }

        $question->load('options');

        $optionsText = $question->question_type === 'option'
            ? $question->options->sortBy('order')->pluck('option_text')->implode("\n")
            : '';

        return response()->json([
            'survey' => $survey,
            'question' => $question,
            'optionsText' => $optionsText,
        ]);
    }

    public function update(Request $request, Survey $survey, SurveyQuestion $question)
    {
        if ($question->survey_id !== $survey->id) {
            abort(403, 'Acción no autorizada.');
        }

        $validatedData = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:text,option,rating_1_5',
            'options_text' => 'required_if:question_type,option|nullable|string',
        ]);

        $question->update(
            collect($validatedData)->except('options_text')->toArray()
        );

        $question->options()->delete();

        if ($validatedData['question_type'] === 'option' && !empty($validatedData['options_text'])) {
            $optionsArray = array_filter(preg_split("/\r\n|\r|\n/", $validatedData['options_text']));
            $optionsToInsert = collect($optionsArray)->map(function ($optionText, $index) use ($question) {
                return [
                    'survey_question_id' => $question->id,
                    'option_text' => trim($optionText),
                    'order' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->all();

            if (!empty($optionsToInsert)) {
                SurveyOption::insert($optionsToInsert);
            }
        }

        return response()->json([
            'message' => 'Pregunta actualizada con éxito.',
            'data' => $question->fresh()->load('options'),
        ]);
    }

    public function destroy(Survey $survey, SurveyQuestion $question)
    {
        if ($question->survey_id !== $survey->id) {
            abort(403, 'Acción no autorizada.');
        }

        $question->delete();

        return response()->json([
            'message' => 'Pregunta eliminada con éxito.',
        ]);
    }
}

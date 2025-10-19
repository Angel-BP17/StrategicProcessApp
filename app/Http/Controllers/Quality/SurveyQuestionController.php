<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Quality\Survey; // Importamos Survey
use App\Models\Quality\SurveyQuestion; // Importamos SurveyQuestion
use Illuminate\Http\Request;
use App\Models\Quality\SurveyOption;


class SurveyQuestionController extends Controller
{
    /**
     * Guarda una nueva pregunta para una encuesta específica.
     */
    public function store(Request $request, Survey $survey)
    {
        // 1. Validamos los datos, incluyendo 'options_text' si el tipo es 'option'
        $validatedData = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:text,option,rating_1_5',
            // 'options_text' es requerido SOLO SI question_type es 'option'
            'options_text' => 'required_if:question_type,option|nullable|string',
        ]);

        // 2. Asignamos el survey_id
        $validatedData['survey_id'] = $survey->id;

        // 3. Creamos la pregunta (quitamos 'options_text' antes de crear)
        $questionData = collect($validatedData)->except('options_text')->toArray();
        $question = SurveyQuestion::create($questionData);

        // 4. SI el tipo es 'option', procesamos y guardamos las opciones
        if ($validatedData['question_type'] === 'option' && !empty($validatedData['options_text'])) {
            // Dividimos el texto del textarea en líneas, quitamos líneas vacías
            $optionsArray = array_filter(preg_split('/\r\n|\r|\n/', $validatedData['options_text']));

            if (!empty($optionsArray)) {
                $optionsToInsert = [];
                foreach ($optionsArray as $index => $optionText) {
                    $optionsToInsert[] = [
                        'survey_question_id' => $question->id,
                        'option_text' => trim($optionText),
                        'order' => $index + 1, // Guardamos el orden
                        'created_at' => now(), // Añadimos timestamps manualmente si es necesario
                        'updated_at' => now(), // Añadimos timestamps manualmente si es necesario
                    ];
                }
                // Insertamos todas las opciones en la base de datos de golpe
                SurveyOption::insert($optionsToInsert);
            }
        }

        // 5. Redirigimos de vuelta a la página de diseño
        return redirect()->route('quality.surveys.design', $survey)
                        ->with('success', 'Pregunta añadida con éxito.');
    }

    /**
 * Elimina una pregunta específica de una encuesta.
 */
    public function destroy(Survey $survey, SurveyQuestion $question)
    {
        // 1. Verificación (Opcional pero segura): Asegurarse que la pregunta pertenece a la encuesta
        if ($question->survey_id !== $survey->id) {
            // Abortar o redirigir con error si no coinciden
            abort(403, 'Acción no autorizada.'); 
        }

        // 2. Eliminamos la pregunta (y sus opciones se borrarán en cascada si la FK está bien configurada)
        $question->delete();

        // 3. Redirigimos de vuelta a la página de diseño con un mensaje
        return redirect()->route('quality.surveys.design', $survey)
                        ->with('success', 'Pregunta eliminada con éxito.');
    }
    public function edit(Survey $survey, SurveyQuestion $question)
    {
        // Ensure question belongs to the survey (optional check)
        if ($question->survey_id !== $survey->id) {
            abort(403);
        }

        // Load options if it's an 'option' type question
        if ($question->question_type === 'option') {
            $question->load('options');
            // Format options back into newline separated text for the textarea
            $optionsText = $question->options->sortBy('order')->pluck('option_text')->implode("\n");
        } else {
            $optionsText = '';
        }

        // Pass the question and options text to the edit view
        return view('quality.surveys.edit_question', [ // We'll create this view
            'survey' => $survey,
            'question' => $question,
            'optionsText' => $optionsText, // Send formatted options
        ]);
    }

    /**
     * Update the specified question in storage.
     */
    public function update(Request $request, Survey $survey, SurveyQuestion $question)
    {
         // Ensure question belongs to the survey (optional check)
        if ($question->survey_id !== $survey->id) {
            abort(403);
        }

        // 1. Validate the incoming data
        $validatedData = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:text,option,rating_1_5',
            'options_text' => 'required_if:question_type,option|nullable|string',
        ]);

        // 2. Update the question itself (excluding options_text)
        $questionData = collect($validatedData)->except('options_text')->toArray();
        $question->update($questionData);

        // 3. Handle options if the type is 'option'
        if ($validatedData['question_type'] === 'option') {
            // Delete existing options first
            $question->options()->delete();

            // Process and insert new options
            if (!empty($validatedData['options_text'])) {
                $optionsArray = array_filter(preg_split('/\r\n|\r|\n/', $validatedData['options_text']));
                if (!empty($optionsArray)) {
                    $optionsToInsert = [];
                    foreach ($optionsArray as $index => $optionText) {
                        $optionsToInsert[] = [
                            'survey_question_id' => $question->id,
                            'option_text' => trim($optionText),
                            'order' => $index + 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    SurveyOption::insert($optionsToInsert);
                }
            }
        } else {
            // If type changed FROM option, delete any lingering options
            $question->options()->delete();
        }

        // 4. Redirect back to the design page
        return redirect()->route('quality.surveys.design', $survey)
                         ->with('success', 'Pregunta actualizada con éxito.');
    }





    // Aquí irían luego los métodos update, destroy para preguntas
}
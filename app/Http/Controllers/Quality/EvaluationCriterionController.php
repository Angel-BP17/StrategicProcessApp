<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Quality\EvaluationCriterion; 
use Illuminate\Http\Request;
use App\Models\Quality\OptionCriterion;

class EvaluationCriterionController extends Controller
{
    /**
     * Muestra el listado de criterios de evaluación.
     */
    public function index()
    {
        $criteria = EvaluationCriterion::orderBy('criterion_name')->get(); // Ordenamos por nombre

        return view('quality.evaluation-criteria.index', [ // Vista que crearemos
            'criteria' => $criteria
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo criterio.
     */
    public function create()
    {
        // Vista que crearemos
        return view('quality.evaluation-criteria.create');
    }

    /**
     * Guarda el nuevo criterio en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validamos, incluyendo 'options_text' si es 'option'
        $validatedData = $request->validate([
            'criterion_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'response_type' => 'required|in:numeric,text,option',
            'percentage_weight' => 'nullable|numeric|min:0|max:100',
            'state' => 'required|string|max:255',
            'options_text' => 'required_if:response_type,option|nullable|string', // Validación añadida
        ]);

        // 2. Creamos el criterio (quitando 'options_text')
        $criterionData = collect($validatedData)->except('options_text')->toArray();
        $criterion = EvaluationCriterion::create($criterionData);

        // 3. SI es 'option', guardamos las opciones
        if ($criterion && $validatedData['response_type'] === 'option' && !empty($validatedData['options_text'])) {
            $optionsArray = array_filter(preg_split('/\r\n|\r|\n/', $validatedData['options_text']));
            if (!empty($optionsArray)) {
                $optionsToInsert = [];
                foreach ($optionsArray as $index => $optionText) {
                    $optionsToInsert[] = [
                        'id_evaluation_criteria' => $criterion->id, // FK correcta
                        'option_text' => trim($optionText),
                        // 'order' => $index + 1, // Si añades columna 'order' a option_criteria
                    ];
                }
                OptionCriterion::insert($optionsToInsert);
            }
        }

        // 4. Redirigimos
        return redirect()->route('quality.evaluation-criteria.index')
                        ->with('success', 'Criterio de evaluación creado con éxito.');
    }

    public function destroy(EvaluationCriterion $criterion)
    {
        // Si la FK en option_criteria tiene ON DELETE CASCADE, las opciones se borran solas.
        // Si no, bórralas manualmente primero: $criterion->options()->delete();
        $criterion->options()->delete();
        $criterion->delete(); // Elimina el criterio

        return redirect()->route('quality.evaluation-criteria.index')
                         ->with('success', 'Criterio de evaluación eliminado con éxito.');
    }


    public function edit(EvaluationCriterion $criterion)
    {
        $optionsText = '';
        if ($criterion->response_type === 'option') {
            // Cargamos las opciones asociadas al criterio
            $criterion->load('options');
            // Las formateamos como texto (una por línea) para el textarea
            $optionsText = $criterion->options->pluck('option_text')->implode("\n");
        }

        return view('quality.evaluation-criteria.edit', [
            'criterion' => $criterion,
            'optionsText' => $optionsText // Pasamos las opciones formateadas
        ]);
    }

    /**
     * Actualiza el criterio en la base de datos.
     */
    public function update(Request $request, EvaluationCriterion $criterion)
    {
        // 1. Validamos (igual que en store)
        $validatedData = $request->validate([
            'criterion_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'response_type' => 'required|in:numeric,text,option',
            'percentage_weight' => 'nullable|numeric|min:0|max:100',
            'state' => 'required|string|max:255',
            'options_text' => 'required_if:response_type,option|nullable|string',
        ]);

        // 2. Actualizamos el criterio
        $criterionData = collect($validatedData)->except('options_text')->toArray();
        $criterion->update($criterionData);

        // 3. Manejamos las opciones (Borrar existentes y crear nuevas)
        if ($validatedData['response_type'] === 'option') {
            $criterion->options()->delete(); // Borramos las viejas
            if (!empty($validatedData['options_text'])) {
                $optionsArray = array_filter(preg_split('/\r\n|\r|\n/', $validatedData['options_text']));
                if (!empty($optionsArray)) {
                    $optionsToInsert = [];
                    foreach ($optionsArray as $index => $optionText) {
                        $optionsToInsert[] = [
                            'id_evaluation_criteria' => $criterion->id,
                            'option_text' => trim($optionText),
                        ];
                    }
                    OptionCriterion::insert($optionsToInsert);
                }
            }
        } else {
            // Si cambió DESDE option, borrar opciones viejas
            $criterion->options()->delete();
        }

        return redirect()->route('quality.evaluation-criteria.index')
                        ->with('success', 'Criterio de evaluación actualizado con éxito.');
    }

}
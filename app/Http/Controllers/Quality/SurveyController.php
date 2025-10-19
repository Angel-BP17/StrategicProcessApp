<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
// Importamos los NUEVOS modelos
use App\Models\Quality\Survey;
use App\Models\Quality\SurveyQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Quality\SurveyAssignment;

class SurveyController extends Controller
{
    /**
     * Muestra el listado de encuestas (usando el modelo Survey).
     */
    public function index()
    {
        // Usamos el modelo Survey, ordenamos por fecha de creación
        $surveys = Survey::orderBy('created_at', 'desc')->get();

        // La vista ahora está en quality.surveys.index
        return view('quality.surveys.index', ['surveys' => $surveys]);
    }

    /**
     * Muestra el formulario para crear una nueva encuesta.
     */
    public function create()
    {
        // Podríamos pasar aquí los tipos de 'target_type' si quisiéramos un <select>
        // $targetTypes = ['students', 'teachers', 'graduates', 'companies'];
        // return view('quality.surveys.create', ['targetTypes' => $targetTypes]);

        // La vista ahora está en quality.surveys.create
        return view('quality.surveys.create');
    }

    /**
     * Guarda la nueva encuesta en la tabla 'surveys'.
     */
    public function store(Request $request)
    {
        // Actualizamos la validación a los campos de la tabla 'surveys'
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string', // Descripción es opcional
            'target_type' => 'required|string|max:100', // Campo importante de 'surveys'
            'status' => 'required|in:draft,active,closed', // Campo importante de 'surveys'
        ]);

        // Añadimos el ID del usuario creador
        $validatedData['created_by_user_id'] = Auth::id();

        // Usamos el modelo Survey para crear el registro
        Survey::create($validatedData);

        // Redirigimos a la nueva ruta con nombre
        return redirect()->route('quality.surveys.index')
                         ->with('success', 'Encuesta creada con éxito.');
    }

    /**
     * Muestra la página para diseñar una encuesta (añadir/editar preguntas).
     * Usamos el modelo Survey.
     */
    public function design(Survey $survey) // <-- Cambiado a Survey
    {
        // Cargamos las preguntas (ahora la relación se llama 'questions')
        // y las opciones de esas preguntas (usando el modelo SurveyOption)
        $survey->load('questions.options');

        // La vista ahora está en quality.surveys.design
        return view('quality.surveys.design', [
            'survey' => $survey
        ]);
    }


    /**
 * Elimina una encuesta completa y sus elementos relacionados (preguntas, opciones, etc.).
 */
    public function destroy(Survey $survey)
    {
        // El modelo Survey tiene relaciones (questions, assignments).
        // Si las FKs en la BD tienen ON DELETE CASCADE, borrar la encuesta
        // borrará automáticamente sus preguntas, opciones, respuestas y asignaciones.
        // Si no, tendrías que borrarlas manualmente aquí antes:
        // $survey->questions()->options()->delete(); // Borrar opciones
        // $survey->questions()->answers()->delete(); // Borrar respuestas
        // $survey->questions()->delete();            // Borrar preguntas
        // $survey->assignments()->delete();          // Borrar asignaciones

        $survey->delete(); // Elimina la encuesta

        return redirect()->route('quality.surveys.index')
                        ->with('success', 'Encuesta eliminada con éxito.');
    }

    /**
     * Muestra el formulario para editar los datos básicos de una encuesta.
     */
    public function edit(Survey $survey)
    {
        // Podríamos pasar los $targetTypes si quisiéramos
        return view('quality.surveys.edit', [
            'survey' => $survey
        ]);
    }

    /**
     * Actualiza los datos básicos de la encuesta en la base de datos.
     */
    public function update(Request $request, Survey $survey)
    {
        // Misma validación que en 'store', excepto 'created_by_user_id'
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_type' => 'required|string|max:100',
            'status' => 'required|in:draft,active,closed',
        ]);

        // Actualizamos el registro existente
        $survey->update($validatedData);

        // Redirigimos de vuelta al listado
        return redirect()->route('quality.surveys.index')
                         ->with('success', 'Encuesta actualizada con éxito.');
    }

    /**
 * Muestra el formulario/interfaz para asignar una encuesta a usuarios.
 */
    public function showAssignForm(Survey $survey)
    {
        // 1. Verificamos que la encuesta esté 'activa' para poder asignarla (opcional pero recomendado)
        // if ($survey->status !== 'active') {
        //     return redirect()->route('quality.surveys.index')->with('error', 'Solo se pueden asignar encuestas activas.');
        // }

        // 2. Cargamos la lista de usuarios a los que se puede asignar la encuesta.
        //    Aquí filtramos por el 'target_type' definido en la encuesta.
        //    (¡Necesitarás ajustar la lógica según cómo almacenes los roles/tipos de usuario!)
        $usersToAssign = collect(); // Colección vacía por defecto
        switch ($survey->target_type) {
            case 'students':
                $usersToAssign = User::whereHas('studentProfile')->orderBy('full_name')->get();
                break;
            case 'teachers':
                $usersToAssign = User::whereHas('instructorProfile')->orderBy('full_name')->get();
                break;
            case 'graduates':
                $usersToAssign = User::whereHas('graduateRecord')->orderBy('full_name')->get();
                break;
            default:
                $usersToAssign = User::where('status', 'active')->orderBy('full_name')->get();
                break;
        }

        // 3. (Opcional) Cargamos los usuarios YA asignados para no mostrarlos o marcarlos
        $assignedUserIds = $survey->assignments()->pluck('user_id')->toArray();

        // 4. Enviamos los datos a la nueva vista de asignación
        return view('quality.surveys.assign', [
            'survey' => $survey,
            'usersToAssign' => $usersToAssign,
            'assignedUserIds' => $assignedUserIds,
        ]);
    }

   
    public function storeAssignments(Request $request, Survey $survey)
    {
        // 1. Validamos que 'user_ids' sea un array (puede estar vacío)
        $validatedData = $request->validate([
            'user_ids' => 'nullable|array', // Acepta un array o null si no se selecciona ninguno
            'user_ids.*' => 'exists:users,id', // Valida que cada ID en el array exista en la tabla users
        ]);

        $selectedUserIds = $validatedData['user_ids'] ?? []; // Obtiene los IDs seleccionados o un array vacío

        // 2. Obtener los IDs que YA estaban asignados a esta encuesta
        $existingAssignedUserIds = $survey->assignments()->pluck('user_id')->toArray();

        // 3. Calcular qué usuarios NUEVOS hay que asignar
        $userIdsToAssign = array_diff($selectedUserIds, $existingAssignedUserIds);

        // 4. Calcular qué usuarios hay que QUITAR (des-asignar)
        $userIdsToRemove = array_diff($existingAssignedUserIds, $selectedUserIds);

        // 5. Crear las nuevas asignaciones
        if (!empty($userIdsToAssign)) {
            $assignmentsToInsert = [];
            foreach ($userIdsToAssign as $userId) {
                $assignmentsToInsert[] = [
                    'survey_id' => $survey->id,
                    'user_id' => $userId,
                    'status' => 'pending', // Estado inicial
                    'assigned_at' => now(),
                    'created_at' => now(), // Asegurarse si timestamps=true
                    'updated_at' => now(), // Asegurarse si timestamps=true
                ];
            }
            SurveyAssignment::insert($assignmentsToInsert);
        }

        // 6. Eliminar las asignaciones de los usuarios des-seleccionados
        if (!empty($userIdsToRemove)) {
            $survey->assignments()->whereIn('user_id', $userIdsToRemove)->delete();
        }

        // 7. Redirigir de vuelta a la página de asignación con mensaje de éxito
        return redirect()->route('quality.surveys.assign.show', $survey)
                        ->with('success', 'Asignaciones actualizadas con éxito.');
    }
}
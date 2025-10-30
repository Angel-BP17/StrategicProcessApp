<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Quality\Survey;
use App\Models\Quality\SurveyAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $surveys,
        ]);
    }

    public function create()
    {
        return response()->json();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_type' => 'required|string|max:100',
            'status' => 'required|in:draft,active,closed',
        ]);

        $validatedData['created_by_user_id'] = Auth::id();

        $survey = Survey::create($validatedData);

        return response()->json([
            'message' => 'Encuesta creada con éxito.',
            'data' => $survey,
        ], 201);
    }

    public function design(Survey $survey)
    {
        $survey->load('questions.options');

        return response()->json([
            'survey' => $survey,
        ]);
    }

    public function edit(Survey $survey)
    {
        return response()->json([
            'survey' => $survey,
        ]);
    }

    public function update(Request $request, Survey $survey)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_type' => 'required|string|max:100',
            'status' => 'required|in:draft,active,closed',
        ]);

        $survey->update($validatedData);

        return response()->json([
            'message' => 'Encuesta actualizada con éxito.',
            'data' => $survey->fresh(),
        ]);
    }

    public function destroy(Survey $survey)
    {
        $survey->delete();

        return response()->json([
            'message' => 'Encuesta eliminada con éxito.',
        ]);
    }

    public function showAssignForm(Survey $survey)
    {
        $usersToAssign = collect();
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

        $assignedUserIds = $survey->assignments()->pluck('user_id')->toArray();

        return response()->json([
            'survey' => $survey,
            'usersToAssign' => $usersToAssign,
            'assignedUserIds' => $assignedUserIds,
        ]);
    }

    public function storeAssignments(Request $request, Survey $survey)
    {
        $validatedData = $request->validate([
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $selectedUserIds = $validatedData['user_ids'] ?? [];
        $existingAssignedUserIds = $survey->assignments()->pluck('user_id')->toArray();

        $userIdsToAssign = array_diff($selectedUserIds, $existingAssignedUserIds);
        $userIdsToRemove = array_diff($existingAssignedUserIds, $selectedUserIds);

        if (!empty($userIdsToAssign)) {
            $assignmentsToInsert = [];
            foreach ($userIdsToAssign as $userId) {
                $assignmentsToInsert[] = [
                    'survey_id' => $survey->id,
                    'user_id' => $userId,
                    'status' => 'pending',
                    'assigned_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            SurveyAssignment::insert($assignmentsToInsert);
        }

        if (!empty($userIdsToRemove)) {
            $survey->assignments()->whereIn('user_id', $userIdsToRemove)->delete();
        }

        return response()->json([
            'message' => 'Asignaciones actualizadas con éxito.',
            'assigned' => array_values($userIdsToAssign),
            'removed' => array_values($userIdsToRemove),
        ]);
    }
}

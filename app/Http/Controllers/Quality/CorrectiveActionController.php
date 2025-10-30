<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Quality\CorrectiveAction;
use App\Models\Quality\Finding;
use App\Models\User;
use Illuminate\Http\Request;

class CorrectiveActionController extends Controller
{
    public function store(Request $request, Finding $finding)
    {
        $validatedData = $request->validate([
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'required|date',
        ]);

        $validatedData['finding_id'] = $finding->id;
        $validatedData['status'] = 'pending';

        $action = CorrectiveAction::create($validatedData);

        return response()->json([
            'message' => 'Acción correctiva registrada con éxito.',
            'data' => $action,
        ], 201);
    }

    public function edit(CorrectiveAction $action)
    {
        $users = User::orderBy('full_name')->get();
        $action->load('finding');

        return response()->json([
            'action' => $action,
            'users' => $users,
            'finding' => $action->finding,
        ]);
    }

    public function update(Request $request, CorrectiveAction $action)
    {
        $validatedData = $request->validate([
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'engagement_date' => 'nullable|date',
            'completion_date' => 'nullable|date|after_or_equal:engagement_date',
        ]);

        if ($validatedData['status'] === 'completed' && empty($validatedData['completion_date'])) {
            $validatedData['completion_date'] = now();
        } elseif ($validatedData['status'] !== 'completed') {
            $validatedData['completion_date'] = null;
        }

        $action->update($validatedData);

        return response()->json([
            'message' => 'Acción correctiva actualizada con éxito.',
            'data' => $action->fresh(),
        ]);
    }

    public function destroy(CorrectiveAction $action)
    {
        $action->delete();

        return response()->json([
            'message' => 'Acción correctiva eliminada con éxito.',
        ]);
    }
}

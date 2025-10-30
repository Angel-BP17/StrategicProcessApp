<?php

namespace App\Http\Controllers\Collaboration;

use App\Http\Controllers\Controller;
use App\Models\Collaboration\Channel;
use App\Models\Collaboration\Task;
use App\Models\Collaboration\TaskAssignment;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request, Channel $channel)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
            'assignee_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $task = $channel->tasks()->create([
            'title' => $data['title'],
            'due_date' => $data['due_date'] ?? null,
            'status' => 'open',
            'created_by_user_id' => $request->user()->id,
        ]);

        if (!empty($data['assignee_id'])) {
            TaskAssignment::create([
                'task_id' => $task->id,
                'user_id' => (int) $data['assignee_id'],
                'assigned_by' => $request->user()->id,
            ]);
        }

        return response()->json([
            'message' => 'Tarea creada correctamente.',
            'data' => $task,
        ], 201);
    }

    public function toggle(Task $task)
    {
        $task->update(['status' => $task->status === 'done' ? 'open' : 'done']);

        return response()->json([
            'message' => 'Estado de la tarea actualizado.',
            'data' => $task->fresh(),
        ]);
    }

    public function destroy(Task $task)
    {
        $user = auth()->user();
        $role = optional($task->channel)->roleOf($user->id);
        abort_unless($task->created_by_user_id === $user->id || in_array($role, ['admin', 'moderator']), 403);
        $task->delete();

        return response()->json([
            'message' => 'Tarea eliminada correctamente.',
        ]);
    }
}

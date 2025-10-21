<?php

namespace App\Http\Controllers\Collaboration;

use App\Http\Controllers\Controller;
use App\Models\Collaboration\Channel;
use App\Models\Collaboration\Task;
use App\Models\Collaboration\TaskAssignment;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $r, Channel $channel)
    {
        $data = $r->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'priority' => ['nullable', 'string', 'max:50'],
            'assignees' => ['array'],
            'assignees.*' => ['exists:users,id'],
        ]);

        $task = Task::create($data + [
            'channel_id' => $channel->id,
            'status' => 'open',
            'created_by_user_id' => $r->user()->id,
        ]);

        foreach (($data['assignees'] ?? []) as $uid) {
            TaskAssignment::create([
                'task_id' => $task->id,
                'user_id' => $uid,
                'assigned_by_user_id' => $r->user()->id,
                'assigned_at' => now(),
            ]);
            // Notificación Laravel
            if ($user = User::find($uid))
                $user->notify(new TaskAssignedNotification($task));
        }

        return back()->with('ok', 'Tarea creada');
    }

    public function toggle(Task $task)
    {
        $task->update(['status' => $task->status === 'done' ? 'open' : 'done']);
        return back();
    }

    public function destroy(Task $task)
    {
        $user = auth()->user();
        $role = optional($task->channel)->roleOf($user->id);
        abort_unless($task->created_by_user_id === $user->id || in_array($role, ['admin', 'moderator']), 403);
        $task->delete();
        return back();
    }
}

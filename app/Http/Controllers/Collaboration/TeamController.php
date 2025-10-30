<?php

namespace App\Http\Controllers\Collaboration;

use App\Http\Controllers\Controller;
use App\Models\Collaboration\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function store(Request $request)
    {
        $team = Team::create($request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
        ]) + ['members' => [['user_id' => $request->user()->id, 'role' => 'admin']]]);

        return response()->json([
            'message' => 'Equipo creado.',
            'data' => $team,
        ], 201);
    }

    public function join(Request $request, Team $team)
    {
        if (method_exists($team, 'addMember')) {
            $team->addMember($request->user()->id, 'member');
        }

        return response()->json([
            'message' => 'Te has unido al equipo.',
        ]);
    }

    public function leave(Request $request, Team $team)
    {
        if (method_exists($team, 'removeMember')) {
            $team->removeMember($request->user()->id);
        }

        return response()->json([
            'message' => 'Has salido del equipo.',
        ]);
    }
}

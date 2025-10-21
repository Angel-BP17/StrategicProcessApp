<?php

namespace App\Http\Controllers\Collaboration;

use App\Http\Controllers\Controller;
use App\Models\Collaboration\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function store(Request $r)
    {
        $t = Team::create($r->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
        ]) + ['members' => [['user_id' => $r->user()->id, 'role' => 'admin']]]); // si tienes JSON
        return back()->with('ok', 'Equipo creado');
    }
    public function join(Request $r, Team $team)
    {
        if (method_exists($team, 'addMember'))
            $team->addMember($r->user()->id, 'member');
        return back();
    }
    public function leave(Request $r, Team $team)
    {
        if (method_exists($team, 'removeMember'))
            $team->removeMember($r->user()->id);
        return redirect()->route('collab.index');
    }
}

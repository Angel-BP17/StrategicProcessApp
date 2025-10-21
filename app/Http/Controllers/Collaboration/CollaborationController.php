<?php

namespace App\Http\Controllers\Collaboration;

use App\Http\Controllers\Controller;
use App\Models\Collaboration\Channel;
use App\Models\Collaboration\Team;
use Illuminate\Http\Request;

class CollaborationController extends Controller
{
    public function index(Request $r)
    {
        $user = $r->user();

        // Equipos que el usuario integra (si no tienes JSON de miembros en teams, lista todos)
        $teams = Team::orderBy('name')->get()->filter(
            fn($t) => method_exists($t, 'hasMember') ? $t->hasMember($user->id) : true
        )->values();

        // Canal activo
        $channel = null;

        if ($r->filled('channel')) {
            $channel = Channel::with(['messages.user', 'tasks.assignments'])
                ->findOrFail((int) $r->query('channel'));
        } else {
            $firstTeam = $teams->first(); // <- SIN optional()
            if ($firstTeam) {
                $channel = $firstTeam->channels()->orderBy('id')->first();
            } else {
                $channel = Channel::query()->orderBy('id')->first();
            }

            if ($channel) {
                $channel->load(['messages.user', 'tasks.assignments']);
            }
        }

        return view('collaboration.index', compact('teams', 'channel'));
    }
}

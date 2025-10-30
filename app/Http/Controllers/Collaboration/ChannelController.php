<?php

namespace App\Http\Controllers\Collaboration;

use App\Http\Controllers\Controller;
use App\Models\Collaboration\Channel;
use App\Models\Collaboration\Team;
use App\Models\Planning\StrategicPlan;
use App\Models\User;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();
        $users = User::orderBy('full_name')->get(['id', 'full_name', 'email']);

        // Permite abrir el formulario solo a admins (sin team específico todavía)
        $raw = $user->role ?? $user->roles ?? [];
        $roles = is_array($raw) ? $raw : [$raw];
        abort_unless(in_array('admin', $roles, true), 403);

        $plans = StrategicPlan::orderByDesc('start_date')
            ->get(['id', 'title', 'start_date', 'end_date', 'status']);

        // Listar equipos existentes para el selector
        $teams = Team::orderBy('name')->get(['id', 'name', 'description', 'members']);

        return view('collaboration.channels.create', compact('teams', 'users', 'plans'));
    }
    public function store(Request $request)
    {
        $user = $request->user();

        $members = collect($request->input('team_members', []))
            ->map(fn($m) => ['user_id' => (int) ($m['user_id'] ?? 0), 'role' => $m['role'] ?? 'member'])
            ->filter(fn($m) => $m['user_id'] > 0)
            ->values()
            ->all();

        // Asegura que el creador esté como admin al menos una vez
        if (!collect($members)->pluck('user_id')->contains($user->id)) {
            array_unshift($members, ['user_id' => $user->id, 'role' => 'admin']);
        }

        $data = $request->validate([
            // Modo: existing/new
            'team_mode' => ['required', 'in:existing,new'],

            // Equipo existente
            'team_id' => ['nullable', 'integer', 'exists:teams,id'],

            // Nuevo equipo
            'team_name' => ['nullable', 'string', 'max:150'],
            'team_description' => ['nullable', 'string'],

            // Canal
            'name' => ['required', 'string', 'max:255'],
            'channel_type' => ['nullable', 'string', 'max:100'], // p.ej. public/private
            'related_plan_id' => ['nullable', 'integer', 'exists:strategic_plans,id'],
        ]);

        // Resolver equipo
        if ($data['team_mode'] === 'existing') {
            $team = Team::findOrFail((int) $data['team_id']);
        } else {
            // Creamos equipo y añadimos al creador como admin del equipo (solo a nivel JSON)
            $team = Team::create([
                'name' => $data['team_name'],
                'description' => $data['team_description'] ?? null,
                'members' => $members,
            ]);
        }


        // Crear canal. Dejamos al creador como moderator del canal.
        $channel = Channel::create([
            'team_id' => $team->id,
            'name' => $data['name'],
            'channel_type' => $data['channel_type'] ?? null,
            'members' => [
                ['user_id' => $user->id, 'role' => 'moderator']
            ],
            'created_by_user_id' => $user->id,
            'related_plan_id' => $data['related_plan_id'] ?? null,
        ]);

        return redirect()
            ->route('collab.index', ['channel' => $channel->id])
            ->with('ok', 'Canal creado correctamente');
    }

    public function join(Request $r, Channel $channel)
    {
        if (method_exists($channel, 'addMember'))
            $channel->addMember($r->user()->id, 'member');
        return back();
    }
    public function leave(Request $r, Channel $channel)
    {
        if (method_exists($channel, 'removeMember'))
            $channel->removeMember($r->user()->id);
        return redirect()->route('collab.index');
    }

    public function ban(Channel $channel, User $user)
    {
        if (method_exists($channel, 'setRole'))
            $channel->setRole($user->id, 'banned');
        return back()->with('ok', 'Usuario bloqueado');
    }
    public function unban(Channel $channel, User $user)
    {
        if (method_exists($channel, 'setRole'))
            $channel->setRole($user->id, 'member');
        return back()->with('ok', 'Usuario desbloqueado');
    }
}

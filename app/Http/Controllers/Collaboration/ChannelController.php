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

        $raw = $user->role ?? $user->roles ?? [];
        $roles = is_array($raw) ? $raw : [$raw];
        abort_unless(in_array('admin', $roles, true), 403);

        $plans = StrategicPlan::orderByDesc('start_date')
            ->get(['id', 'title', 'start_date', 'end_date', 'status']);

        $teams = Team::orderBy('name')->get(['id', 'name', 'description', 'members']);

        return response()->json([
            'teams' => $teams,
            'users' => $users,
            'plans' => $plans,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $members = collect($request->input('team_members', []))
            ->map(fn ($m) => ['user_id' => (int) ($m['user_id'] ?? 0), 'role' => $m['role'] ?? 'member'])
            ->filter(fn ($m) => $m['user_id'] > 0)
            ->values()
            ->all();

        if (!collect($members)->pluck('user_id')->contains($user->id)) {
            array_unshift($members, ['user_id' => $user->id, 'role' => 'admin']);
        }

        $data = $request->validate([
            'team_mode' => ['required', 'in:existing,new'],
            'team_id' => ['nullable', 'integer', 'exists:teams,id'],
            'team_name' => ['nullable', 'string', 'max:150'],
            'team_description' => ['nullable', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'channel_type' => ['nullable', 'string', 'max:100'],
            'related_plan_id' => ['nullable', 'integer', 'exists:strategic_plans,id'],
        ]);

        if ($data['team_mode'] === 'existing') {
            $team = Team::findOrFail((int) $data['team_id']);
        } else {
            $team = Team::create([
                'name' => $data['team_name'],
                'description' => $data['team_description'] ?? null,
                'members' => $members,
            ]);
        }

        $channel = Channel::create([
            'team_id' => $team->id,
            'name' => $data['name'],
            'channel_type' => $data['channel_type'] ?? null,
            'members' => [
                ['user_id' => $user->id, 'role' => 'moderator'],
            ],
            'created_by_user_id' => $user->id,
            'related_plan_id' => $data['related_plan_id'] ?? null,
        ]);

        return response()->json([
            'message' => 'Canal creado correctamente.',
            'data' => $channel,
        ], 201);
    }

    public function join(Request $request, Channel $channel)
    {
        if (method_exists($channel, 'addMember')) {
            $channel->addMember($request->user()->id, 'member');
        }

        return response()->json([
            'message' => 'Te has unido al canal.',
        ]);
    }

    public function leave(Request $request, Channel $channel)
    {
        if (method_exists($channel, 'removeMember')) {
            $channel->removeMember($request->user()->id);
        }

        return response()->json([
            'message' => 'Has salido del canal.',
        ]);
    }

    public function ban(Channel $channel, User $user)
    {
        if (method_exists($channel, 'setRole')) {
            $channel->setRole($user->id, 'banned');
        }

        return response()->json([
            'message' => 'Usuario bloqueado.',
        ]);
    }

    public function unban(Channel $channel, User $user)
    {
        if (method_exists($channel, 'setRole')) {
            $channel->setRole($user->id, 'member');
        }

        return response()->json([
            'message' => 'Usuario desbloqueado.',
        ]);
    }
}

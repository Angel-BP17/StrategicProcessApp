<?php

namespace App\Http\Controllers\Collaboration;

use App\Http\Controllers\Controller;
use App\Models\Collaboration\Channel;
use App\Models\Collaboration\Team;
use App\Models\User;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function store(Request $r, Team $team)
    {
        $data = $r->validate([
            'name' => ['required', 'string', 'max:255'],
            'channel_type' => ['nullable', 'string', 'max:60'],
            'description' => ['nullable', 'string'],
            'topic' => ['nullable', 'string', 'max:200'],
        ]);
        $channel = Channel::create($data + [
            'team_id' => $team->id,
            'created_by_user_id' => $r->user()->id,
            'members' => [['user_id' => $r->user()->id, 'role' => 'moderator']]
        ]);
        return redirect()->route('collab.index', ['channel' => $channel->id]);
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

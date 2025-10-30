<?php

namespace App\Http\Controllers\Collaboration;

use App\Http\Controllers\Controller;
use App\Models\Collaboration\Channel;
use App\Models\Documentation\DocumentVersion;
use App\Models\User;
use Illuminate\Http\Request;

class CollaborationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $userId = (int) $user->id;

        $channelId = (int) ($request->route('channel') ?? $request->integer('channel') ?? 0);
        $channel = $channelId
            ? Channel::with([
                'messages.user',
                'messages.replies.user',
                'tasks',
                'team',
            ])->find($channelId)
            : null;

        $channelName = $channel->name ?? '—';
        $channelMeta = $channel->topic
            ?? ($channel->description ?? 'Selecciona o crea un canal para empezar a colaborar.');

        $messages = $channel?->messages ?? collect();
        $tasks = $channel?->tasks ?? collect();

        $files = collect();
        if ($messages->count() > 0) {
            $files = DocumentVersion::query()
                ->where('linked_type', 'message')
                ->whereIn('linked_id', $messages->pluck('id'))
                ->orderByDesc('uploaded_at')
                ->limit(6)
                ->get();
        }

        $allChannels = Channel::with('team')->orderBy('name')->get();

        $ownedChannels = $allChannels
            ->filter(fn ($c) => (int) ($c->created_by_user_id ?? 0) === $userId)
            ->values();

        $memberChannels = $allChannels
            ->filter(fn ($c) => $c->isMember($userId) && (int) ($c->created_by_user_id ?? 0) !== $userId)
            ->values();

        $publicChannels = Channel::with('team')
            ->where('channel_type', 'public')
            ->orderBy('name')
            ->get();

        $publicChannelsToJoin = $publicChannels
            ->filter(fn ($c) => !$c->isMember($userId) && (int) ($c->created_by_user_id ?? 0) !== $userId)
            ->values();

        $assignableUsers = collect();
        if ($channel && $channel->team) {
            $memberIds = collect($channel->team->members ?? [])->pluck('user_id')->filter()->unique();
            $assignableUsers = User::whereIn('id', $memberIds)->orderBy('full_name')->get(['id', 'full_name']);
        }

        $raw = $user->role ?? ($user->roles ?? []);
        $roles = is_array($raw) ? $raw : [$raw];
        $isAdmin = in_array('admin', $roles, true);

        return response()->json([
            'channel' => $channel,
            'channelId' => $channelId,
            'channelName' => $channelName,
            'channelMeta' => $channelMeta,
            'messages' => $messages,
            'tasks' => $tasks,
            'files' => $files,
            'userId' => $userId,
            'ownedChannels' => $ownedChannels,
            'memberChannels' => $memberChannels,
            'publicChannelsToJoin' => $publicChannelsToJoin,
            'assignableUsers' => $assignableUsers,
            'isAdmin' => $isAdmin,
        ]);
    }
}

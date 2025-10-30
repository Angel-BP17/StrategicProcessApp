<?php

namespace App\Http\Controllers\Collaboration;

use App\Http\Controllers\Controller;
use App\Models\Collaboration\Channel;
use App\Models\Collaboration\Team;
use App\Models\Documentation\DocumentVersion;
use App\Models\User;
use Illuminate\Http\Request;

class CollaborationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $userId = (int) $user->id;

        // channel puede venir como query param (?channel=1) o como route param
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
            ->filter(fn($c) => (int) ($c->created_by_user_id ?? 0) === $userId)
            ->values();

        $memberChannels = $allChannels
            ->filter(fn($c) => $c->isMember($userId) && (int) ($c->created_by_user_id ?? 0) !== $userId)
            ->values();

        // Canales públicos (ajusta el campo según tu modelo: is_public / visibility='public' / etc.)
        $publicChannels = Channel::with('team')
            ->where('channel_type', 'public')
            ->orderBy('name')
            ->get();

        // Canales públicos disponibles para unirse (sin ser miembro ni dueño)
        $publicChannelsToJoin = $publicChannels
            ->filter(fn($c) => !$c->isMember($userId) && (int) ($c->created_by_user_id ?? 0) !== $userId)
            ->values();

        // Usuarios asignables: miembros del canal actual (si hay)
        $assignableUsers = collect();
        if ($channel && $channel->team) {
            $memberIds = collect($channel->team->members ?? [])->pluck('user_id')->filter()->unique();
            $assignableUsers = User::whereIn('id', $memberIds)->orderBy('full_name')->get(['id', 'full_name']);
        }

        // Roles / admin
        $raw = $user->role ?? ($user->roles ?? []);
        $roles = is_array($raw) ? $raw : [$raw];
        $isAdmin = in_array('admin', $roles, true);

        return view('collaboration.index', compact(
            'channel',
            'channelId',
            'channelName',
            'channelMeta',
            'messages',
            'tasks',
            'files',
            'userId',
            'ownedChannels',
            'memberChannels',
            'publicChannelsToJoin',
            'assignableUsers',
            'isAdmin'
        ));
    }
}

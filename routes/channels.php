<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('channel.{channelId}', function ($user, $channelId) {
    // Si tu esquema tiene membresía por JSON en channels.members o por team, ajústalo aquí:
    $channel = Channel::find($channelId);
    if (!$channel)
        return false;

    // Permite si el usuario pertenece al equipo del canal o si el canal es público
    if (method_exists($channel, 'isMember') && $channel->isMember($user->id)) {
        return ['id' => $user->id, 'name' => $user->name ?? $user->email];
    }
    return ['id' => $user->id, 'name' => $user->name ?? $user->email]; // fallback público
});

Broadcast::channel('user.{id}', fn($user, $id) => (int) $user->id === (int) $id);
<?php

namespace App\Policies;

use App\Models\Collaboration\Team;
use App\Models\User;

class ChannelPolicy
{
    public function create(User $user, Team $team): bool
    {
        $roles = $user->roles ?? $user->role ?? [];
        if (is_string($roles)) {
            $roles = [$roles];
        }
        $roles = (array) $roles;

        // 1) admins globales
        if (in_array('admin', (array) ($user->roles ?? []), true))
            return true;

        // 2) moderadores/owners del propio equipo (en el JSON members del team o channel)
        $members = collect($team->members ?? []);
        $role = optional($members->firstWhere('user_id', $user->id))['role'] ?? null;

        return in_array($role, ['admin', 'moderator', 'owner'], true);
    }
}

<?php

namespace App\Support\Documentation;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class DocumentAccess
{
    public const VIEW_ROLES = ['admin', 'quality_manager', 'auditor', 'consultant'];
    public const MANAGE_ROLES = ['admin', 'quality_manager'];

    public static function userHasAnyRole(?Authenticatable $user, array $roles): bool
    {
        if (!$user) {
            return false;
        }

        $rawRoles = $user->roles ?? $user->role ?? [];

        if (is_string($rawRoles)) {
            $decoded = json_decode($rawRoles, true);
            $rawRoles = json_last_error() === JSON_ERROR_NONE && is_array($decoded)
                ? $decoded
                : array_map('trim', explode(',', $rawRoles));
        } elseif ($rawRoles instanceof Collection) {
            $rawRoles = $rawRoles->all();
        }

        $normalizedUserRoles = collect(Arr::wrap($rawRoles))
            ->filter(fn($role) => is_string($role) && $role !== '')
            ->map(fn($role) => strtolower(trim($role)))
            ->unique()
            ->all();

        $normalizedRequested = array_map(fn($role) => strtolower(trim($role)), $roles);

        return count(array_intersect($normalizedRequested, $normalizedUserRoles)) > 0;
    }
}
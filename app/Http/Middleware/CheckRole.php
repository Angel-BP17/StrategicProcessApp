<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$params): Response
    {
        $user = $request->user();
        if (!$user) {
            abort(Response::HTTP_UNAUTHORIZED, 'No autenticado.');
        }

        if (empty($params)) {
            abort(Response::HTTP_FORBIDDEN, 'Roles requeridos no especificados.');
        }

        // modo: any (por defecto) o all
        $mode = in_array(strtolower($params[0]), ['all', 'any'], true) ? strtolower(array_shift($params)) : 'any';

        // roles requeridos normalizados
        $required = array_values(array_filter(array_map(
            fn($r) => strtolower(trim($r)),
            $params
        )));
        if (empty($required)) {
            abort(Response::HTTP_FORBIDDEN, 'No se especificaron roles válidos.');
        }

        // --- obtener y normalizar roles del usuario ---
        // acepta $user->roles (plural) o $user->role (singular)
        $raw = $user->roles ?? $user->role ?? [];

        if (is_string($raw)) {
            // puede ser JSON o CSV
            $decoded = json_decode($raw, true);
            $roles = (json_last_error() === JSON_ERROR_NONE && is_array($decoded))
                ? $decoded
                : array_map('trim', explode(',', $raw));
        } elseif (is_array($raw)) {
            $roles = $raw;
        } elseif ($raw instanceof \Illuminate\Support\Collection) {
            $roles = $raw->all();
        } else {
            $roles = [];
        }

        // a minúsculas y solo strings
        $userRoles = array_values(array_filter(array_map(function ($r) {
            if (is_string($r))
                return strtolower(trim($r));
            if (is_array($r) && isset($r['name']))
                return strtolower(trim($r['name']));
            return null;
        }, $roles)));

        $hasAll = !array_diff($required, $userRoles);
        $hasAny = count(array_intersect($required, $userRoles)) > 0;

        $ok = $mode === 'all' ? $hasAll : $hasAny;
        if (!$ok) {
            abort(Response::HTTP_FORBIDDEN, 'No tienes permiso para acceder a esta ruta.');
        }

        return $next($request);
    }
}

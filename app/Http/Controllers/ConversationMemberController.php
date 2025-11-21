<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use IncadevUns\CoreDomain\Models\Conversation;

class ConversationMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
        // Los permisos se protegen en rutas; aquí puedes duplicar si lo prefieres.
    }

    /**
     * Lista de miembros de la conversación (+pivot timestamps)
     */
    public function index(Conversation $conversation)
    {
        $users = $conversation->users()->withPivot(['created_at', 'updated_at'])->paginate(20);
        return response()->json($users);
    }

    /**
     * Agregar uno o varios usuarios (sin duplicar)
     * Body admite:
     *  - user_id: int
     *  - user_ids: int[]
     */
    public function store(Request $request, Conversation $conversation)
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $ids = [];
        if (!empty($data['user_id'])) {
            $ids[] = (int) $data['user_id'];
        }
        if (!empty($data['user_ids'])) {
            $ids = array_merge($ids, $data['user_ids']);
        }
        $ids = array_values(array_unique($ids));

        if (empty($ids)) {
            return response()->json(['message' => 'No se proporcionaron usuarios.'], 422);
        }

        $conversation->users()->syncWithoutDetaching($ids);

        return response()->json([
            'message' => 'Miembros añadidos.',
            'users' => $conversation->users()->whereIn('users.id', $ids)->get(),
        ], 201);
    }

    /**
     * Remover miembro
     */
    public function destroy(Conversation $conversation, int $user)
    {
        $conversation->users()->detach($user);
        return response()->json(['message' => 'Miembro removido.'], 204);
    }

    /**
     * Reemplazar el conjunto completo de miembros
     * Body:
     *  - user_ids: int[] (obligatorio)
     */
    public function sync(Request $request, Conversation $conversation)
    {
        $data = $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $conversation->users()->sync($data['user_ids']);

        return response()->json([
            'message' => 'Membresía sincronizada.',
            'users' => $conversation->users()->get(),
        ]);
    }
}

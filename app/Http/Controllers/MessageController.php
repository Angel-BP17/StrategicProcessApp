<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use IncadevUns\CoreDomain\Models\Conversation;
use IncadevUns\CoreDomain\Models\Message;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
        $this->middleware(['role:planner_admin']);
        /*
        $this->middleware(['permission:messages.view'])->only(['index', 'show']);
        $this->middleware(['permission:messages.create'])->only(['store']);
        $this->middleware(['permission:messages.update'])->only(['update']);
        $this->middleware(['permission:messages.delete'])->only(['destroy']);*/
    }

    public function index()
    {
        return response()->json(
            Message::with(['conversation', 'user'])->latest('id')->paginate(20)
        );
    }

    public function show(Message $message)
    {
        return response()->json($message->load(['conversation', 'user']));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'conversation_id' => ['required', 'integer', 'exists:conversations,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'content' => ['required', 'string'],
        ]);

        $conversation = Conversation::findOrFail($data['conversation_id']);

        // ✅ El autor debe ser miembro de la conversación
        $isMember = $conversation->users()->whereKey($data['user_id'])->exists();
        if (!$isMember) {
            return response()->json(['message' => 'El usuario no pertenece a la conversación.'], 422);
        }

        $item = Message::create($data);
        return response()->json($item->load(['conversation', 'user']), 201);
    }

    public function update(Request $request, Message $message)
    {
        $data = $request->validate([
            'conversation_id' => ['sometimes', 'integer', 'exists:conversations,id'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'content' => ['sometimes', 'string'],
        ]);

        if (isset($data['conversation_id']) || isset($data['user_id'])) {
            $conversationId = $data['conversation_id'] ?? $message->conversation_id;
            $userId = $data['user_id'] ?? $message->user_id;

            $conversation = Conversation::findOrFail($conversationId);
            $isMember = $conversation->users()->whereKey($userId)->exists();
            if (!$isMember) {
                return response()->json(['message' => 'El usuario no pertenece a la conversación.'], 422);
            }
        }

        $message->update($data);
        return response()->json($message->load(['conversation', 'user']));
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return response()->json(['message' => 'Deleted'], 204);
    }
}

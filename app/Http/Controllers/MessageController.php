<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use IncadevUns\CoreDomain\Models\Message;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
        
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

        $message->update($data);
        return response()->json($message->load(['conversation', 'user']));
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return response()->json(['message' => 'Deleted'], 204);
    }
}

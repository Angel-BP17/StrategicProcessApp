<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use IncadevUns\CoreDomain\Models\Conversation;

class ConversationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
        $this->middleware(['permission:conversations.view'])->only(['index', 'show']);
        $this->middleware(['permission:conversations.create'])->only(['store']);
        $this->middleware(['permission:conversations.update'])->only(['update']);
        $this->middleware(['permission:conversations.delete'])->only(['destroy']);
    }

    public function index()
    {
        return response()->json(Conversation::query()->latest('id')->paginate(20));
    }

    public function show(Conversation $conversation)
    {
        return response()->json($conversation);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        $item = Conversation::create($data);
        return response()->json($item, 201);
    }

    public function update(Request $request, Conversation $conversation)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
        ]);
        $conversation->update($data);
        return response()->json($conversation);
    }

    public function destroy(Conversation $conversation)
    {
        $conversation->delete();
        return response()->json(['message' => 'Deleted'], 204);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use IncadevUns\CoreDomain\Models\MessageFile;

class MessageFileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
        $this->middleware(['role:planner_admin']);
        /*
        $this->middleware(['permission:message_files.view'])->only(['index', 'show']);
        $this->middleware(['permission:message_files.create'])->only(['store']);
        $this->middleware(['permission:message_files.update'])->only(['update']);
        $this->middleware(['permission:message_files.delete'])->only(['destroy']);*/
    }

    public function index()
    {
        return response()->json(MessageFile::with('message')->latest('id')->paginate(20));
    }

    public function show(MessageFile $messageFile)
    {
        return response()->json($messageFile->load('message'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'message_id' => ['required', 'integer', 'exists:messages,id'],
            'type' => ['required', 'string', 'max:50'],
            'path' => ['required', 'string', 'max:500'],
        ]);
        $item = MessageFile::create($data);
        return response()->json($item->load('message'), 201);
    }

    public function update(Request $request, MessageFile $messageFile)
    {
        $data = $request->validate([
            'message_id' => ['sometimes', 'integer', 'exists:messages,id'],
            'type' => ['sometimes', 'string', 'max:50'],
            'path' => ['sometimes', 'string', 'max:500'],
        ]);
        $messageFile->update($data);
        return response()->json($messageFile->load('message'));
    }

    public function destroy(MessageFile $messageFile)
    {
        $messageFile->delete();
        return response()->json(['message' => 'Deleted'], 204);
    }
}

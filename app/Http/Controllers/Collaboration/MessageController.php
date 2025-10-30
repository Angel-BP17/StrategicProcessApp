<?php

namespace App\Http\Controllers\Collaboration;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Collaboration\Channel;
use App\Models\Collaboration\Message;
use App\Models\Documentation\Document;
use App\Models\Documentation\DocumentVersion;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request, Channel $channel)
    {
        if (method_exists($channel, 'roleOf') && $channel->roleOf($request->user()->id) === 'banned') {
            abort(403, 'Bloqueado');
        }

        $data = $request->validate([
            'content' => ['required', 'string', 'max:4000'],
            'parent_id' => ['nullable', 'exists:messages,id'],
            'files.*' => ['file', 'max:10240'],
        ]);

        $message = Message::create([
            'channel_id' => $channel->id,
            'user_id' => $request->user()->id,
            'content' => $data['content'],
            'parent_id' => $data['parent_id'] ?? null,
            'created_at' => now(),
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('collab', 'public');
                $document = Document::create([
                    'title' => $file->getClientOriginalName(),
                    'category' => 'administrative',
                    'entity_type' => 'message',
                    'entity_id' => $message->id,
                    'version' => 1,
                    'status' => 'active',
                    'file_path' => $path,
                    'created_by' => $request->user()->id,
                ]);

                DocumentVersion::create([
                    'document_id' => $document->id,
                    'version_number' => 1,
                    'file_name' => $file->getClientOriginalName(),
                    'storage_path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'uploaded_by_user_id' => $request->user()->id,
                    'uploaded_at' => now(),
                    'linked_type' => 'message',
                    'linked_id' => $message->id,
                    'created_at' => now(),
                ]);
            }
        }

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'message' => 'Mensaje enviado correctamente.',
            'data' => $message->load('user'),
        ], 201);
    }

    public function destroy(Message $message)
    {
        $user = request()->user();
        $role = optional($message->channel)->roleOf($user->id);
        abort_unless($message->user_id === $user->id || in_array($role, ['admin', 'moderator']), 403);
        $message->update(['content' => '[mensaje eliminado]', 'pinned' => false]);

        return response()->json([
            'message' => 'Mensaje eliminado.',
            'data' => $message->fresh(),
        ]);
    }

    public function pin(Message $message)
    {
        $user = request()->user();
        $role = optional($message->channel)->roleOf($user->id);
        abort_unless(in_array($role, ['admin', 'moderator']), 403);
        $message->update(['pinned' => !$message->pinned]);

        return response()->json([
            'message' => 'Estado de fijado actualizado.',
            'data' => $message->fresh(),
        ]);
    }

    public function report(Message $message)
    {
        return response()->json([
            'message' => 'Reporte enviado.',
        ]);
    }
}

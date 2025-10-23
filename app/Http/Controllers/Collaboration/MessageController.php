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
    public function store(Request $r, Channel $channel)
    {
        // canal público o miembro (si tu esquema tiene JSON de miembros)
        if (method_exists($channel, 'roleOf') && $channel->roleOf($r->user()->id) === 'banned')
            abort(403, 'Bloqueado');

        $data = $r->validate([
            'content' => ['required', 'string', 'max:4000'],
            'parent_id' => ['nullable', 'exists:messages,id'],
            'files.*' => ['file', 'max:10240'],
        ]);

        $msg = Message::create([
            'channel_id' => $channel->id,
            'user_id' => $r->user()->id,
            'content' => $data['content'],
            'parent_id' => $data['parent_id'] ?? null,
            'created_at' => now(),
        ]);

        // Adjuntos: usa documents/document_versions
        if ($r->hasFile('files')) {
            foreach ($r->file('files') as $f) {
                $path = $f->store('collab', 'public');
                $doc = Document::create([
                    'title' => $f->getClientOriginalName(),
                    'category' => 'administrative',
                    'entity_type' => 'message',
                    'entity_id' => $msg->id,
                    'version' => 1,
                    'status' => 'active',
                    'file_path' => $path,
                    'created_by' => $r->user()->id,
                ]);
                DocumentVersion::create([
                    'document_id' => $doc->id,
                    'version_number' => 1,
                    'file_name' => $f->getClientOriginalName(),
                    'storage_path' => $path,
                    'mime_type' => $f->getClientMimeType(),
                    'file_size' => $f->getSize(),
                    'uploaded_by_user_id' => $r->user()->id,
                    'uploaded_at' => now(),
                    'linked_type' => 'message',
                    'linked_id' => $msg->id,
                    'created_at' => now(),
                ]);
            }
        }

        // Tiempo real
        broadcast(new MessageSent($msg))->toOthers();

        return back();
    }

    public function destroy(Message $message)
    {
        // autor o moderador
        $user = request()->user();
        $role = optional($message->channel)->roleOf($user->id);
        abort_unless($message->user_id === $user->id || in_array($role, ['admin', 'moderator']), 403);
        $message->update(['content' => '[mensaje eliminado]', 'pinned' => false]);
        return back();
    }

    public function pin(Message $message)
    {
        $user = request()->user();
        $role = optional($message->channel)->roleOf($user->id);
        abort_unless(in_array($role, ['admin', 'moderator']), 403);
        $message->update(['pinned' => !$message->pinned]);
        return back();
    }

    public function report(Message $message)
    {
        // Podrías guardar en logs propios o enviar a un canal admin; aquí solo confirmamos
        return back()->with('ok', 'Reporte enviado');
    }
}

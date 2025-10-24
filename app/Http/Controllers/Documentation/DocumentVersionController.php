<?php

namespace App\Http\Controllers\Documentation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Documentation\StoreDocumentVersionRequest;
use App\Models\Documentation\Document;
use App\Models\Documentation\DocumentVersion;
use App\Services\Documentation\DocumentVersionManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentVersionController extends Controller
{
    public function __construct(private readonly DocumentVersionManager $versionManager)
    {
    }

    public function store(StoreDocumentVersionRequest $request, Document $document): RedirectResponse
    {
        $this->versionManager->createVersion(
            $document,
            $request->file('file'),
            $request->user()->id,
            $request->input('notes')
        );

        return redirect()
            ->route('documents.show', $document)
            ->with('ok', 'Nueva versión registrada correctamente.');
    }

    public function download(Document $document, DocumentVersion $version): StreamedResponse
    {   
        abort_unless($version->document_id === $document->id, 404);

        $disk = $this->versionManager->getDisk();

        if (!Storage::disk($disk)->exists($version->storage_path)) {
            abort(404, 'El archivo solicitado ya no se encuentra disponible.');
        }

        return Storage::disk($disk)->download($version->storage_path, $version->file_name);
    }
}
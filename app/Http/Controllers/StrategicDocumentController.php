<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use IncadevUns\CoreDomain\Models\MediaFile;
use IncadevUns\CoreDomain\Models\StrategicDocument;
use App\Services\GcsStorageService;

class StrategicDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);

        /*
        $this->middleware(['permission:strategic_documents.view'])->only(['index', 'show']);
        $this->middleware(['permission:strategic_documents.create'])->only(['store']);
        $this->middleware(['permission:strategic_documents.update'])->only(['update']);
        $this->middleware(['permission:strategic_documents.delete'])->only(['destroy']);*/
    }

    public function index()
    {
        $paginator = StrategicDocument::query()
            ->with('file')
            ->latest('id')
            ->paginate(20);

        // Adjuntamos URL firmada para el frontend
        $paginator->getCollection()->transform(function ($doc) {
            $signedUrl = $this->signedUrl($doc->file);
            $doc->setAttribute('file_url', $signedUrl);
            $doc->file?->setAttribute('secure_url', $signedUrl);
            return $doc;
        });

        return response()->json($paginator);
    }

    public function show(StrategicDocument $strategicDocument)
    {
        $strategicDocument->load('file');
        $signedUrl = $this->signedUrl($strategicDocument->file);
        $strategicDocument->setAttribute('file_url', $signedUrl);
        $strategicDocument->file?->setAttribute('secure_url', $signedUrl);

        return response()->json($strategicDocument);
    }

    public function store(Request $request)
    {
        // Validate against real columns in strategic_documents
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
            'visibility' => 'nullable|string|in:internal,restricted,public',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
        ]);

        $file = $request->file('file');
        $user = Auth::user();
        $gcs = new GcsStorageService();

        DB::beginTransaction();

        try {
            $upload = $gcs->upload($file, 'strategic-documents');

            // Create media_files entry
            $media = MediaFile::create([
                'provider' => 'gcs',
                'disk' => 'gcs',
                'public_id' => $upload['public_id'] ?? null,
                'resource_type' => $upload['resource_type'] ?? null,
                'format' => $upload['format'] ?? null,
                'secure_url' => $upload['secure_url'] ?? ($upload['url'] ?? null),
                'thumbnail_url' => null,
                'folder' => $upload['folder'] ?? null,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'bytes' => $upload['bytes'] ?? null,
                'width' => $upload['width'] ?? null,
                'height' => $upload['height'] ?? null,
                'meta' => null,
                'uploaded_by' => $user?->id,
            ]);

            // Create strategic document
            $type = $validated['type'] ?? null;
            if ($type && !in_array($type, \IncadevUns\CoreDomain\Enums\MediaType::values(), true)) {
                $type = \IncadevUns\CoreDomain\Enums\MediaType::Document->value;
            }

            $document = StrategicDocument::create([
                'name' => $validated['name'],
                'type' => $type,
                'category' => $validated['category'] ?? null,
                'description' => $validated['description'] ?? null,
                'visibility' => $validated['visibility'] ?? 'internal',
                'file_id' => $media->id,
            ]);
            if ($user?->id) {
                // uploaded_by no está en $fillable, asignamos directo
                $document->uploaded_by = $user->id;
                $document->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Documento guardado correctamente',
                'document' => tap($document->load('file'), function ($doc) {
                    $signedUrl = $this->signedUrl($doc->file);
                    $doc->setAttribute('file_url', $signedUrl);
                    $doc->file?->setAttribute('secure_url', $signedUrl);
                }),
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Error al guardar documento', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Error al guardar el documento',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, StrategicDocument $strategicDocument)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', 'string', 'max:50'],
            'category' => ['sometimes', 'nullable', 'string', 'max:100'],
            'visibility' => ['sometimes', 'string', 'in:internal,restricted,public'],
            'description' => ['sometimes', 'nullable', 'string'],
            'file' => ['sometimes', 'file', 'mimes:pdf,doc,docx,png,jpg,jpeg', 'max:10240'],
        ]);

        $user = $request->user();
        $strategicDocument->load('file');
        $oldFile = $strategicDocument->file;
        $gcs = new GcsStorageService();

        DB::transaction(function () use ($request, $data, $user, $strategicDocument, $oldFile, $gcs) {
            // If a new file arrives, upload and replace
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                $upload = $gcs->upload($file, 'strategic-documents');

                $mediaFile = MediaFile::create([
                    'provider' => 'gcs',
                    'disk' => 'gcs',
                    'public_id' => $upload['public_id'] ?? null,
                    'resource_type' => $upload['resource_type'] ?? null,
                    'format' => $upload['format'] ?? null,
                    'secure_url' => $upload['secure_url'] ?? ($upload['url'] ?? null),
                    'thumbnail_url' => null,
                    'folder' => $upload['folder'] ?? null,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'bytes' => $upload['bytes'] ?? null,
                    'width' => $upload['width'] ?? null,
                    'height' => $upload['height'] ?? null,
                    'meta' => null,
                    'uploaded_by' => $user?->id,
                ]);

                $strategicDocument->file_id = $mediaFile->id;

                if ($oldFile && $oldFile->public_id) {
                    // Delete from GCS using stored path as key
                    $gcs->delete($oldFile->public_id);
                    $oldFile->delete();
                }
            }

            unset($data['file']);
            if (isset($data['type']) && !in_array($data['type'], \IncadevUns\CoreDomain\Enums\MediaType::values(), true)) {
                $data['type'] = \IncadevUns\CoreDomain\Enums\MediaType::Document->value;
            }
            $strategicDocument->fill($data);
            $strategicDocument->save();
        });

        $strategicDocument->load('file');
        $signedUrl = $this->signedUrl($strategicDocument->file);
        $strategicDocument->setAttribute('file_url', $signedUrl);
        $strategicDocument->file?->setAttribute('secure_url', $signedUrl);

        return response()->json($strategicDocument);
    }

    public function destroy(StrategicDocument $strategicDocument)
    {
        $strategicDocument->load('file');
        $file = $strategicDocument->file;

        DB::transaction(function () use ($strategicDocument, $file) {
            if ($file && $file->public_id) {
                // Delete from GCS by stored path
                (new GcsStorageService())->delete($file->public_id);
            }

            $strategicDocument->delete();

            if ($file) {
                $file->delete();
            }
        });

        return response()->json(null, 204);
    }

    /**
     * Generate a short-lived signed URL for the given media file.
     */
    private function signedUrl(?MediaFile $file, int $minutes = 60): ?string
    {
        if (!$file || !$file->public_id) {
            return null;
        }

        return (new GcsStorageService())->temporaryUrl(
            $file->public_id,
            $minutes,
            ['contentType' => $file->mime_type]
        );
    }
}

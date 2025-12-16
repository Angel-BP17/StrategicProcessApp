<?php

namespace App\Http\Controllers;

use Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use IncadevUns\CoreDomain\Models\MediaFile;
use IncadevUns\CoreDomain\Models\StrategicDocument;

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
        return response()->json(StrategicDocument::query()->latest('id')->paginate(20));
    }

    public function show(StrategicDocument $strategicDocument)
    {
        $strategicDocument->load('file');
        $secureUrl = $strategicDocument->file->secure_url ?? ($strategicDocument->file->url ?? null);
        if ($secureUrl) {
            $strategicDocument->setAttribute('file_url', $secureUrl);
        }

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

        DB::beginTransaction();

        try {
            $cloudUrl = config('cloudinary.cloud_url')
                ?? config('filesystems.disks.cloudinary.url')
                ?? env('CLOUDINARY_URL');
            if (!$cloudUrl) {
                throw new \RuntimeException('Cloudinary URL not configured');
            }

            // Upload to Cloudinary
            $upload = Cloudinary::uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => 'uns/strategic-documents',
                    'resource_type' => 'auto',
                    'type' => 'upload'
                ]
            );

            // Create media_files entry
            $media = MediaFile::create([
                'provider' => 'cloudinary',
                'disk' => 'cloudinary',
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
                'document' => $document->load('file'),
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

        DB::transaction(function () use ($request, $data, $user, $strategicDocument, $oldFile) {
            // If a new file arrives, upload and replace
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                $cloudUrl = config('cloudinary.cloud_url')
                    ?? config('filesystems.disks.cloudinary.url')
                    ?? env('CLOUDINARY_URL');
                if (!$cloudUrl) {
                    throw new \RuntimeException('Cloudinary URL not configured');
                }

                $upload = Cloudinary::uploadApi()->upload(
                    $file->getRealPath(),
                    [
                        'folder' => 'uns/strategic-documents',
                        'resource_type' => 'auto',
                    ]
                );

                $mediaFile = MediaFile::create([
                    'provider' => 'cloudinary',
                    'disk' => 'cloudinary',
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
                    Cloudinary::destroy($oldFile->public_id, [
                        'resource_type' => $oldFile->resource_type ?? 'raw',
                        'invalidate' => true,
                    ]);
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

        return response()->json($strategicDocument);
    }

    public function destroy(StrategicDocument $strategicDocument)
    {
        $strategicDocument->load('file');
        $file = $strategicDocument->file;

        DB::transaction(function () use ($strategicDocument, $file) {
            if ($file && $file->public_id) {
                $resourceType = $file->resource_type ?: 'auto';
                $cloudUrl = config('cloudinary.cloud_url')
                    ?? config('filesystems.disks.cloudinary.url')
                    ?? env('CLOUDINARY_URL');

                if (!$cloudUrl) {
                    throw new \RuntimeException('Cloudinary URL not configured');
                }

                // Destroy using UploadApi (no destroy method on the facade instance)
                Cloudinary::uploadApi()->destroy($file->public_id, [
                    'resource_type' => $resourceType,
                    'invalidate' => true,
                ]);
            }

            $strategicDocument->delete();

            if ($file) {
                $file->delete();
            }
        });

        return response()->json(null, 204);
    }
}

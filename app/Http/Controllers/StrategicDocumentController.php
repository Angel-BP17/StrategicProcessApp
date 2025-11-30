<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use DB;
use Illuminate\Http\Request;
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
        return response()->json($strategicDocument);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:50'],   // acta, convenio, informe_kpi, etc.
            'category' => ['nullable', 'string', 'max:50'],   // calidad, alianzas, innovación...
            'description' => ['nullable', 'string'],
            'visibility' => ['nullable', 'in:internal,restricted,public'],
            'file' => ['required', 'file', 'max:10240'],  // 10MB, ajusta si necesitas
        ]);

        $user = $request->user();

        $file = $request->file('file');

        $upload = Cloudinary::uploadFile(
            $file->getRealPath(),
            [
                'folder' => 'uns/strategic-documents',
                'resource_type' => 'auto', // acepta pdf, imágenes, etc.
                // opcional: 'public_id' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time(),
            ]
        );

        $item = StrategicDocument::create($data);
        return response()->json($item, 201);
    }

    public function update(Request $request, StrategicDocument $strategicDocument)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'path' => ['sometimes', 'string', 'max:500'],
            'type' => ['sometimes', 'string', 'max:50'],
            'description' => ['sometimes', 'nullable', 'string'],
        ]);

        $user = $request->user();
        $strategicDocument->load('file');
        $oldFile = $strategicDocument->file;

        DB::transaction(function () use ($request, $data, $user, $strategicDocument, $oldFile) {
            // 2) Si viene un nuevo archivo, lo subimos a Cloudinary y reemplazamos
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                $upload = Cloudinary::uploadFile(
                    $file->getRealPath(),
                    [
                        'folder' => 'uns/strategic-documents',
                        'resource_type' => 'auto',
                    ]
                );

                $result = $upload->getResult();

                // Crear nuevo registro en media_files
                $mediaFile = MediaFile::create([
                    'provider' => 'cloudinary',
                    'disk' => 'cloudinary',
                    'public_id' => $result['public_id'] ?? null,
                    'resource_type' => $result['resource_type'] ?? null,
                    'format' => $result['format'] ?? null,
                    'secure_url' => $result['secure_url'] ?? ($result['url'] ?? null),
                    'thumbnail_url' => null,
                    'folder' => $result['folder'] ?? null,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'bytes' => $result['bytes'] ?? null,
                    'width' => $result['width'] ?? null,
                    'height' => $result['height'] ?? null,
                    'meta' => null,
                    'uploaded_by' => $user?->id,
                ]);

                // Actualizamos el doc para que apunte al nuevo archivo
                $strategicDocument->file_id = $mediaFile->id;

                // Eliminamos el archivo anterior en Cloudinary (si existía)
                if ($oldFile && $oldFile->public_id) {
                    Cloudinary::destroy($oldFile->public_id, [
                        'resource_type' => $oldFile->resource_type ?? 'raw',
                    ]);
                    $oldFile->delete();
                }
            }

            // 3) Actualizamos metadata del documento
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
            // 1) Eliminamos archivo en Cloudinary
            if ($file && $file->public_id) {
                Cloudinary::destroy($file->public_id, [
                    'resource_type' => $file->resource_type ?? 'raw',
                ]);
            }

            // 2) Borramos registros en BD
            $strategicDocument->delete();

            if ($file) {
                $file->delete();
            }
        });

        return response()->json(null, 204);
    }
}

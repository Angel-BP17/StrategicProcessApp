<?php

namespace App\Http\Controllers\Documentation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Documentation\StoreDocumentRequest;
use App\Http\Requests\Documentation\UpdateDocumentRequest;
use App\Models\Documentation\Document;
use App\Models\InnovacionMejoraContinua\Initiative;
use App\Models\Planning\KpiMeasurement;
use App\Models\Quality\Accreditation;
use App\Models\Quality\Audit;
use App\Services\Documentation\DocumentVersionManager;
use App\Support\Documentation\DocumentAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function __construct(private readonly DocumentVersionManager $versionManager)
    {
    }

    public function index(Request $request)
    {
        $filters = [
            'q' => $request->string('q')->toString(),
            'category' => $request->input('category'),
            'status' => $request->input('status'),
        ];

        $documents = Document::query()
            ->with(['creator', 'latestVersion'])
            ->withCount('versions')
            ->search($filters['q'])
            ->when($filters['category'], fn ($query, $category) => $query->where('category', $category))
            ->when($filters['status'], fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            'documents' => $documents,
            'filters' => $filters,
            'categories' => Document::CATEGORIES,
            'statuses' => Document::STATUSES,
            'canManageDocuments' => DocumentAccess::userHasAnyRole($request->user(), DocumentAccess::MANAGE_ROLES),
        ]);
    }

    public function create(Request $request)
    {
        return response()->json([
            'document' => new Document(),
            'categories' => Document::CATEGORIES,
            'statuses' => Document::STATUSES,
            'withFileUpload' => true,
        ]);
    }

    public function store(StoreDocumentRequest $request)
    {
        $validated = $request->validated();

        $metadata = collect($validated)->except(['file', 'version_notes'])->all();

        $document = DB::transaction(function () use ($metadata, $request) {
            $document = Document::create($metadata + [
                'created_by' => $request->user()->id,
            ]);

            if ($request->hasFile('file')) {
                $this->versionManager->createVersion(
                    $document,
                    $request->file('file'),
                    $request->user()->id,
                    $request->input('version_notes')
                );
            }

            return $document;
        });

        return response()->json([
            'message' => 'Documento creado correctamente.',
            'data' => $document->load(['creator', 'latestVersion']),
        ], 201);
    }

    public function show(Document $document, Request $request)
    {
        $document->load([
            'creator',
            'latestVersion',
            'versions' => fn ($query) => $query
                ->with([
                    'uploader',
                    'evidences.initiative',
                    'evidences.audit',
                    'evidences.accreditation',
                    'evidences.kpiMeasurement.kpi',
                ])
                ->orderByDesc('version_number'),
        ])->loadCount(['versions', 'evidences']);

        $managementAllowed = DocumentAccess::userHasAnyRole($request->user(), DocumentAccess::MANAGE_ROLES);

        return response()->json([
            'document' => $document,
            'categories' => Document::CATEGORIES,
            'statuses' => Document::STATUSES,
            'canManageDocuments' => $managementAllowed,
            'versionUploadMaxSize' => config('documentation.max_upload_size_kb'),
            'allowedExtensions' => config('documentation.allowed_extensions', []),
            'initiatives' => $managementAllowed
                ? Initiative::orderBy('title')->get(['id', 'title'])
                : collect(),
            'audits' => $managementAllowed
                ? Audit::orderByDesc('start_date')->get(['id', 'area', 'start_date', 'end_date'])
                : collect(),
            'accreditations' => $managementAllowed
                ? Accreditation::orderByDesc('accreditation_date')->get(['id', 'entity', 'accreditation_date'])
                : collect(),
            'kpiMeasurements' => $managementAllowed
                ? KpiMeasurement::with('kpi')->orderByDesc('measured_at')->get(['id', 'kpi_id', 'measured_at', 'value'])
                : collect(),
        ]);
    }

    public function edit(Document $document)
    {
        return response()->json([
            'document' => $document,
            'categories' => Document::CATEGORIES,
            'statuses' => Document::STATUSES,
            'withFileUpload' => false,
        ]);
    }

    public function update(UpdateDocumentRequest $request, Document $document)
    {
        $document->update($request->validated());

        return response()->json([
            'message' => 'Documento actualizado correctamente.',
            'data' => $document->fresh(['creator', 'latestVersion']),
        ]);
    }

    public function destroy(Document $document)
    {
        if ($document->versions()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar un documento que tiene versiones registradas.',
            ], 422);
        }

        $document->delete();

        return response()->json([
            'message' => 'Documento eliminado.',
        ]);
    }
}

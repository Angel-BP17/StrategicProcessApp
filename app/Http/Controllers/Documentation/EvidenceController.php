<?php

namespace App\Http\Controllers\Documentation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Documentation\StoreEvidenceRequest;
use App\Models\Documentation\Document;
use App\Models\Documentation\Evidence;
use Illuminate\Http\RedirectResponse;

class EvidenceController extends Controller
{
    public function store(StoreEvidenceRequest $request, Document $document): RedirectResponse
    {
        $data = $request->validated();

        $version = $document->versions()->findOrFail($data['document_version_id']);

        $duplicateQuery = $version->evidences()->newQuery();
        foreach (['initiative_id', 'audit_id', 'accreditation_id', 'kpi_measurement_id'] as $field) {
            $value = $data[$field] ?? null;
            $duplicateQuery->where($field, $value);
        }

        if ($duplicateQuery->exists()) {
            return redirect()
                ->route('documents.show', $document)
                ->with('error', 'Esta versión ya está asociada a la misma entidad estratégica.');
        }

        $version->evidences()->create([
            'initiative_id' => $data['initiative_id'] ?? null,
            'audit_id' => $data['audit_id'] ?? null,
            'accreditation_id' => $data['accreditation_id'] ?? null,
            'kpi_measurement_id' => $data['kpi_measurement_id'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()
            ->route('documents.show', $document)
            ->with('ok', 'Evidencia vinculada correctamente.');
    }

    public function destroy(Document $document, Evidence $evidence): RedirectResponse
    {
        $version = $evidence->documentVersion;

        if (!$version || $version->document_id !== $document->id) {
            abort(404);
        }

        $evidence->delete();

        return redirect()
            ->route('documents.show', $document)
            ->with('ok', 'Evidencia eliminada correctamente.');
    }
}
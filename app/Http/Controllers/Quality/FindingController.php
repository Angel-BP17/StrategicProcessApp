<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Quality\Audit;
use App\Models\Quality\Finding;
use Illuminate\Http\Request;

class FindingController extends Controller
{
    public function store(Request $request, Audit $audit)
    {
        $validatedData = $request->validate([
            'description' => 'required|string',
            'classification' => 'required|string|max:255',
            'severity' => 'required|in:low,medium,high',
            'discovery_date' => 'required|date',
            'evidence' => 'nullable|string',
        ]);

        $validatedData['audit_id'] = $audit->id;

        $finding = Finding::create($validatedData);

        return response()->json([
            'message' => 'Hallazgo registrado con éxito.',
            'data' => $finding,
        ], 201);
    }

    public function edit(Finding $finding)
    {
        return response()->json([
            'finding' => $finding,
        ]);
    }

    public function update(Request $request, Finding $finding)
    {
        $validatedData = $request->validate([
            'description' => 'required|string',
            'classification' => 'required|string|max:255',
            'severity' => 'required|in:low,medium,high',
            'discovery_date' => 'required|date',
            'evidence' => 'nullable|string',
        ]);

        $finding->update($validatedData);

        return response()->json([
            'message' => 'Hallazgo actualizado con éxito.',
            'data' => $finding->fresh(),
        ]);
    }

    public function destroy(Finding $finding)
    {
        $finding->correctiveActions()->delete();
        $finding->delete();

        return response()->json([
            'message' => 'Hallazgo eliminado con éxito.',
        ]);
    }
}

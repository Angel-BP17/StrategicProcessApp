<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::latest()->get();

        return response()->json([
            'data' => $partners,
        ]);
    }

    public function create()
    {
        return response()->json();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'contact' => 'nullable|string',
            'legal_representative' => 'nullable|string|max:255',
        ]);

        $validated['contact'] = ['email' => $request->contact];
        $partner = Partner::create($validated);

        return response()->json([
            'message' => 'Socio registrado correctamente.',
            'data' => $partner,
        ], 201);
    }

    public function edit(Partner $partner)
    {
        return response()->json([
            'partner' => $partner,
        ]);
    }

    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'contact' => 'nullable|string',
            'legal_representative' => 'nullable|string|max:255',
        ]);

        $validated['contact'] = ['email' => $request->contact];
        $partner->update($validated);

        return response()->json([
            'message' => 'Socio actualizado correctamente.',
            'data' => $partner->fresh(),
        ]);
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();

        return response()->json([
            'message' => 'Socio eliminado correctamente.',
        ]);
    }
}

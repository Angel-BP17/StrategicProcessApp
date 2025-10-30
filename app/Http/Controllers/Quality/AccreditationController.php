<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Quality\Accreditation;
use Illuminate\Http\Request;

class AccreditationController extends Controller
{
    public function index()
    {
        $accreditations = Accreditation::all();

        return response()->json([
            'data' => $accreditations,
        ]);
    }

    public function create()
    {
        return response()->json();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'entity' => 'required|string|max:255',
            'result' => 'required|string|max:255',
            'accreditation_date' => 'required|date',
            'expiration_date' => 'nullable|date|after_or_equal:accreditation_date',
        ]);

        $accreditation = Accreditation::create($validatedData);

        return response()->json([
            'message' => 'Acreditación registrada con éxito.',
            'data' => $accreditation,
        ], 201);
    }

    public function edit(Accreditation $accreditation)
    {
        return response()->json([
            'accreditation' => $accreditation,
        ]);
    }

    public function update(Request $request, Accreditation $accreditation)
    {
        $validatedData = $request->validate([
            'entity' => 'required|string|max:255',
            'result' => 'required|string|max:255',
            'accreditation_date' => 'required|date',
            'expiration_date' => 'nullable|date|after_or_equal:accreditation_date',
        ]);

        $accreditation->update($validatedData);

        return response()->json([
            'message' => 'Acreditación actualizada con éxito.',
            'data' => $accreditation->fresh(),
        ]);
    }

    public function destroy(Accreditation $accreditation)
    {
        $accreditation->delete();

        return response()->json([
            'message' => 'Acreditación eliminada con éxito.',
        ]);
    }
}

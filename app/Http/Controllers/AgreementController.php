<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Partner;
use Illuminate\Http\Request;

class AgreementController extends Controller
{
    public function index()
    {
        $agreements = Agreement::with('partner')->latest()->get();

        return response()->json([
            'data' => $agreements,
        ]);
    }

    public function create()
    {
        $partners = Partner::all();

        return response()->json([
            'partners' => $partners,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|string|max:50',
            'renewal_date' => 'nullable|date',
            'electronic_signature' => 'nullable|boolean',
        ]);

        // Asignamos el usuario que crea el convenio
        $data['created_by_user_id'] = auth()->id();

        $agreement = Agreement::create($data);

        return response()->json([
            'message' => 'Convenio creado correctamente.',
            'data' => $agreement->load('partner'),
        ], 201);
    }

    public function edit(Agreement $agreement)
    {
        $partners = Partner::all();

        return response()->json([
            'agreement' => $agreement->load('partner'),
            'partners' => $partners,
        ]);
    }

    public function update(Request $request, Agreement $agreement)
    {
        $data = $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|string|max:50',
            'renewal_date' => 'nullable|date',
            'electronic_signature' => 'nullable|boolean',
        ]);

        $agreement->update($data);

        return response()->json([
            'message' => 'Convenio actualizado correctamente.',
            'data' => $agreement->fresh()->load('partner'),
        ]);
    }

    public function destroy(Agreement $agreement)
    {
        $agreement->delete();

        return response()->json([
            'message' => 'Convenio eliminado correctamente.',
        ]);
    }
}

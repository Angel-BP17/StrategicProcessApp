<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Partner;
use Illuminate\Http\Request;

class AgreementController extends Controller
{
    public function index()
    {
        // Siempre mostramos el panel unificado
        $agreements = Agreement::with('partner')->latest()->get();
        return view('alliances.index', compact('agreements'));
    }

    public function create()
    {
        $partners = Partner::all();
        return view('alliances.agreements.create', compact('partners'));
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

        Agreement::create($data);

        return redirect()->route('alliances.index')
                         ->with('success', 'Convenio creado correctamente.');
    }

    public function edit(Agreement $agreement)
    {
        $partners = Partner::all();
        return view('alliances.agreements.edit', compact('agreement', 'partners'));
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

        return redirect()->route('alliances.index')
                         ->with('success', 'Convenio actualizado correctamente.');
    }

    public function destroy(Agreement $agreement)
    {
        $agreement->delete();
        return redirect()->route('alliances.index')
                         ->with('success', 'Convenio eliminado correctamente.');
    }
}

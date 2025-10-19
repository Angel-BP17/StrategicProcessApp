<?php

namespace App\Http\Controllers;
use App\Models\Partner;
use Illuminate\Http\Request;


class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::latest()->get();
        return view('alliances.index', compact('partners'));
    }

    public function create()
    {
        return view('alliances.partners.create');
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
    Partner::create($validated);

    // Redirigir al index general (alianzas)
    return redirect()->route('alliances.index')
        ->with('success', 'Socio registrado correctamente.');
    }

    public function edit(Partner $partner)
    {
        return view('alliances.partners.edit', compact('partner'));
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

        return redirect()->route('alliances.index')
    ->with('success', 'Socio actualizado correctamente.');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();
        return redirect()->route('alliances.index')
            ->with('success', 'Socio eliminado correctamente.');
    }
}
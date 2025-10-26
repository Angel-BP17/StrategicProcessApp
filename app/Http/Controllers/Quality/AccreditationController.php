<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Quality\Accreditation;
use Illuminate\Http\Request;

class AccreditationController extends Controller
{
    /**
     * Muestra el listado de acreditaciones.
     */
    public function index()
    {
        $accreditations = Accreditation::all();

        return view('quality.accreditations.index', [
            'accreditations' => $accreditations
        ]);
    }

    /**
     * Muestra el formulario para crear una nueva acreditación.
     */
    public function create()
    {
        // Aún no necesitamos enviar datos extra
        return view('quality.accreditations.create');
    }

    /**
     * Guarda la nueva acreditación (lo programaremos después).
     */
    public function store(Request $request)
    {
        // 1. Validamos los datos del formulario
        $validatedData = $request->validate([
            'entity' => 'required|string|max:255',
            'result' => 'required|string|max:255',
            'accreditation_date' => 'required|date',
            'expiration_date' => 'nullable|date|after_or_equal:accreditation_date',
            // 'document_version_id' lo dejaremos pendiente para la subida de archivos
        ]);

        // 2. Creamos el registro en la base de datos
        Accreditation::create($validatedData);

        // 3. Redirigimos de vuelta al listado con un mensaje
        return redirect()->route('quality.accreditations.index')
            ->with('success', 'Acreditación registrada con éxito.');
    }

    public function edit(Accreditation $accreditation)
    {
        // Enviamos la acreditación que queremos editar a la nueva vista
        return view('quality.accreditations.edit', [
            'accreditation' => $accreditation
        ]);
    }


    public function update(Request $request, Accreditation $accreditation)
    {
        // 1. Validamos los datos (igual que en 'store')
        $validatedData = $request->validate([
            'entity' => 'required|string|max:255',
            'result' => 'required|string|max:255',
            'accreditation_date' => 'required|date',
            'expiration_date' => 'nullable|date|after_or_equal:accreditation_date',
        ]);

        // 2. Actualizamos el registro existente
        $accreditation->update($validatedData);

        // 3. Redirigimos de vuelta al listado
        return redirect()->route('quality.accreditations.index')
            ->with('success', 'Acreditación actualizada con éxito.');
    }

    public function destroy(Accreditation $accreditation)
    {
        // 1. Eliminamos el registro
        $accreditation->delete();

        // 2. Redirigimos de vuelta al listado con un mensaje de éxito
        return redirect()->route('quality.accreditations.index')
            ->with('success', 'Acreditación eliminada con éxito.');
    }
}
<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Quality\Audit; // Asegúrate de que este 'use' esté correcto
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- AÑADE ESTO, lo usaremos para el 'user_id'
use App\Models\User;

class AuditController extends Controller
{
    /**
     * Muestra una lista de todas las auditorías.
     */
    public function index()
    {
        $audits = Audit::all();
        return view('quality.audits.index', [
            'audits' => $audits
        ]);
    }

    
    public function create()
    {
        // Por ahora, solo mostramos la vista del formulario
        return view('quality.audits.create');
    }

    
    public function store(Request $request)
    {
        
        $validatedData = $request->validate([
            'area' => 'required|string|max:255',
            'type' => 'required|in:internal,external', // 'in' valida que sea uno de esos dos valores
            'objective' => 'required|string|max:255',
            'range' => 'required|string|max:255', 
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        
        $validatedData['user_id'] = Auth::id(); // Asignamos el ID del usuario que está logueado
        $validatedData['state'] = 'planned'; 

        // 3. Creamos la auditoría en la base de datos
        Audit::create($validatedData);

        // 4. Redirigimos al usuario de vuelta al listado
        return redirect()->route('quality.audits.index')->with('success', 'Auditoría planificada con éxito.');
    }

    public function show(Audit $audit)
    {
        // 1. Cargamos las relaciones para evitar consultas N+1
        // (El responsable, los hallazgos, y las acciones correctivas de esos hallazgos)
        $audit->load('responsible', 'findings', 'findings.correctiveActions.responsible');

        $users = \App\Models\User::all();
        

        // 2. Enviamos la auditoría (con todos sus datos) a la nueva vista
        return view('quality.audits.show', [
            'audit' => $audit,
            'users' => $users
        ]);
    }



  
}
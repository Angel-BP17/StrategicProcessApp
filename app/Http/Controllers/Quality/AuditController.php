<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Quality\Audit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditController extends Controller
{
    public function index()
    {
        $audits = Audit::with('responsible')->get();

        return response()->json([
            'data' => $audits,
        ]);
    }

    public function create()
    {
        return response()->json();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'area' => 'required|string|max:255',
            'type' => 'required|in:internal,external',
            'objective' => 'required|string|max:255',
            'audit_range' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $validatedData['user_id'] = Auth::id();
        $validatedData['state'] = 'planned';

        $audit = Audit::create($validatedData);

        return response()->json([
            'message' => 'Auditoría planificada con éxito.',
            'data' => $audit->load('responsible'),
        ], 201);
    }

    public function show(Audit $audit)
    {
        $audit->load('responsible', 'findings', 'findings.correctiveActions.responsible');
        $users = User::all();

        return response()->json([
            'audit' => $audit,
            'users' => $users,
        ]);
    }

    public function edit(Audit $audit)
    {
        return response()->json([
            'audit' => $audit,
        ]);
    }

    public function update(Request $request, Audit $audit)
    {
        $validatedData = $request->validate([
            'area' => 'required|string|max:255',
            'type' => 'required|in:internal,external',
            'objective' => 'required|string|max:255',
            'audit_range' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'state' => 'required|in:planned,in_progress,completed,cancelled',
        ]);

        $audit->update($validatedData);

        return response()->json([
            'message' => 'Auditoría actualizada con éxito.',
            'data' => $audit->fresh()->load('responsible'),
        ]);
    }

    public function destroy(Audit $audit)
    {
        $audit->findings()->each(function ($finding) {
            $finding->correctiveActions()->delete();
        });
        $audit->findings()->delete();
        $audit->delete();

        return response()->json([
            'message' => 'Auditoría eliminada con éxito.',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use IncadevUns\CoreDomain\Models\StrategicDocument;

class StrategicDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
        
        /*
        $this->middleware(['permission:strategic_documents.view'])->only(['index', 'show']);
        $this->middleware(['permission:strategic_documents.create'])->only(['store']);
        $this->middleware(['permission:strategic_documents.update'])->only(['update']);
        $this->middleware(['permission:strategic_documents.delete'])->only(['destroy']);*/
    }

    public function index()
    {
        return response()->json(StrategicDocument::query()->latest('id')->paginate(20));
    }

    public function show(StrategicDocument $strategicDocument)
    {
        return response()->json($strategicDocument);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'path' => ['required', 'string', 'max:500'],
            'type' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ]);
        $item = StrategicDocument::create($data);
        return response()->json($item, 201);
    }

    public function update(Request $request, StrategicDocument $strategicDocument)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'path' => ['sometimes', 'string', 'max:500'],
            'type' => ['sometimes', 'string', 'max:50'],
            'description' => ['sometimes', 'nullable', 'string'],
        ]);
        $strategicDocument->update($data);
        return response()->json($strategicDocument);
    }

    public function destroy(StrategicDocument $strategicDocument)
    {
        $strategicDocument->delete();
        return response()->json(['message' => 'Deleted'], 204);
    }
}

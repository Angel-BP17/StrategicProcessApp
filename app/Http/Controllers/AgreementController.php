<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use IncadevUns\CoreDomain\Models\Agreement;

class AgreementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
        $this->middleware(['permission:agreements.view'])->only(['index', 'show']);
        $this->middleware(['permission:agreements.create'])->only(['store']);
        $this->middleware(['permission:agreements.update'])->only(['update']);
        $this->middleware(['permission:agreements.delete'])->only(['destroy']);
    }

    public function index()
    {
        return response()->json(Agreement::with('organization')->latest('id')->paginate(20));
    }

    public function show(Agreement $agreement)
    {
        return response()->json($agreement->load('organization'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'renewal_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'purpose' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:50'],
        ]);
        $item = Agreement::create($data);
        return response()->json($item->load('organization'), 201);
    }

    public function update(Request $request, Agreement $agreement)
    {
        $data = $request->validate([
            'organization_id' => ['sometimes', 'integer', 'exists:organizations,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'start_date' => ['sometimes', 'date'],
            'renewal_date' => ['sometimes', 'nullable', 'date', 'after_or_equal:start_date'],
            'purpose' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'string', 'max:50'],
        ]);
        $agreement->update($data);
        return response()->json($agreement->load('organization'));
    }

    public function destroy(Agreement $agreement)
    {
        $agreement->delete();
        return response()->json(['message' => 'Deleted'], 204);
    }
}

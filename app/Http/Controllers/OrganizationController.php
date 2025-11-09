<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Incadev\Core\Models\Organization;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
        $this->middleware(['permission:organizations.view'])->only(['index', 'show']);
        $this->middleware(['permission:organizations.create'])->only(['store']);
        $this->middleware(['permission:organizations.update'])->only(['update']);
        $this->middleware(['permission:organizations.delete'])->only(['destroy']);
    }

    public function index()
    {
        return response()->json(Organization::query()->latest('id')->paginate(20));
    }

    public function show(Organization $organization)
    {
        return response()->json($organization);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ruc' => ['required', 'string', 'max:20', 'unique:organizations,ruc'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:100'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],
        ]);
        $item = Organization::create($data);
        return response()->json($item, 201);
    }

    public function update(Request $request, Organization $organization)
    {
        $data = $request->validate([
            'ruc' => ['sometimes', 'string', 'max:20', 'unique:organizations,ruc,' . $organization->id],
            'name' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', 'string', 'max:100'],
            'contact_phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'contact_email' => ['sometimes', 'nullable', 'email', 'max:255'],
        ]);
        $organization->update($data);
        return response()->json($organization);
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();
        return response()->json(['message' => 'Deleted'], 204);
    }
}
